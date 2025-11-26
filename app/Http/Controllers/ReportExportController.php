<?php

namespace App\Http\Controllers;

use App\Models\JobVacancy;
use App\Models\ApplyJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportExportController extends Controller
{
    public function exportExcel()
    {
        try {
            $data = $this->getReportData();
            
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            
            // Set document properties
            $spreadsheet->getProperties()
                ->setCreator('Metland Recruitment System')
                ->setLastModifiedBy('Metland Recruitment System')
                ->setTitle('Recruitment Report')
                ->setSubject('Recruitment Report')
                ->setDescription('Report generated from Metland Recruitment System');
            
            // Header
            $sheet->setCellValue('A1', 'Job Vacancy');
            $sheet->setCellValue('B1', 'Level');
            $sheet->setCellValue('C1', 'Lokasi');
            $sheet->setCellValue('D1', 'Man Power Needed');
            $sheet->setCellValue('E1', 'Start Date');
            $sheet->setCellValue('F1', 'End Date');
            $sheet->setCellValue('G1', 'Total Applicants');
            $sheet->setCellValue('H1', 'Review Applicant');
            $sheet->setCellValue('I1', 'Interview User');
            $sheet->setCellValue('J1', 'Psiko Test');
            $sheet->setCellValue('K1', 'Offering Letter');
            $sheet->setCellValue('L1', 'MCU');
            $sheet->setCellValue('M1', 'Hired');
            $sheet->setCellValue('N1', 'Job Status');
            
            // Style header
            $sheet->getStyle('A1:N1')->getFont()->setBold(true);
            $sheet->getStyle('A1:N1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FF4CAF50');
            
            // Data
            $row = 2;
            foreach ($data as $item) {
                $sheet->setCellValue('A' . $row, $this->cleanUtf8($item['job_vacancy_name']));
                $sheet->setCellValue('B' . $row, $this->cleanUtf8($item['level']));
                $sheet->setCellValue('C' . $row, $this->cleanUtf8($item['location']));
                $sheet->setCellValue('D' . $row, $item['man_power']);
                $sheet->setCellValue('E' . $row, $item['start_date']);
                $sheet->setCellValue('F' . $row, $item['end_date']);
                $sheet->setCellValue('G' . $row, $item['total_applicants']);
                $sheet->setCellValue('H' . $row, $item['review_applicant']);
                $sheet->setCellValue('I' . $row, $item['interview_user']);
                $sheet->setCellValue('J' . $row, $item['psiko_test']);
                $sheet->setCellValue('K' . $row, $item['offering_letter']);
                $sheet->setCellValue('L' . $row, $item['mcu']);
                $sheet->setCellValue('M' . $row, $item['hired']);
                $sheet->setCellValue('N' . $row, $this->cleanUtf8($item['status']));
                $row++;
            }
            
            // Auto size columns
            foreach (range('A', 'N') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            
            $writer = new Xlsx($spreadsheet);
            $filename = 'recruitment-report-' . now()->format('Y-m-d') . '.xlsx';
            
            // Create a temporary file
            $tempFile = tempnam(sys_get_temp_dir(), 'recruitment_report_');
            $writer->save($tempFile);
            
            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
            
        } catch (\Exception $e) {
            Log::error('Excel export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating Excel file: ' . $e->getMessage());
        }
    }
    
    public function exportPdf()
    {
        try {
            $data = $this->getReportData();
            
            // Clean data for PDF
            foreach ($data as &$item) {
                $item['job_vacancy_name'] = $this->cleanUtf8($item['job_vacancy_name']);
                $item['level'] = $this->cleanUtf8($item['level']);
                $item['location'] = $this->cleanUtf8($item['location']);
                $item['status'] = $this->cleanUtf8($item['status']);
            }
            
            $pdf = Pdf::loadView('exports.recruitment-report-pdf', [
                'data' => $data,
                'generated_at' => now()->format('d/m/Y H:i:s')
            ]);
            
            $pdf->setPaper('a4', 'landscape');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true, 
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            $filename = 'recruitment-report-' . now()->format('Y-m-d') . '.pdf';
            
            return $pdf->download($filename);
            
        } catch (\Exception $e) {
            Log::error('PDF export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating PDF file: ' . $e->getMessage());
        }
    }
    
    public function exportApplicantsExcel($job_vacancy_id, $status_key)
    {
        try {
            $statusMap = [
                'all' => null,
                'review-applicant' => 1,
                'interview-user' => 2,
                'psiko' => 3,
                'offering' => 4,
                'hired' => 5,
                'mcu' => 6,
            ];

            $statusValue = $statusMap[$status_key] ?? null;

            $query = ApplyJob::query()
                ->join('require', 'apply_jobs.requireid', '=', 'require.requireid')
                ->where('apply_jobs.job_vacancy_id', $job_vacancy_id)
                ->select('apply_jobs.*', 'require.*');

            if ($statusValue !== null) {
                $query->where('apply_jobs.apply_jobs_status', $statusValue);
            }

            $rows = $query->get();

            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Header
            $sheet->setCellValue('A1', 'Nama Lengkap');
            $sheet->setCellValue('B1', 'Email');
            $sheet->setCellValue('C1', 'Phone');
            $sheet->setCellValue('D1', 'Status');
            $sheet->setCellValue('E1', 'Tanggal');

            $sheet->getStyle('A1:E1')->getFont()->setBold(true);

            $rowIndex = 2;
            foreach ($rows as $row) {
                $fullName = trim(($row->firstname ?? '') . ' ' . ($row->middlename ?? '') . ' ' . ($row->lastname ?? ''));

                // decrypt email & phone if possible
                $email = $row->gmail;
                $phone = $row->phone;
                try {
                    if ($email) {
                        $email = Crypt::decryptString($email);
                    }
                } catch (\Exception $e) {
                    // keep raw
                }
                try {
                    if ($phone) {
                        $phone = Crypt::decryptString($phone);
                    }
                } catch (\Exception $e) {
                    // keep raw
                }

                // map status text
                $statusText = match($row->apply_jobs_status) {
                    0 => 'Applied',
                    1 => 'Review Applicant',
                    2 => 'Interview User',
                    3 => 'Psiko Test',
                    4 => 'Offering Letter',
                    5 => 'Hired',
                    6 => 'MCU',
                    default => 'Unknown',
                };

                // date per status
                $dateValue = $row->apply_date ?? $row->created_at;
                if ($statusValue === 2) {
                    // interview
                    $dateValue = $row->apply_jobs_interview_date ?? $row->apply_jobs_interview_time ?? $dateValue;
                } elseif ($statusValue === 3) {
                    $dateValue = $row->updated_at ?? $dateValue;
                } elseif ($statusValue === 4) {
                    $dateValue = $row->updated_at ?? $dateValue;
                } elseif ($statusValue === 6) {
                    $dateValue = $row->updated_at ?? $dateValue;
                } elseif ($statusValue === 5) {
                    $dateValue = $row->updated_at ?? $dateValue;
                }

                $formattedDate = $dateValue ? Carbon::parse($dateValue)->format('d M Y H:i') : '';

                $sheet->setCellValue('A' . $rowIndex, $this->cleanUtf8($fullName));
                $sheet->setCellValue('B' . $rowIndex, $this->cleanUtf8($email));
                $sheet->setCellValue('C' . $rowIndex, $this->cleanUtf8($phone));
                $sheet->setCellValue('D' . $rowIndex, $this->cleanUtf8($statusText));
                $sheet->setCellValue('E' . $rowIndex, $formattedDate);

                $rowIndex++;
            }

            foreach (range('A', 'E') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }

            $writer = new Xlsx($spreadsheet);

            $filename = 'applicants-' . $status_key . '-job-' . $job_vacancy_id . '-' . now()->format('Y-m-d') . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), 'applicants_export_');
            $writer->save($tempFile);

            return response()->download($tempFile, $filename, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            Log::error('Applicants Excel export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error generating applicants Excel file: ' . $e->getMessage());
        }
    }
    
    private function getReportData()
    {
        $user = Auth::user();
        
        $query = JobVacancy::query();
        
        // Filter berdasarkan role dan lokasi
        if ($user && $user->role === 'admin_location' && $user->location_id) {
            $location = $user->location;
            if ($location && $location->hris_location_id) {
                $query->where('job_vacancy_hris_location_id', $location->hris_location_id);
            }
        }
        
        $jobVacancies = $query->orderBy('job_vacancy_start_date', 'desc')->get();
        
        $data = [];
        foreach ($jobVacancies as $job) {
            $totalApplicants = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->count();
            $reviewApplicant = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 1)->count();
            $interviewUser = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 2)->count();
            $psikoTest = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 3)->count();
            $offeringLetter = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 4)->count();
            $mcu = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 6)->count();
            $hired = ApplyJob::where('job_vacancy_id', $job->job_vacancy_id)->where('apply_jobs_status', 5)->count();
            
            $status = 'Active';
            if ($hired >= $job->job_vacancy_man_power && $job->job_vacancy_man_power > 0) {
                $status = 'Closed';
            } elseif (now()->gt($job->job_vacancy_end_date)) {
                $status = 'Closed';
            }
            
            $location = \App\Models\Location::where('hris_location_id', $job->job_vacancy_hris_location_id)->first();
            
            $data[] = [
                'job_vacancy_name' => $job->job_vacancy_name ?? '',
                'level' => $job->job_vacancy_level_name ?? '',
                'location' => $location ? $location->name : '-',
                'man_power' => $job->job_vacancy_man_power ?? 0,
                'start_date' => $job->job_vacancy_start_date ? $job->job_vacancy_start_date->format('d/m/Y') : '',
                'end_date' => $job->job_vacancy_end_date ? $job->job_vacancy_end_date->format('d/m/Y') : '',
                'total_applicants' => $totalApplicants,
                'review_applicant' => $reviewApplicant,
                'interview_user' => $interviewUser,
                'psiko_test' => $psikoTest,
                'offering_letter' => $offeringLetter,
                'mcu' => $mcu,
                'hired' => $hired,
                'status' => $status,
            ];
        }
        
        return $data;
    }
    
    private function cleanUtf8($string)
    {
        if (is_null($string)) {
            return '';
        }
        
        // Remove any non-UTF-8 characters
        $string = mb_convert_encoding($string, 'UTF-8', 'UTF-8');
        
        // Remove any remaining problematic characters
        $string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
        
        return $string;
    }
}
