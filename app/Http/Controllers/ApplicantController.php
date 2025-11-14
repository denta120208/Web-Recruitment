<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Applicant;
use App\Models\RequireWorkExperience;
use App\Models\RequireEducation;
use App\Models\RequireTraining;
use App\Services\HrisApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Filament\Facades\Filament;

class ApplicantController extends Controller
{
    public function index()
    {
        return view('applicant.index');
    }

    public function create()
    {
        $user = auth()->user();
        $existingApplication = Applicant::where('user_id', $user->id)->first();
        
        if ($existingApplication) {
            return redirect()->route('applicant.edit', $existingApplication->getKey());
        }
        
        // Get user name parts from registration
        $nameParts = explode(' ', $user->name);
        $firstName = $nameParts[0] ?? '';
        $lastName = '';
        $middleName = '';
        
        if (count($nameParts) > 2) {
            $middleName = implode(' ', array_slice($nameParts, 1, -1));
            $lastName = end($nameParts);
        } elseif (count($nameParts) == 2) {
            $lastName = $nameParts[1];
        }
        
        // Pass user email and name parts for auto-fill
        $userEmail = $user->email ?? '';
        
        // Get educations from HRIS API
        $hrisService = new HrisApiService();
        $hrisEducations = $hrisService->getAllEducations() ?? [];
        
        return view('applicant.create', compact('userEmail', 'hrisEducations', 'firstName', 'middleName', 'lastName'));
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

        // Map PascalCase form inputs to lowercase model attributes for PostgreSQL
        $modelData = [
            'firstname' => $validated['FirstName'],
            'middlename' => $validated['MiddleName'] ?? null,
            'lastname' => $validated['LastName'],
            'gender' => $validated['Gender'],
            'dateofbirth' => $validated['DateOfBirth'],
            'address' => $validated['Address'],
            'city' => $validated['City'],
            'gmail' => $validated['Gmail'],
            'linkedin' => $validated['LinkedIn'] ?? null,
            'instagram' => $validated['Instagram'] ?? null,
            'phone' => $validated['Phone'],
            'cvpath' => $validated['CVPath'] ?? null,
            'photopath' => $validated['PhotoPath'] ?? null,
            'user_id' => auth()->id(),
        ];

        $applicant = Applicant::create($modelData);

       
        if ($request->has('work_experiences')) {
            foreach ($request->work_experiences as $workExp) {
                if (!empty($workExp['CompanyName'])) {
                    $isCurrent = isset($workExp['is_current']) && $workExp['is_current'] == '1';
                    RequireWorkExperience::create([ 
                        'requireid' => $applicant->getKey(),
                        'companyname' => $workExp['CompanyName'],
                        'joblevel' => $workExp['JobLevel'],
                        'startdate' => $workExp['StartDate'],
                        'enddate' => $isCurrent ? null : ($workExp['EndDate'] ?? null),
                        'iscurrent' => $isCurrent ? 1 : 0,
                        'salary' => $workExp['Salary'] ?? null,
                        'eexp_comments' => $workExp['Comments'] ?? null,
                    ]);
                }
            }
        }

      
        if ($request->has('educations')) {
            foreach ($request->educations as $education) {
                if (!empty($education['InstitutionName'])) {
                    RequireEducation::create([
                        'requireid' => $applicant->getKey(),
                        'education_id' => isset($education['EducationId']) && $education['EducationId'] !== '' ? (int) $education['EducationId'] : null,
                        'institutionname' => $education['InstitutionName'],
                        'major' => $education['Major'] ?? null,
                        'year' => $education['Year'] ?? null,
                        'score' => $education['Score'] ?? null,
                        'startdate' => $education['StartDate'],
                        'enddate' => $education['EndDate'] ?? null,
                    ]);
                }
            }
        }

        
        if ($request->has('trainings')) {
            foreach ($request->trainings as $training) {
                if (!empty($training['TrainingName'])) {
                    RequireTraining::create([
                        'requireid' => $applicant->getKey(),
                        'trainingname' => $training['TrainingName'],
                        'certificateno' => $training['CertificateNo'],
                        'starttrainingdate' => $training['StartTrainingDate'],
                        'endtrainingdate' => $training['EndTrainingDate'],
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

    public function edit($id)
    {
        $user = auth()->user();
        $applicant = Applicant::where('requireid', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Get educations from HRIS API
        $hrisService = new HrisApiService();
        $hrisEducations = $hrisService->getAllEducations() ?? [];
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
            
        return view('applicant.edit', compact('applicant', 'educations', 'workExperiences', 'trainings', 'hrisEducations'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $applicant = Applicant::where('requireid', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

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
            'CVPath' => 'nullable|file|mimes:pdf|max:5120',
            'PhotoPath' => 'nullable|image|mimes:jpeg,png,jpg|max:5120',
        ]);

        // Handle file uploads
        if ($request->hasFile('CVPath')) {
            $file = $request->file('CVPath');
            $originalName = $file->getClientOriginalName();
            $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
            $path = $file->storeAs('applicants/cv', $sanitized, 'mlnas');
            $validated['CVPath'] = $path;
        }

        if ($request->hasFile('PhotoPath')) {
            $file = $request->file('PhotoPath');
            $originalName = $file->getClientOriginalName();
            $sanitized = preg_replace('/[^A-Za-z0-9_.-]/', '_', $originalName);
            $path = $file->storeAs('applicants/photos', $sanitized, 'mlnas');
            $validated['PhotoPath'] = $path;
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

        // Map PascalCase form inputs to lowercase model attributes for PostgreSQL
        $modelData = [
            'firstname' => $validated['FirstName'],
            'middlename' => $validated['MiddleName'] ?? null,
            'lastname' => $validated['LastName'],
            'gender' => $validated['Gender'],
            'dateofbirth' => $validated['DateOfBirth'],
            'address' => $validated['Address'],
            'city' => $validated['City'],
            'gmail' => $validated['Gmail'],
            'linkedin' => $validated['LinkedIn'] ?? null,
            'instagram' => $validated['Instagram'] ?? null,
            'phone' => $validated['Phone'],
        ];

        // Only update file paths if new files were uploaded
        if (isset($validated['CVPath'])) {
            $modelData['cvpath'] = $validated['CVPath'];
        }
        if (isset($validated['PhotoPath'])) {
            $modelData['photopath'] = $validated['PhotoPath'];
        }

        $applicant->update($modelData);

        // Update work experiences 
        if ($request->has('work_experiences')) {
            $applicant->workExperiences()->delete();
            foreach ($request->work_experiences as $workExp) {
                if (!empty($workExp['CompanyName'])) {
                    $isCurrent = isset($workExp['is_current']) && $workExp['is_current'] == '1';
                    RequireWorkExperience::create([ 
                        'requireid' => $applicant->getKey(),
                        'companyname' => $workExp['CompanyName'],
                        'joblevel' => $workExp['JobLevel'],
                        'startdate' => $workExp['StartDate'],
                        'enddate' => $isCurrent ? null : ($workExp['EndDate'] ?? null),
                        'iscurrent' => $isCurrent ? 1 : 0,
                        'salary' => $workExp['Salary'] ?? null,
                        'eexp_comments' => $workExp['Comments'] ?? null,
                    ]);
                }
            }
        }

        // Update educations
        if ($request->has('educations')) {
            $applicant->educations()->delete();
            foreach ($request->educations as $education) {
                if (!empty($education['InstitutionName'])) {
                    RequireEducation::create([
                        'requireid' => $applicant->getKey(),
                        'education_id' => isset($education['EducationId']) && $education['EducationId'] !== '' ? (int) $education['EducationId'] : null,
                        'institutionname' => $education['InstitutionName'],
                        'major' => $education['Major'] ?? null,
                        'year' => $education['Year'] ?? null,
                        'score' => $education['Score'] ?? null,
                        'startdate' => $education['StartDate'],
                        'enddate' => $education['EndDate'] ?? null,
                    ]);
                }
            }
        }

        // Update trainings
        if ($request->has('trainings')) {
            $applicant->trainings()->delete();
            foreach ($request->trainings as $training) {
                if (!empty($training['TrainingName'])) {
                    RequireTraining::create([
                        'requireid' => $applicant->getKey(),
                        'trainingname' => $training['TrainingName'],
                        'certificateno' => $training['CertificateNo'],
                        'starttrainingdate' => $training['StartTrainingDate'],
                        'endtrainingdate' => $training['EndTrainingDate'],
                    ]);
                }
            }
        }

        return redirect()->route('applicant.success')->with('success', 'Profil berhasil diperbarui!');
    }

    public function serveFile(Request $request, string $type, string $filename)
    {
        // Only allow authenticated user to access their own files
        $user = auth()->user();
        if (!$user) {
            abort(401);
        }

        $applicant = Applicant::where('user_id', $user->id)->first();
        if (!$applicant) {
            abort(404);
        }

        // Determine which path to serve based on requested type
        if ($type === 'cv') {
            $path = $applicant->cvpath;
        } elseif ($type === 'photo') {
            $path = $applicant->photopath;
        } else {
            abort(404);
        }

        // Ensure path exists and is on the configured disk
        if (empty($path) || !\Illuminate\Support\Facades\Storage::disk('mlnas')->exists($path)) {
            abort(404);
        }

        // Stream the file inline (browser will render images/pdf)
        // We ignore the provided $filename to prevent path tampering
        $disk = Storage::disk('mlnas');
        $stream = $disk->readStream($path);
        if ($stream === false) {
            abort(404);
        }

        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $downloadName = basename($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    public function servePath(Request $request, string $path)
    {
        $user = auth()->user();
        if (!$user) abort(401);

        $applicant = Applicant::where('user_id', $user->id)->first();
        if (!$applicant) abort(404);

        // Only allow access if the requested path matches the user's saved file paths
        $allowedPaths = array_filter([
            $applicant->cvpath,
            $applicant->photopath,
        ]);

        // Normalize comparison
        $normalizedRequested = ltrim($path, '/');
        $isAllowed = collect($allowedPaths)->contains(function ($p) use ($normalizedRequested) {
            return ltrim($p, '/') === $normalizedRequested;
        });

        if (!$isAllowed) abort(404);

        $disk = Storage::disk('mlnas');
        if (!$disk->exists($normalizedRequested)) abort(404);

        $stream = $disk->readStream($normalizedRequested);
        if ($stream === false) abort(404);

        $mime = $disk->mimeType($normalizedRequested) ?: 'application/octet-stream';
        $downloadName = basename($normalizedRequested);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) fclose($stream);
        }, 200, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $downloadName . '"',
        ]);
    }

    public function serveApplicantFileAdmin(Request $request, int $requireId, string $type)
    {
        // Only allow authenticated Filament admin users
        if (! Filament::auth()->check()) {
            abort(401);
        }

        $applicant = Applicant::where('requireid', $requireId)->firstOrFail();

        if ($type === 'cv') {
            $path = $applicant->cvpath;
        } elseif ($type === 'photo') {
            $path = $applicant->photopath;
        } else {
            abort(404);
        }

        if (empty($path)) {
            abort(404);
        }

        $disk = Storage::disk('mlnas');
        if (! $disk->exists($path)) {
            abort(404);
        }

        $stream = $disk->readStream($path);
        if ($stream === false) {
            abort(404);
        }

        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $downloadName = basename($path);

        return response()->stream(function () use ($stream) {
            fpassthru($stream);
            if (is_resource($stream)) {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mime,
            // CV should download, photo should open inline in a new tab
            'Content-Disposition' => ($type === 'cv' ? 'attachment' : 'inline') . '; filename="' . $downloadName . '"',
        ]);
    }

    public function serveApplyJobFile(Request $request)
    {
        // Only allow authenticated Filament admin users
        if (! Filament::auth()->check()) {
            abort(401);
        }

        $path = $request->query('path');
        
        if (empty($path)) {
            abort(404, 'File path is empty');
        }

        $disk = Storage::disk('mlnas');
        
        // Log untuk debugging
        \Log::info('Trying to serve apply job file', [
            'path' => $path,
            'exists' => $disk->exists($path)
        ]);
        
        if (! $disk->exists($path)) {
            abort(404, 'File not found: ' . $path);
        }

        try {
            $stream = $disk->readStream($path);
            if ($stream === false) {
                abort(404, 'Cannot read file stream');
            }

            // Get mime type
            $extension = pathinfo($path, PATHINFO_EXTENSION);
            $mimeTypes = [
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'jpeg' => 'image/jpeg',
                'png' => 'image/png',
                'gif' => 'image/gif',
            ];
            $mime = $mimeTypes[strtolower($extension)] ?? 'application/octet-stream';
            
            $downloadName = basename($path);

            return response()->stream(function () use ($stream) {
                fpassthru($stream);
                if (is_resource($stream)) {
                    fclose($stream);
                }
            }, 200, [
                'Content-Type' => $mime,
                'Content-Disposition' => 'attachment; filename="' . $downloadName . '"',
            ]);
        } catch (\Exception $e) {
            \Log::error('Error serving apply job file', [
                'path' => $path,
                'error' => $e->getMessage()
            ]);
            abort(500, 'Error serving file: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        $applicant = Applicant::where('requireid', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
            
        return view('applicant.show', compact('applicant', 'educations', 'workExperiences', 'trainings'));
    }

    public function printView($id)
    {
        $user = auth()->user();
        $applicant = Applicant::where('requireid', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
            
        return view('applicant.pdf_complete', compact('applicant', 'educations', 'workExperiences', 'trainings'));
    }

    public function generatePDF($id)
    {
        $user = auth()->user();
        $applicant = Applicant::where('requireid', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
        
        $pdf = app('dompdf.wrapper')->loadView('applicant.pdf_complete', compact('applicant', 'educations', 'workExperiences', 'trainings'));
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Formulir_Lamaran_' . $applicant->firstname . '_' . $applicant->lastname . '.pdf';
        
        return $pdf->download($filename);
    }

    // Admin methods for viewing applicant data
    public function showAdmin($id)
    {
        // Only allow authenticated Filament admin users
        if (! Filament::auth()->check()) {
            abort(401);
        }

        $applicant = Applicant::where('requireid', $id)->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
            
        return view('applicant.show', compact('applicant', 'educations', 'workExperiences', 'trainings'));
    }

    public function printViewAdmin($id)
    {
        // Only allow authenticated Filament admin users
        if (! Filament::auth()->check()) {
            abort(401);
        }

        $applicant = Applicant::where('requireid', $id)->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
            
        return view('applicant.pdf_complete', compact('applicant', 'educations', 'workExperiences', 'trainings'));
    }

    public function generatePDFAdmin($id)
    {
        // Only allow authenticated Filament admin users
        if (! Filament::auth()->check()) {
            abort(401);
        }

        $applicant = Applicant::where('requireid', $id)->firstOrFail();
        
        // Get existing data from database
        $educations = $applicant->educations;
        $workExperiences = $applicant->workExperiences;
        $trainings = $applicant->trainings;
        
        $pdf = app('dompdf.wrapper')->loadView('applicant.pdf_complete', compact('applicant', 'educations', 'workExperiences', 'trainings'));
        $pdf->setPaper('A4', 'portrait');
        
        $filename = 'Formulir_Lamaran_' . $applicant->firstname . '_' . $applicant->lastname . '.pdf';
        
        return $pdf->download($filename);
    }
}