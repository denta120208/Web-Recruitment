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

class JobVacancyController extends Controller
{
    public function index()
    {
        // Get active job vacancies (status 1,2,3,4 and within date range)
        $jobVacancies = JobVacancy::active()
            ->orderBy('job_vacancy_start_date', 'desc')
            ->get();

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
                'apply_jobs_status' => 1, // Status: Review Aplicant
                'requireid' => $applicant->getKey(),
                'require_id' => $applicant->getKey(),
                'apply_date' => now()->toDateString(),
            ]);

            // Kirim ke HRIS eksternal akan dihandle oleh ApplyJobObserver
            // Observer akan otomatis memanggil HRIS API saat ApplyJob created

            DB::commit();

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
