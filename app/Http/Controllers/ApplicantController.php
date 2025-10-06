<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\RequireWorkExperience;
use App\Models\RequireEducation;
use App\Models\RequireTraining;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ApplicantController extends Controller
{
    public function index()
    {
        return view('applicant.index');
    }

    public function create()
    {
        return view('applicant.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'FirstName' => 'required|string|max:255',
            'MiddleName' => 'nullable|string|max:255',
            'LastName' => 'required|string|max:255',
            'Gender' => 'required|in:Male,Female',
            'DateOfBirth' => 'required|date',
            'Address' => 'required|string|max:500',
            'City' => 'required|string|max:255',
            'Gmail' => 'required|email|max:255',
            'LinkedIn' => 'nullable|url|max:255',
            'Instagram' => 'nullable|string|max:255',
            'Phone' => 'required|string|max:20',
            // CV must be PDF only and max 5MB
            'CVPath' => 'nullable|file|mimes:pdf|max:5120',
            'PhotoPath' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle file uploads only to 'mlnas' disk. If any upload fails, rollback any uploaded files and return error.
        $uploaded = [];

        if ($request->hasFile('CVPath')) {
            $file = $request->file('CVPath');
            $originalName = $file->getClientOriginalName();
            $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);

            try {
                $path = $file->storeAs('applicants/cv', $sanitized, 'mlnas');
                if ($path === false) {
                    throw new \Exception('Storage returned false');
                }
                $validated['CVPath'] = $path;
                $uploaded[] = ['disk' => 'mlnas', 'path' => $path];
            } catch (\Exception $e) {
                \Log::error('MLNAS CV upload failed: ' . $e->getMessage());
                // cleanup any previously uploaded files
                foreach ($uploaded as $u) {
                    try {
                        Storage::disk($u['disk'])->delete($u['path']);
                    } catch (\Exception $ex) {
                        \Log::warning('Failed to cleanup uploaded file after CV failure: ' . $ex->getMessage());
                    }
                }

                return redirect()->back()->withInput()->withErrors([ 'CVPath' => 'Gagal mengunggah CV ke server penyimpanan. Silakan coba lagi atau hubungi admin.' ]);
            }
        }

        if ($request->hasFile('PhotoPath')) {
            $file = $request->file('PhotoPath');
            $originalName = $file->getClientOriginalName();
            $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);

            try {
                $path = $file->storeAs('applicants/photos', $sanitized, 'mlnas');
                if ($path === false) {
                    throw new \Exception('Storage returned false');
                }
                $validated['PhotoPath'] = $path;
                $uploaded[] = ['disk' => 'mlnas', 'path' => $path];
            } catch (\Exception $e) {
                \Log::error('MLNAS Photo upload failed: ' . $e->getMessage());
                // cleanup any previously uploaded files
                foreach ($uploaded as $u) {
                    try {
                        Storage::disk($u['disk'])->delete($u['path']);
                    } catch (\Exception $ex) {
                        \Log::warning('Failed to cleanup uploaded file after Photo failure: ' . $ex->getMessage());
                    }
                }

                return redirect()->back()->withInput()->withErrors([ 'PhotoPath' => 'Gagal mengunggah Foto ke server penyimpanan. Silakan coba lagi atau hubungi admin.' ]);
            }
        }

        $applicant = Applicant::create($validated);

        // Handle work experiences
        if ($request->has('work_experiences')) {
            foreach ($request->work_experiences as $workExp) {
                if (!empty($workExp['CompanyName'])) {
                    RequireWorkExperience::create([ 
                        'RequireID' => $applicant->RequireID,
                        'CompanyName' => $workExp['CompanyName'],
                        'JobLevel' => $workExp['JobLevel'],
                        'StartDate' => $workExp['StartDate'],
                        'EndDate' => $workExp['EndDate'],
                        'Salary' => $workExp['Salary'],
                    ]);
                }
            }
        }

        // Handle educations
        if ($request->has('educations')) {
            foreach ($request->educations as $education) {
                if (!empty($education['InstitutionName'])) {
                    RequireEducation::create([
                        'RequireID' => $applicant->RequireID,
                        'InstitutionName' => $education['InstitutionName'],
                        'Major' => $education['Major'],
                        'StartDate' => $education['StartDate'],
                        'EndDate' => $education['EndDate'],
                    ]);
                }
            }
        }

        // Handle trainings
        if ($request->has('trainings')) {
            foreach ($request->trainings as $training) {
                if (!empty($training['TrainingName'])) {
                    RequireTraining::create([
                        'RequireID' => $applicant->RequireID,
                        'TrainingName' => $training['TrainingName'],
                        'CertificateNo' => $training['CertificateNo'],
                        'StartTrainingDate' => $training['StartTrainingDate'],
                        'EndTrainingDate' => $training['EndTrainingDate'],
                    ]);
                }
            }
        }

        return redirect()->route('applicant.success')->with('success', 'Data diri berhasil disimpan!');
    }

    public function success()
    {
        return view('applicant.success');
    }

}
