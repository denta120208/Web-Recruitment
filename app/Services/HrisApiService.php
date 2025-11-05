<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HrisApiService
{
    protected $baseUrl;
    protected $timeout = 30;
    protected $apiKey;

    public function __construct()
    {
        // Base URL HRIS API
        $this->baseUrl = env('HRIS_API_URL', 'https://trialhris.metropolitanland.com/recruitment/api');
        $this->apiKey = env('HRIS_API_KEY', null);
    }

    /**
     * Get base URL (public for external access)
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }


    /**
     * Get headers for HRIS API request
     */
    public function getHeaders()
    {
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];

        // Add API key if configured
        if ($this->apiKey) {
            $headers['Authorization'] = 'Bearer ' . $this->apiKey;
            // Or use: $headers['X-API-Key'] = $this->apiKey;
        }

        return $headers;
    }

    /**
     * Get all locations from HRIS
     */
    public function getAllLocations()
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->get($this->baseUrl . '/locations');

            if ($response->successful()) {
                $data = $response->json();
                // If response is array of locations, return as [id => name] format
                if (is_array($data) && isset($data[0])) {
                    $locations = [];
                    foreach ($data as $location) {
                        if (isset($location['id']) && isset($location['name'])) {
                            $locations[$location['id']] = $location['name'];
                        }
                    }
                    return $locations;
                }
                // If response is object with data property
                if (isset($data['data']) && is_array($data['data'])) {
                    $locations = [];
                    foreach ($data['data'] as $location) {
                        if (isset($location['id']) && isset($location['name'])) {
                            $locations[$location['id']] = $location['name'];
                        }
                    }
                    return $locations;
                }
            }

            Log::warning('Failed to fetch locations from HRIS API', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Error fetching locations from HRIS: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Sync all locations from HRIS to local database
     */
    public function syncLocations()
    {
        try {
            $allLocations = $this->getAllLocations();
            
            if (!$allLocations || !is_array($allLocations)) {
                // If getAllLocations returns null, try to fetch individual locations
                // Or use static data as fallback
                return false;
            }

            $synced = 0;
            foreach ($allLocations as $locationId => $locationName) {
                \App\Models\Location::updateOrCreate(
                    ['hris_location_id' => $locationId],
                    ['name' => $locationName]
                );
                $synced++;
            }

            Log::info('Locations synced from HRIS', ['count' => $synced]);
            return true;
        } catch (\Exception $e) {
            Log::error('Error syncing locations from HRIS: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get location name from HRIS by location_id
     * First checks local database, then tries HRIS API, then uses static mapping
     */
    public function getLocationName($locationId)
    {
        if (!$locationId) {
            return null;
        }

        // First: Check local database (locations table)
        try {
            $localLocation = \App\Models\Location::getNameByHrisId($locationId);
            if ($localLocation) {
                return $localLocation;
            }
        } catch (\Exception $e) {
            // Table might not exist yet, continue to API
            Log::debug('Local location table not available yet', [
                'location_id' => $locationId
            ]);
        }

        // Second: Try to fetch from HRIS API
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout($this->timeout)
                ->get($this->baseUrl . '/location/' . $locationId);

            if ($response->successful()) {
                $data = $response->json();
                // Try different response formats
                $locationName = $data['name'] 
                    ?? $data['location_name'] 
                    ?? ($data['data']['name'] ?? null)
                    ?? ($data['data']['location_name'] ?? null);
                    
                if ($locationName) {
                    // Save to local database for future use
                    try {
                        \App\Models\Location::updateOrCreate(
                            ['hris_location_id' => $locationId],
                            ['name' => $locationName]
                        );
                    } catch (\Exception $e) {
                        // Table might not exist, ignore
                    }
                    return $locationName;
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to fetch location from HRIS API, trying fallback', [
                'location_id' => $locationId,
                'error' => $e->getMessage()
            ]);
        }

        // Third: Fallback to static mapping (from HRIS database - tabel location)
        // Mapping berdasarkan tabel location di HRIS dengan 28 rows
        $locationMap = [
            1 => 'Kantor Pusat',
            2 => 'Metland Transyogi',
            3 => 'Metland Cileungsi',
            4 => 'Metland Tambun',
            5 => 'Metland Cibitung',
            6 => 'Metland Menteng',
            7 => 'Metland Puri',
            8 => 'Metland Cyber Puri',
            9 => 'Mal Metropolitan Bekasi',
            10 => 'M Gold Tower',
            11 => 'Grand Metropolitan Mall',
            12 => 'Mal Metropolitan Cileungsi',
            13 => 'Kaliana Apartment',
            14 => 'Metland Hotel Cirebon',
            15 => 'Hotel Horison Ultima Bekasi',
            16 => 'Hotel Horison Ultima Seminyak',
            17 => 'Plaza Metropolitan',
            18 => 'Metland Hotel Bekasi',
            19 => 'Kantor Pusat - MT Haryono',
            20 => 'Kantor Pusat - Hotel Division',
            21 => 'Metland Smara Kertajati',
            22 => 'Metland Cikarang',
            23 => 'One District Puri',
            24 => 'Metland Venya Ubud',
            25 => 'Recreation & Sport Facility',
            26 => 'Koperasi Metland Maju Bersama',
            27 => 'Metland Kertajati',
            28 => 'DIUBUD',
            // Mapping sesuai dengan data aktual dari tabel location HRIS
        ];

        // Return mapped location if exists
        if (isset($locationMap[$locationId])) {
            return $locationMap[$locationId];
        }

        Log::warning('Location ID not found in any source', [
            'location_id' => $locationId
        ]);

        return null;
    }

    /**
     * Set New Candidate ke HRIS
     */
    public function setNewCandidate($data)
    {
        try {
            $payload = [
                'job_vacancy_id' => $data['job_vacancy_id'],
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => $data['apply_jobs_status_id'],
                'set_new_candidate_by' => $data['set_new_candidate_by'],
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setNewCandidate', $payload);

            Log::info('HRIS API - Set New Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set New Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set Candidate (Update Status - Except Hired)
     */
    public function setCandidate($data)
    {
        try {
            $payload = [
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => $data['apply_jobs_status_id'],
                'set_candidate_by' => $data['set_candidate_by'],
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setCandidate', $payload);

            Log::info('HRIS API - Set Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Set Candidate Hired (dengan data lengkap employee)
     */
    public function setCandidateHired($data)
    {
        try {   
            // Prepare payload - ensure null values are actually null, not empty strings
            $payload = [
                // Required fields dari recruitment
                'recruitment_candidate_id' => $data['recruitment_candidate_id'],
                'candidate_name' => $data['candidate_name'],
                'candidate_email' => $data['candidate_email'],
                'candidate_contact_number' => $data['candidate_contact_number'],
                'candidate_apply_date' => $data['candidate_apply_date'],
                'apply_jobs_status_id' => 5, // Hired
                'set_candidate_by' => $data['set_candidate_by'],
                
                // Data Employee - Required fields
                'joined_date' => $data['joined_date'],
                'emp_firstname' => $data['emp_firstname'],
                'emp_gender' => $data['emp_gender'],
                'emp_marital_status' => $data['emp_marital_status'],
                
                // Optional fields - convert empty strings to null
                'emp_middle_name' => !empty($data['emp_middle_name']) ? $data['emp_middle_name'] : null,
                'emp_lastname' => !empty($data['emp_lastname']) ? $data['emp_lastname'] : null,
                'emp_ktp' => !empty($data['emp_ktp']) ? $data['emp_ktp'] : null,
                'emp_dri_lice_num' => !empty($data['emp_dri_lice_num']) ? $data['emp_dri_lice_num'] : null,
                'emp_dri_lice_exp_date' => !empty($data['emp_dri_lice_exp_date']) ? $data['emp_dri_lice_exp_date'] : null,
                'emp_birthday' => !empty($data['emp_birthday']) ? $data['emp_birthday'] : null,
                'bpjs_ks' => !empty($data['bpjs_ks']) ? $data['bpjs_ks'] : null,
                'bpjs_tk' => !empty($data['bpjs_tk']) ? $data['bpjs_tk'] : null,
                'npwp' => !empty($data['npwp']) ? $data['npwp'] : null,
                // work_station removed - not supported by HRIS API
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/setCandidate', $payload);

            Log::info('HRIS API - Set Candidate Hired', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Set Candidate Hired: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reject Job Vacancy
     */
    public function rejectJobVacancy($jobVacancyId, $rejectedBy)
    {
        try {
            $payload = [
                'job_vacancy_id' => $jobVacancyId,
                'reject_by' => $rejectedBy,
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/rejectJobVacancy', $payload);

            Log::info('HRIS API - Reject Job Vacancy', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Reject Job Vacancy: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Reject Candidate
     */
    public function rejectCandidate($recruitmentCandidateId, $rejectedBy)
    {
        try {
            $payload = [
                'recruitment_candidate_id' => $recruitmentCandidateId,
                'reject_candidate_by' => $rejectedBy,
            ];

            $response = Http::timeout($this->timeout)
                ->withHeaders($this->getHeaders())
                ->post($this->baseUrl . '/rejectCandidate', $payload);

            Log::info('HRIS API - Reject Candidate', [
                'payload' => $payload,
                'response' => $response->json(),
                'status' => $response->status()
            ]);

            return [
                'success' => $response->successful(),
                'data' => $response->json(),
                'status' => $response->status()
            ];

        } catch (\Exception $e) {
            Log::error('HRIS API Error - Reject Candidate: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
