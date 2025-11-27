<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\JobVacancy;
use App\Models\ApplyJob;
use App\Models\Applicant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class JobVacancyController extends Controller
{
    public function index()
    {
        // Get active job vacancies (status 1,2,3,4 and within date range)
        // Load count of hired candidates for each job vacancy
        $jobVacancies = JobVacancy::active()
            ->select([
                'job_vacancy_id',
                'job_vacancy_name',
                'job_vacancy_level_name',
                'job_vacancy_job_desc',
                'job_vacancy_job_spec',
                'job_vacancy_start_date',
                'job_vacancy_end_date',
                'job_vacancy_man_power',
                'job_vacancy_hris_location_id',
                'job_vacancy_status_id',
            ])
            ->withCount([
                'applyJobs as hired_count' => function($query) {
                    $query->where('apply_jobs_status', 5); // Status Hired
                }
            ])
            ->orderBy('job_vacancy_start_date', 'desc')
            ->get()
            ->filter(function($job) {
                // Filter out jobs with no available positions
                $hiredCount = $job->hired_count ?? 0;
                $manPower = $job->job_vacancy_man_power ?? 0;
                $available = max(0, $manPower - $hiredCount);
                return $available > 0; // Only show jobs with available positions
            })
            ->map(function($job) {
                // Attach location name from HRIS mapping
                if (!empty($job->job_vacancy_hris_location_id)) {
                    $hrisService = new \App\Services\HrisApiService();
                    $locationName = $hrisService->getLocationName($job->job_vacancy_hris_location_id);
                    // Add location as dynamic attribute
                    $job->location = $locationName;
                }
                return $job;
            })
            ->values(); // Re-index array after filtering

        // Check if user has profile and if already applied to any job
        $userHasApplied = false;
        $appliedJobId = null;
        $hasProfile = false;
        $profileId = null;
        $isHired = false;
        $applyJobStatus = null;
        
        if (Auth::check()) {
            $profile = Applicant::where('user_id', Auth::id())->first();
            if ($profile) {
                $hasProfile = true;
                $profileId = $profile->getKey();
            }
            $existingApplication = ApplyJob::where('user_id', Auth::id())->first();
            if ($existingApplication) {
                $userHasApplied = true;
                $appliedJobId = $existingApplication->job_vacancy_id;
                $applyJobStatus = $existingApplication->apply_jobs_status;
                // Check if user is hired (status 5)
                $isHired = ($existingApplication->apply_jobs_status == 5);
            }
        }

        return view('applicant.index', compact('jobVacancies', 'userHasApplied', 'appliedJobId', 'hasProfile', 'profileId', 'isHired', 'applyJobStatus'));
    }

    public function apply(Request $request)
    {
        $request->validate([
            'job_vacancy_id' => 'required|exists:job_vacancy,job_vacancy_id',
        ]);

        $user = Auth::user();
        
        // Check if user is authenticated
        if (!$user) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu untuk melamar pekerjaan.');
        }

        // Check if user has already applied to any job
        $existingApplication = ApplyJob::where('user_id', $user->id)->first();
        if ($existingApplication) {
            return redirect()->back()->with('error', 'Anda sudah melamar pekerjaan sebelumnya. Satu user hanya bisa melamar satu pekerjaan.');
        }

        // Check if user has completed their profile
        $applicant = Applicant::where('user_id', $user->id)->first();
        if (!$applicant) {
            return redirect()->route('applicant.create')->with('error', 'Silakan lengkapi data diri terlebih dahulu sebelum melamar pekerjaan.');
        }

        // Check if the job vacancy is still active
        $jobVacancy = JobVacancy::find($request->job_vacancy_id);
        if (!$jobVacancy->isActive()) {
            return redirect()->back()->with('error', 'Lowongan pekerjaan ini sudah tidak tersedia.');
        }

        try {
            DB::beginTransaction();

            // Create application
            $applyJob = ApplyJob::create([
                'job_vacancy_id' => $request->job_vacancy_id,
                'user_id' => $user->id,
                'apply_jobs_status' => 1, // Status: Review Applicant
                'requireid' => $applicant->getKey(),
                'require_id' => $applicant->getKey(),
                'apply_date' => now()->toDateString(),
            ]);

            // Kirim ke HRIS eksternal akan dihandle oleh ApplyJobObserver
            // Observer akan otomatis memanggil HRIS API saat ApplyJob created

            DB::commit();

            // Setelah lamaran berhasil dibuat, generate PDF formulir dan simpan ke MLNAS
            try {
                // Muat relasi yang dibutuhkan untuk PDF
                $applicant->load(['educations', 'workExperiences', 'trainings']);

                $educations = $applicant->educations;
                $workExperiences = $applicant->workExperiences;
                $trainings = $applicant->trainings;

                // Job title untuk ditampilkan di PDF
                $jobTitle = $jobVacancy->job_vacancy_name ?? null;

                $pdf = app('dompdf.wrapper')->loadView('applicant.pdf_complete', compact('applicant', 'educations', 'workExperiences', 'trainings', 'jobTitle'));
                $pdf->setPaper('A4', 'portrait');

                // Folder per job vacancy, gunakan slug dari nama lowongan
                $jobName = $jobVacancy->job_vacancy_name ?? 'job';
                $jobSlug = Str::slug($jobName) ?: 'job';
                $folder = 'applicants/' . $jobSlug;

                $disk = Storage::disk('mlnas');

                // Pastikan folder ada
                if (! $disk->exists($folder)) {
                    $disk->makeDirectory($folder);
                }

                // Nama file utama berdasarkan firstname, dengan fallback & penanganan duplikasi
                $baseName = Str::slug($applicant->firstname ?? 'candidate');
                if ($baseName === '') {
                    $baseName = 'candidate';
                }

                $fileName = $baseName . '.pdf';
                $path = $folder . '/' . $fileName;
                $counter = 1;

                // Jika sudah ada file dengan nama yang sama, tambahkan suffix -1, -2, dst
                while ($disk->exists($path)) {
                    $fileName = $baseName . '-' . $counter . '.pdf';
                    $path = $folder . '/' . $fileName;
                    $counter++;
                }

                $disk->put($path, $pdf->output());
            } catch (\Throwable $e) {
                // Jangan ganggu user kalau gagal generate/simpan PDF, cukup log saja
                Log::error('Failed to generate applicant PDF on MLNAS after apply', [
                    'user_id' => $user->id,
                    'applicant_id' => $applicant->getKey(),
                    'job_vacancy_id' => $jobVacancy->job_vacancy_id,
                    'error' => $e->getMessage(),
                ]);
            }

            return redirect()->back()->with('success', 'Lamaran Anda berhasil dikirim! Tim HR akan menghubungi Anda untuk proses selanjutnya.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.');
        }
    }

    public function show($id)
    {
        $jobVacancy = JobVacancy::findOrFail($id);
        
        // Check if user has already applied to any job
        $userHasApplied = false;
        $appliedJobId = null;
        
        if (Auth::check()) {
            $existingApplication = ApplyJob::where('user_id', Auth::id())->first();
            if ($existingApplication) {
                $userHasApplied = true;
                $appliedJobId = $existingApplication->job_vacancy_id;
            }
        }

        return view('applicant.show', compact('jobVacancy', 'userHasApplied', 'appliedJobId'));
    }
}
