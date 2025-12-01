<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow">
    <title>Formulir Lamaran Pekerjaan - {{ $applicant->firstname }} {{ $applicant->lastname }}</title>
    <style>
        
        body {
            /* DejaVu Sans is bundled with DomPDF and supports wider Unicode, preventing '?' boxes */
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9px;
            line-height: 1.2;
            margin: 0;
            padding: 10px;
        }

        /* Make DomPDF and browser print use the same page size & margins */
        @page {
            margin: 0.5in 0.5in 0.2in 0.5in;
            size: A4;
        }
        
        .header {
            text-align: right;
            margin-bottom: 10px;
            font-size: 8px;
        }
        
        .title {
            text-align: center;
            font-size: 12px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .section {
            margin: 8px 0;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 5px;
            font-size: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 3px 0;
        }
        
        td, th {
            border: 1px solid #000;
            padding: 2px;
            vertical-align: top;
            font-size: 8px;
            word-wrap: break-word;
            word-break: break-word;
        }
        
        .no-border {
            border: none !important;
        }
        
        .photo-box {
            width: 100px;
            height: 130px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: top;
            font-size: 7px;
            margin-top: -40px;
        }
        
        .checkbox {
            display: inline-block;
            width: 10px;
            height: 10px;
            border: 1px solid #000;
            margin-right: 3px;
            text-align: center;
            line-height: 8px;
            font-size: 7px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .form-row {
            margin: 3px 0;
        }
        
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 150px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .bold {
            font-weight: bold;
        }
        
        /* Hide URL when printing in browser */
        @media print {
            
            /* Hide browser URL and other print elements */
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            
            /* Hide any potential URL display */
            a[href]:after {
                content: none !important;
            }
            
            /* Hide browser generated content */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            
            /* Remove any browser default headers/footers */
            html, body {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
    <script>
        // Auto-trigger print dialog when page loads
        window.onload = function() {
            // Add a small delay to ensure page is fully loaded
            setTimeout(function() {
                window.print();
            }, 500);
        };
        
        // Hide URL in print by modifying print behavior
        window.addEventListener('beforeprint', function() {
            document.title = '';
        });
    </script>
</head>
<body>
    <!-- Header -->
    <div class="header">
     
    </div>

    <!-- Title -->
    <div class="title">
        FORMULIR LAMARAN PEKERJAAN<br>
        <span style="font-size: 10px;">(Application For Employment)</span>
    </div>

    <!-- Jabatan -->
    <div class="form-row">
        <strong>Jabatan Yang Dilamar (Type of position desired) :</strong> 
        <span class="underline">{{ $jobTitle ?? '' }}</span>
    </div>

    <!-- SECTION I: DATA PRIBADI -->
    <div class="section">
        <div class="section-title">I. DATA PRIBADI (Personal Data)</div>
        
        <table style="border: none;">
            <tr>
                <td style="width: 70%; border: none;">
                    <div class="form-row">
                        <strong>NAMA (Name) :</strong> {{ $applicant->firstname }} {{ $applicant->middlename }} {{ $applicant->lastname }}
                    </div>
                    <div class="form-row">
                        <strong>ALAMAT SESUAI KTP<br><small>(Permanent Address)</small> :</strong> {{ $applicant->address ?? '' }}
                    </div>
                    <div class="form-row">
                        <strong>ALAMAT DOMISILI<br><small>(Correspondence Address)</small> :</strong> {{ $applicant->city ?? '' }}
                    </div>
                    <div class="form-row">
                        <strong>ALAMAT EMAIL (Email Address) :</strong> {{ $applicant->gmail ?? '' }}
                    </div>
                    <br>
                    <div class="form-row"><strong>Linkedin :</strong> {{ $applicant->linkedin ?? '' }}</div>
                    <div class="form-row"><strong>Instagram :</strong> {{ $applicant->instagram ?? '' }}</div>
                    <div class="form-row"><strong>Telepon (Telephone) :</strong> {{ $applicant->phone ?? '' }}</div>
                </td>
                <td style="width: 30%; text-align: center; border: none;">
                    <div class="photo-box">
                        @if($applicant->photopath)
                            @php
                                $fileFound = false;
                                $debugInfo = '';
                                $imageSrc = '';
                                
                                // Try to get file from the new 'career' disk first, then fallback to legacy 'mlnas'
                                try {
                                    $disk = null;
                                    foreach (['career', 'mlnas'] as $diskName) {
                                        try {
                                            $candidate = \Storage::disk($diskName);
                                            if ($candidate->exists($applicant->photopath)) {
                                                $disk = $candidate;
                                                break;
                                            }
                                        } catch (\Exception $e) {
                                            continue;
                                        }
                                    }

                                    if (! $disk) {
                                        throw new \Exception('Photo not found on any disk');
                                    }
                                    $photoPath = $applicant->photopath;
                                    
                                    // Try different path variations
                                    $pathVariations = [
                                        $photoPath,
                                        'applicants/photos/' . basename($photoPath),
                                        basename($photoPath)
                                    ];
                                    
                                    foreach ($pathVariations as $path) {
                                        try {
                                            if ($disk->exists($path)) {
                                                $imageData = base64_encode($disk->get($path));
                                                $imageType = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                                                // Handle different image types
                                                if ($imageType === 'jpg') $imageType = 'jpeg';
                                                $imageSrc = 'data:image/' . $imageType . ';base64,' . $imageData;
                                                $fileFound = true;
                                                $debugInfo = 'Found: ' . $path;
                                                break;
                                            }
                                        } catch (\Exception $e) {
                                            continue;
                                        }
                                    }
                                    
                                    if (!$fileFound) {
                                        $debugInfo = 'Not found in paths: ' . implode(', ', $pathVariations);
                                    }
                                    
                                } catch (\Exception $e) {
                                    $debugInfo = 'FTP Error: ' . $e->getMessage();
                                }
                            @endphp
                            
                            @if(isset($fileFound) && $fileFound)
                                <img src="{{ $imageSrc }}" 
                                     style="width: 98px; height: 128px; object-fit: cover; border: none;" 
                                     alt="Pas Photo">
                            @else
                                Pas photo<br>
                                (Photograph)<br>
                                <small style="color: red; font-size: 6px;">{{ $debugInfo ?? 'File not found' }}</small>
                            @endif
                        @else
                            Pas photo<br>   
                            (Photograph)
                        @endif
                    </div>
                </td>
            </tr>
        </table>

        <table>
            <tr>
                <td style="width: 16%;">
                    <span class="checkbox">{{ $applicant->gender == 'Male' ? '✓' : '' }}</span> Pria (Male)<br>
                    <span class="checkbox">{{ $applicant->gender == 'Female' ? '✓' : '' }}</span> Wanita (Female)
                </td>
                <td style="width: 12%;"><strong>Tinggi</strong><br>(Height)</td>
                <td style="width: 12%;"><strong>Berat</strong><br>(Weight)</td>
                <td style="width: 14%;"><strong>Agama</strong><br>(Religion)</td>
                <td style="width: 18%;"><strong>Kebangsaan</strong><br>(Nationality)</td>
                <td style="width: 14%;"><strong>No.KTP</strong><br>(ID No)</td>
                <td style="width: 14%;"><strong>No.SIM</strong><br>(Driving Licence)</td>
            </tr>
            <tr style="height: 35px;">
                <td></td>
                <td>{{ $applicant->height ?? '' }}</td>
                <td>{{ $applicant->weight ?? '' }}</td>
                <td>{{ $applicant->religion ?? '' }}</td>
                <td>{{ $applicant->nationality ?? '' }}</td>
                <td>{{ $applicant->idnumber ?? '' }}</td>
                <td>{{ $applicant->drivinglicense ?? '' }}</td>
            </tr>
        </table>

        <div class="form-row">
            <strong>Tempat & Tanggal Lahir (Place & Date of Birth) :</strong> 
            {{ $applicant->dateofbirth ? $applicant->dateofbirth->format('d F Y') : '' }}
        </div>

        <table>
            <tr>
                <td rowspan="2" style="width: 120px;"><strong>Status Perkawinan<br>(Marital Status)</strong></td>
                <td><span class="checkbox">{{ $applicant->marital_status === 'Belum Kawin' ? '✓' : '' }}</span> Belum kawin (Single)</td>
                <td><span class="checkbox">{{ $applicant->marital_status === 'Janda/Duda' ? '✓' : '' }}</span> Janda / Duda (Widow / Widower)</td>
            </tr>
            <tr>
                <td><span class="checkbox">{{ $applicant->marital_status === 'Kawin' ? '✓' : '' }}</span> Kawin (Married)</td>
                <td><span class="checkbox">{{ $applicant->marital_status === 'Cerai' ? '✓' : '' }}</span> Cerai (Divorced)</td>
            </tr>
        </table>

        <table>
            <tr>
                <td rowspan="2" style="width: 120px;"><strong>Rumah Tinggal<br>(House)</strong></td>
                <td><span class="checkbox"></span> Milik sendiri (Private)</td>
                <td><span class="checkbox"></span> Sewa (Rented)</td>
            </tr>
            <tr>
                <td><span class="checkbox"></span> Milik keluarga (Family)</td>
                <td><span class="checkbox"></span> Kost (Boarded)</td>
            </tr>
        </table>

        <table>
            <tr>
                <td rowspan="3" style="width: 80px;"><strong>Kendaraan<br>(Vehicles)</strong></td>
                <td><strong>Merk (Brand) :</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Tahun (Year) :</strong></td>
                <td></td>
            </tr>
            <tr>
                <td>
                    <span class="checkbox"></span> Milik sendiri (Private)<br>
                    <span class="checkbox"></span> Milik keluarga (Family)
                </td>
                <td>
                    <span class="checkbox"></span> Milik kantor (office)<br>
                    <span class="checkbox"></span> Lain-lain (others)
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION II: SUSUNAN KELUARGA -->
    <div class="section">
        <div class="section-title">II. SUSUNAN KELUARGA (members of Family)</div>
        <p style="font-size: 8px; font-style: italic;">Mohon tuliskan anggota keluarga, termasuk diri Anda (Please write down your family members, including you)</p>
        
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 18%;"><strong>HUB.KELUARGA</strong><br>(Fam.relationship)</th>
                <th style="width: 20%;"><strong>NAMA</strong><br>(Name)</th>
                <th style="width: 8%;"><strong>L/P</strong><br>(M/F)</th>
                <th style="width: 10%;"><strong>UMUR</strong><br>(Age)</th>
                <th style="width: 15%;"><strong>PENDIDIKAN</strong><br>(Education)</th>
                <th style="width: 15%;"><strong>PEKERJAAN</strong><br>(Employment)</th>
                <th style="width: 14%;"><strong>KETERANGAN</strong><br>(Remarks)</th>
            </tr>
            <tr style="height: 35px;"><td>Ayah (Father)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Ibu (Mother)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Saudara 1 (1st child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Saudara 2 (2nd child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Saudara 3 (3rd child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Suami/istri (Spouse)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Anak 1 (1st child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr style="height: 35px;"><td>Anak 2 (2nd child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>
    <div class="header">Formulir : F-HO&PROJECT/HRD-02 Rev.05</div>

    <!-- SECTION III: RIWAYAT PENDIDIKAN -->
    <div class="section">
        <div class="section-title">III. RIWAYAT PENDIDIKAN (Formal & Informal Education Record)</div>
        
        @php
            // Ambil mapping education_id => nama level dari HRIS
            try {
                $hrisService = app(\App\Services\HrisApiService::class);
                $hrisEducations = $hrisService->getAllEducations() ?? [];
            } catch (\Throwable $e) {
                $hrisEducations = [];
            }
        @endphp

        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 15%;"><strong>TINGKAT</strong><br>(Level)</th>
                <th style="width: 25%;"><strong>NAMA SEKOLAH</strong><br>(Name of School)</th>
                <th style="width: 15%;"><strong>TEMPAT</strong><br>(Place)</th>
                <th style="width: 15%;"><strong>JURUSAN</strong><br>(Major)</th>
                <th style="width: 10%;"><strong>DARI</strong><br>(From)</th>
                <th style="width: 10%;"><strong>SAMPAI</strong><br>(To)</th>
                <th style="width: 10%;"><strong>GELAR</strong><br>(Academic Title)</th>
            </tr>
            @foreach($educations as $education)
            @php
                $eduId = $education->education_id ?? null;
                $eduName = ($eduId && isset($hrisEducations[$eduId])) ? $hrisEducations[$eduId] : '';
            @endphp
            <tr style="height: 30px;">
                <td>{{ $eduName }}</td>
                <td>{{ $education->institutionname ?? '' }}</td>
                <td></td>
                <td>{{ $education->major ?? '' }}</td>
                <td>{{ $education->startdate ? \Carbon\Carbon::parse($education->startdate)->format('Y') : '' }}</td>
                <td>{{ $education->enddate ? \Carbon\Carbon::parse($education->enddate)->format('Y') : '' }}</td>
                <td></td>
            </tr>
            @endforeach
            @for($i = count($educations); $i < 6; $i++)
            <tr style="height: 30px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            @endfor
        </table>

        <div style="margin-top: 15px;">
            <strong>KURSUS / TRAINING (Courses)</strong>
            <table style="margin-top: 5px;">
                <tr style="background-color: #f0f0f0;">
                    <th style="width: 20%;"><strong>JENIS</strong><br>(Subject)</th>
                    <th style="width: 20%;"><strong>PENYELENGGARA</strong><br>(Executor)</th>
                    <th style="width: 15%;"><strong>TEMPAT</strong><br>(Place)</th>
                    <th style="width: 12%;"><strong>DARI</strong><br>(From)</th>
                    <th style="width: 12%;"><strong>SAMPAI</strong><br>(To)</th>
                    <th style="width: 21%;"><strong>DIBIAYAI OLEH</strong><br>(Supported by)</th>
                </tr>
                @foreach($trainings as $training)
                <tr style="height: 30px;">
                    <td>{{ $training->trainingname ?? '' }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $training->starttrainingdate ? \Carbon\Carbon::parse($training->starttrainingdate)->format('Y') : '' }}</td>
                    <td>{{ $training->endtrainingdate ? \Carbon\Carbon::parse($training->endtrainingdate)->format('Y') : '' }}</td>
                    <td></td>
                </tr>
                @endforeach
                @for($i = count($trainings); $i < 5; $i++)
                <tr style="height: 30px;"><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endfor
            </table>
        </div>
    </div>

    <!-- SECTION IV: BAHASA ASING -->
    <div class="section">
        <div class="section-title">IV. BAHASA ASING LAINNYA – Isi dengan Baik / Cukup / Kurang</div>
        <p style="font-size: 8px; font-style: italic;">(Other Foreign Languages) – Filled with : Good / Average / Poor</p>
        
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 20%;"><strong>BAHASA</strong><br>(Language)</th>
                <th style="width: 20%;"><strong>MENDENGAR</strong><br>(Listening)</th>
                <th style="width: 20%;"><strong>MEMBACA</strong><br>(Reading)</th>
                <th style="width: 20%;"><strong>MENULIS</strong><br>(Writing)</th>
                <th style="width: 20%;"><strong>BERBICARA</strong><br>(Speaking)</th>
            </tr>
            @for($i = 0; $i < 4; $i++)
            <tr style="height: 30px;"><td></td><td></td><td></td><td></td><td></td></tr>
            @endfor
        </table>
    </div>

    <!-- SECTION V: KEGIATAN SOSIAL -->
    <div class="section">
        <div class="section-title">V. KEGIATAN SOSIAL (Social Activities)</div>
        
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 30%;"><strong>ORGANISASI</strong><br>(Organization)</th>
                <th style="width: 30%;"><strong>KEGIATAN</strong><br>(Activities)</th>
                <th style="width: 25%;"><strong>JABATAN</strong><br>(Position)</th>
                <th style="width: 15%;"><strong>TAHUN</strong><br>(Year)</th>
            </tr>
            @for($i = 0; $i < 4; $i++)
            <tr style="height: 30px;"><td></td><td></td><td></td><td></td></tr>
            @endfor
        </table>
    </div>

    <!-- SECTION VI: HOBBY -->
    <div class="section">
        <div class="section-title">VI. Hobby dan Kegiatan Lainnya (Hobbies and Other Activities)</div>
        <table>
            @for($i = 0; $i < 4; $i++)
            <tr><td style="height: 30px;"></td></tr>
            @endfor
        </table>
    </div>

    <!-- SECTION VII: KEGIATAN MEMBACA -->
    <div class="section">
        <div class="section-title">VII. KEGIATAN MEMBACA (Reading Activity)</div>
        <table>
            <tr>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr style="background-color: #f0f0f0;">
                            <th colspan="2"><strong>KEGIATAN MEMBACA</strong><br>(Reading Activity)</th>
                        </tr>
                        <tr style="height: 30px;"><td style="width: 20px;">( )</td><td>Banyak (A lot)</td></tr>
                        <tr style="height: 30px;"><td style="width: 20px;">( )</td><td>Cukup (Sufficient)</td></tr>
                        <tr style="height: 30px;"><td style="width: 20px;">( )</td><td>Kurang (A Little)</td></tr>
                    </table>
                </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tr style="background-color: #f0f0f0;">
                            <th><strong>TOPIK YANG DIBACA</strong><br>(Topics being read)</th>
                        </tr>
                        <tr style="height: 30px;"><td></td></tr>
                        <tr style="height: 30px;"><td></td></tr>
                        <tr style="height: 30px;"><td></td></tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION VIII: SURAT KABAR DAN MAJALAH -->
    <div class="section">
        <div class="section-title">VIII. SURAT KABAR DAN MAJALAH YANG DIBACA (Newspaper and Magazine being read)</div>
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 50%;"><strong>SURAT KABAR</strong><br>(Newspaper)</th>
                <th style="width: 50%;"><strong>MAJALAH</strong><br>(Magazine)</th>
            </tr>
            <tr style="height: 30px;"><td></td><td></td></tr>
            <tr style="height: 30px;"><td></td><td></td></tr>
            <tr style="height: 30px;"><td></td><td></td></tr>
        </table>
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>
    <div class="header">Formulir : F-HO&PROJECT/GHL/HRD-02 Rev.05</div>

    <!-- SECTION IX: RIWAYAT PEKERJAAN -->
    <div class="section">
        <div class="section-title">IX. RIWAYAT PEKERJAAN (Employment History)</div>
        <p style="font-size: 8px; font-style: italic;">dimulai dari pekerjaan terakhir / start from the latest job</p>
        
        @foreach($workExperiences->take(5) as $index => $work)
        <div style="margin: 10px 0;">
            <strong>{{ $index + 1 }}.</strong>
            <table>
                <tr style="background-color: #f0f0f0;">
                    <th style="width: 20%;"><strong>NAMA PERUSAHAAN</strong><br>(Company)</th>
                    <th style="width: 15%;"><strong>JABATAN</strong><br>(Position)</th>
                    <th style="width: 10%;"><strong>JML.KARY</strong><br>(Total Employees)</th>
                    <th style="width: 15%;"><strong>NAMA ATASAN</strong><br>(Name of Superior)</th>
                    <th style="width: 10%;"><strong>DARI</strong><br>(From)</th>
                    <th style="width: 10%;"><strong>SAMPAI</strong><br>(To)</th>
                    <th style="width: 20%;"><strong>GAJI</strong><br>(Salary)</th>
                </tr>
                <tr style="height: 35px;">
                    <td>{{ $work->companyname ?? '' }}</td>
                    <td>{{ $work->joblevel ?? '' }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $work->startdate ? \Carbon\Carbon::parse($work->startdate)->format('m/Y') : '' }}</td>
                    <td>{{ $work->enddate ? \Carbon\Carbon::parse($work->enddate)->format('m/Y') : ($work->iscurrent ? 'Sekarang' : '') }}</td>
                    <td>{{ $work->salary ? 'Rp ' . number_format($work->salary, 0, ',', '.') : '' }}</td>
                </tr>
            </table>
            <div style="margin-top: 5px; border: 1px solid #000; padding: 8px; min-height: 50px;">
                <strong>ALASAN KELUAR (Reason for Leaving):</strong><br>
                {{ $work->eexp_comments ?? '' }}
            </div>
            <div style="margin-top: 5px; border: 1px solid #000; padding: 8px; min-height: 50px;">
                <strong>TUGAS DAN TANGGUNG JAWAB PADA JABATAN TERAKHIR:</strong><br>
                {{ $work->jobdesk ?? '' }}
            </div>
        </div>
        @endforeach

        @for($i = count($workExperiences); $i < 5; $i++)
        <div style="margin: 10px 0;">
            <strong>{{ $i + 1 }}.</strong>
            <table>
                <tr style="background-color: #f0f0f0;">
                    <th style="width: 20%;"><strong>NAMA PERUSAHAAN</strong><br>(Company)</th>
                    <th style="width: 15%;"><strong>JABATAN</strong><br>(Position)</th>
                    <th style="width: 10%;"><strong>JML.KARY</strong><br>(Total Employees)</th>
                    <th style="width: 15%;"><strong>NAMA ATASAN</strong><br>(Name of Superior)</th>
                    <th style="width: 10%;"><strong>DARI</strong><br>(From)</th>
                    <th style="width: 10%;"><strong>SAMPAI</strong><br>(To)</th>
                    <th style="width: 20%;"><strong>GAJI</strong><br>(Salary)</th>
                </tr>
                <tr style="height: 35px;"><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            </table>
            <div style="margin-top: 5px; border: 1px solid #000; padding: 8px; min-height: 50px;">
                <strong>ALASAN KELUAR (Reason for Leaving):</strong><br>
            </div>
            <div style="margin-top: 5px; border: 1px solid #000; padding: 8px; min-height: 50px;">
                <strong>TUGAS DAN TANGGUNG JAWAB PADA JABATAN TERAKHIR:</strong><br>
            </div>
        </div>
        @endfor
    </div>

    <!-- Page Break -->
    <div class="page-break"></div>
    <div class="header">Formulir : F-HO&PROJECT/HRD-02 Rev.05</div>

    <!-- SECTION X: REFERENSI -->
    <div class="section">
        <div class="section-title">X. REFERENSI / References</div>
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 25%;"><strong>NAMA</strong><br>(Name)</th>
                <th style="width: 25%;"><strong>ALAMAT & TELEPON</strong><br>(Address & Telephone)</th>
                <th style="width: 25%;"><strong>PEKERJAAN</strong><br>(Occupation)</th>
                <th style="width: 25%;"><strong>HUBUNGAN</strong><br>(Relationship)</th>
            </tr>
            <tr style="height: 35px;">
                <td>{{ $applicant->ref1_name ?? '' }}</td>
                <td>{{ $applicant->ref1_address_phone ?? '' }}</td>
                <td>{{ $applicant->ref1_occupation ?? '' }}</td>
                <td>{{ $applicant->ref1_relationship ?? '' }}</td>
            </tr>
            <tr style="height: 35px;">
                <td>{{ $applicant->ref2_name ?? '' }}</td>
                <td>{{ $applicant->ref2_address_phone ?? '' }}</td>
                <td>{{ $applicant->ref2_occupation ?? '' }}</td>
                <td>{{ $applicant->ref2_relationship ?? '' }}</td>
            </tr>
            <tr style="height: 35px;">
                <td>{{ $applicant->ref3_name ?? '' }}</td>
                <td>{{ $applicant->ref3_address_phone ?? '' }}</td>
                <td>{{ $applicant->ref3_occupation ?? '' }}</td>
                <td>{{ $applicant->ref3_relationship ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- SECTION XI: ORANG YANG DIHUBUNGI DALAM KEADAAN DARURAT -->
    <div class="section">
        <div class="section-title">XI. ORANG YANG DIHUBUNGI DALAM KEADAAN DARURAT / Person to be contacted in emergency case</div>
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 25%;"><strong>NAMA</strong><br>(Name)</th>
                <th style="width: 25%;"><strong>ALAMAT</strong><br>(Address)</th>
                <th style="width: 25%;"><strong>TELEPON</strong><br>(Telephone)</th>
                <th style="width: 25%;"><strong>HUBUNGAN</strong><br>(Relationship)</th>
            </tr>
            <tr style="height: 35px;">
                <td>{{ $applicant->emergency1_name ?? '' }}</td>
                <td>{{ $applicant->emergency1_address ?? '' }}</td>
                <td>{{ $applicant->emergency1_phone ?? '' }}</td>
                <td>{{ $applicant->emergency1_relationship ?? '' }}</td>
            </tr>
            <tr style="height: 35px;">
                <td>{{ $applicant->emergency2_name ?? '' }}</td>
                <td>{{ $applicant->emergency2_address ?? '' }}</td>
                <td>{{ $applicant->emergency2_phone ?? '' }}</td>
                <td>{{ $applicant->emergency2_relationship ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- SECTION XII: TUGAS DAN TANGGUNG JAWAB -->
    

    <!-- SECTION XIII: STRUKTUR ORGANISASI -->
    <div class="section">
        <div class="section-title">XIII. STRUKTUR ORGANISASI DAN POSISI ANDA PADA PERUSAHAAN YANG TERAKHIR</div>
        <p style="font-size: 8px; font-style: italic;">(Your Organization Structure and your position in last company)</p>
        <table>
            <tr><td style="height: 120px; border: 1px solid #000; padding: 10px;"></td></tr>
        </table>
    </div>

    <div class="page-break"></div>
    <div class="header">Formulir : F-HO&PROJECT/HRD-02 Rev.05</div>

    <!-- SECTION XIV: PERTANYAAN -->
    <div class="section">
        <div class="section-title">XIV. PERTANYAAN (Questionnaire)</div>
        
        <table>
            <tr style="background-color: #f0f0f0;">
                <th style="width: 5%;"></th>
                <th style="width: 60%;"><strong>PERTANYAAN</strong></th>
                <th style="width: 10%;"><strong>Ya</strong><br>(Yes)</th>
                <th style="width: 10%;"><strong>Tidak</strong><br>(No)</th>
                <th style="width: 15%;"><strong>Penjelasan</strong><br>(Description)</th>
            </tr>
            <tr>
                <td>1</td>
                <td>Apakah anda pernah bekerja di group/perusahaan ini sebelumnya?<br><small>(Have you ever been employed by this group/company?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>2</td>
                <td>Apakah anda mempunyai teman/saudara yg bekerja di grup/perusahaan ini?<br><small>(Do you have any friend/family as employee in this group/company?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>3</td>
                <td>Selain di perusahaan ini, dimana lagi anda melamar? dan sebagai apa?<br><small>(Besides in this company, which company do you apply to and in what position?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>4</td>
                <td>Apakah anda terikat kontrak di perusahaan saat ini?<br><small>(Are you fastened in contract at your current company?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>5</td>
                <td>Apakah anda mempunyai pekerjaan sampingan?<br><small>(Do you have any other jobs/as a part timer?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>6</td>
                <td>Dapatkah kami meminta referensi dari perusahaan anda saat ini?<br><small>(Would you mind if we get reference from your current company?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>7</td>
                <td>Apakah anda pernah menderita sakit keras,kecelakaan/melakukan operasi?<br><small>(Have you ever suffered chronics disease,accident or had a surgery?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>8</td>
                <td>Apakah anda pernah menjalani pemeriksaan psikologis, dimana dan untuk keperluan apa?<br><small>(Have you ever got the psycological test, where and what for?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>9</td>
                <td>Apakah anda pernah berhubungan dengan kepolisian karena tindakan kejahatan?<br><small>(Have you ever had something to do with police in criminal cases?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>10</td>
                <td>Apakah anda bersedia ditugaskan keluar kota Jakarta?<br><small>(Are you willing to work out of Jakarta?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>11</td>
                <td>Apakah anda bersedia ditempatkan diluar kota Jakarta?<br><small>(Are you willing to be placed outside Jakarta?)</small></td>
                <td>{{ $applicant->q11_willing_outside_jakarta === true ? '✓' : '' }}</td>
                <td>{{ $applicant->q11_willing_outside_jakarta === false ? '✓' : '' }}</td>
                <td></td>
            </tr>
            <tr>
                <td>12</td>
                <td>Jenis pekerjaan apa yang anda tidak sukai?<br><small>(What kind of job that you dislike?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>13</td>
                <td>Jenis pekerjaan apa yang anda sukai?<br><small>(What kind of job that you like?)</small></td>
                <td></td><td></td><td></td>
            </tr>
            <tr>
                <td>14</td>
                <td>Berapa penghasilan & fasilitas apa yang anda terima saat ini?<br><small>(How much your current income and facilities that you accepted?)</small></td>
                <td></td><td></td><td>{{ $applicant->q14_current_income ?? '' }}</td>
            </tr>
            <tr>
                <td>15</td>
                <td>Berapa penghasilan & fasilitas apa yang anda harapkan?<br><small>(How much your expected income and facilities?)</small></td>
                <td></td><td></td><td>{{ $applicant->q15_expected_income ?? '' }}</td>
            </tr>
            <tr>
                <td>16</td>
                <td>Kapankah anda siap untuk bekerja di perusahaan ini?<br><small>(When will you be available for work in this company?)</small></td>
                <td></td><td></td><td>{{ $applicant->q16_available_from ?? '' }}</td>
            </tr>
        </table>
    </div>

    <!-- Signature Section -->
    <div style="margin-top: 30px;">
        <p style="text-align: justify; font-size: 8px;">
            Dengan ini saya menyatakan bahwa informasi yang saya berikan adalah benar. Apabila terdapat informasi yang tidak benar maka saya bersedia mempertanggungjawabkan segala akibatnya.
        </p>
        <p style="text-align: justify; font-size: 8px;">
            Saya memahami bahwa keputusan diterima atau tidaknya lamaran saya tergantung pada proses seleksi berikutnya.
        </p>
        <p style="font-size: 8px;">
            * Terkait dengan pelindungan data pribadi, saya dengan ini menyetujui untuk mengikuti ketentuan pengelolaan data pribadi yang berlaku di PT. Metropolitan Land, Tbk
        </p>
        
        <div style="margin-top: 20px; text-align: right;">
            <p>Jakarta, {{ date('d F Y') }}</p>
            <br><br><br>
            <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px;">
                {{ $applicant->firstname }} {{ $applicant->lastname }}
            </p>
        </div>
    </div>
</body>
</html>
