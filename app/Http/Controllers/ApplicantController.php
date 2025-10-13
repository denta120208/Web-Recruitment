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
            'LastName' => 'nullable|string|max:255',
            'Gender' => 'required|in:Male,Female',
            'DateOfBirth' => 'required|date',
            'Address' => 'required|string|max:500',
            'City' => 'required|string|max:255',
            'Gmail' => 'required|email|max:255',
            'LinkedIn' => 'nullable|url|max:255',
            'Instagram' => 'nullable|string|max:255',
            'Phone' => 'required|string|max:20',
           
            'CVPath' => 'required|file|mimes:pdf|max:5120',
            'PhotoPath' => 'required|image|mimes:jpeg,png,jpg|max:5120',
        ]);

       
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

        // Ensure at least one education entry is provided
        $hasEducation = false;
        if ($request->has('educations')) {
            foreach ($request->educations as $education) {
                if (!empty($education['InstitutionName'])) {
                    $hasEducation = true;
                    break;
                }
            }
        }

        if (! $hasEducation) {
            return redirect()->back()->withInput()->withErrors(['educations' => 'Mohon tambahkan minimal 1 data pendidikan.']);
        }

        // Ensure database NOT NULL columns get a non-null value when fields are optional in the form
        if (!isset($validated['LastName']) || is_null($validated['LastName'])) {
            $validated['LastName'] = '';
        }

        $applicant = Applicant::create($validated);

       
        if ($request->has('work_experiences')) {
            foreach ($request->work_experiences as $workExp) {
                if (!empty($workExp['CompanyName'])) {
                    $isCurrent = isset($workExp['is_current']) && $workExp['is_current'] == '1';
                    RequireWorkExperience::create([ 
                        'RequireID' => $applicant->RequireID,
                        'CompanyName' => $workExp['CompanyName'],
                        'JobLevel' => $workExp['JobLevel'],
                        'StartDate' => $workExp['StartDate'],
                        'EndDate' => $isCurrent ? null : ($workExp['EndDate'] ?? null),
                        'IsCurrent' => $isCurrent ? 1 : 0,
                        'Salary' => $workExp['Salary'] ?? null,
                    ]);
                }
            }
        }

      
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
