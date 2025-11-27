@extends('layouts.app')

@section('title', 'Print Formulir Lamaran')

@section('styles')
<style>
    @media print {
        .no-print { display: none !important; }
        body { margin: 0; }
        .container-fluid { padding: 0; }
    }
    
    .form-container {
        font-family: Arial, sans-serif;
        font-size: 12px;
        line-height: 1.3;
        max-width: 210mm;
        margin: 0 auto;
        padding: 20px;
    }
    
    .header {
        text-align: right;
        margin-bottom: 20px;
        font-size: 10px;
    }
    
    .title {
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        margin: 20px 0;
    }
    
    .section {
        margin: 15px 0;
    }
    
    .section-title {
        font-weight: bold;
        margin-bottom: 10px;
        border-bottom: 2px solid #000;
        padding-bottom: 3px;
        font-size: 14px;
    }
    
    table {
        width: 100%;
        border-collapse: collapse;
        margin: 8px 0;
    }
    
    td, th {
        border: 1px solid #000;
        padding: 5px;
        vertical-align: top;
        font-size: 11px;
    }
    
    .no-border {
        border: none !important;
    }
    
    .photo-box {
        width: 120px;
        height: 140px;
        border: 2px solid #000;
        text-align: center;
        vertical-align: middle;
        font-size: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .checkbox {
        display: inline-block;
        width: 15px;
        height: 15px;
        border: 1px solid #000;
        margin-right: 8px;
        text-align: center;
        line-height: 13px;
        font-weight: bold;
    }
    
    .print-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 1000;
    }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <button onclick="window.print()" class="btn btn-primary print-btn no-print">
        <i class="fas fa-print"></i> Print
    </button>
    
    <div class="form-container">
        <div class="header">
            Formulir : F-HO&PROJECT/GHL/HRD-02 Rev.01
        </div>

        <div class="title">
            FORMULIR LAMARAN PEKERJAAN<br>
            (Application For Employment)
        </div>

        <div class="section">
            <strong>Jabatan Yang Dilamar (Type of position desired) :</strong> 
            <span style="border-bottom: 1px solid #000; display: inline-block; min-width: 300px; margin-left: 10px;">
                {{ $jobTitle ?? '' }}
            </span>
        </div>

        <!-- SECTION I: DATA PRIBADI -->
        <div class="section">
            <div class="section-title">I. DATA PRIBADI (Personal Data)</div>
            
            <table style="border: none;">
                <tr>
                    <td style="width: 70%; border: none;">
                        <table>
                            <tr>
                                <td style="width: 200px; font-weight: bold;">NAMA (Name)</td>
                                <td>: {{ $applicant->firstname }} {{ $applicant->middlename }} {{ $applicant->lastname }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">ALAMAT SESUAI KTP<br><small>(Permanent Address)</small></td>
                                <td>: {{ $applicant->address ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">ALAMAT DOMISILI<br><small>(Correspondence Address)</small></td>
                                <td>: {{ $applicant->city ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">ALAMAT EMAIL<br><small>(Email Address)</small></td>
                                <td>: {{ $applicant->gmail ?? '' }}</td>
                            </tr>
                            <tr><td colspan="2" style="height: 10px;"></td></tr>
                            <tr>
                                <td style="font-weight: bold;">Facebook</td>
                                <td>: </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Linkedin</td>
                                <td>: {{ $applicant->linkedin ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Twitter</td>
                                <td>: </td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Instagram</td>
                                <td>: {{ $applicant->instagram ?? '' }}</td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Telepon (Telephone)</td>
                                <td>: {{ $applicant->phone ?? '' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 30%; text-align: center; border: none;">
                        <div class="photo-box">
                            Pas photo<br>
                            (Photograph)
                        </div>
                    </td>
                </tr>
            </table>

            <table style="margin-top: 15px;">
                <tr>
                    <td style="width: 200px;">
                        <span class="checkbox">{{ $applicant->gender == 'Male' ? '✓' : '' }}</span> Pria (Male)<br>
                        <span class="checkbox">{{ $applicant->gender == 'Female' ? '✓' : '' }}</span> Wanita (Female)
                    </td>
                    <td><strong>Tinggi</strong><br>(Height)</td>
                    <td><strong>Berat</strong><br>(Weight)</td>
                    <td><strong>Agama</strong><br>(Religion)</td>
                    <td><strong>Kebangsaan</strong><br>(Nationality)</td>
                    <td><strong>No.KTP</strong><br>(ID No)</td>
                    <td><strong>No.SIM</strong><br>(Driving Licence)</td>
                </tr>
                <tr>
                    <td></td>
                    <td>{{ $applicant->height ?? '' }}</td>
                    <td>{{ $applicant->weight ?? '' }}</td>
                    <td>{{ $applicant->religion ?? '' }}</td>
                    <td>{{ $applicant->nationality ?? '' }}</td>
                    <td>{{ $applicant->idnumber ?? '' }}</td>
                    <td>{{ $applicant->drivinglicense ?? '' }}</td>
                </tr>
            </table>

            <table style="margin-top: 10px;">
                <tr>
                    <td style="width: 250px; font-weight: bold;">Tempat & Tanggal Lahir<br>(Place & Date of Birth)</td>
                    <td>: {{ $applicant->dateofbirth ? $applicant->dateofbirth->format('d F Y') : '' }}</td>
                </tr>
            </table>

            <table style="margin-top: 10px;">
                <tr>
                    <td rowspan="2" style="width: 180px; font-weight: bold;">Status Perkawinan<br>(Marital Status)</td>
                    <td>
                        <span class="checkbox">{{ $applicant->marital_status === 'Belum Kawin' ? '✓' : '' }}</span>
                        Belum kawin (Single)
                    </td>
                    <td>
                        <span class="checkbox">{{ $applicant->marital_status === 'Janda/Duda' ? '✓' : '' }}</span>
                        Janda / Duda (Widow / Widower)
                    </td>
                </tr>
                <tr>
                    <td>
                        <span class="checkbox">{{ $applicant->marital_status === 'Kawin' ? '✓' : '' }}</span>
                        Kawin (Married)
                    </td>
                    <td>
                        <span class="checkbox">{{ $applicant->marital_status === 'Cerai' ? '✓' : '' }}</span>
                        Cerai (Divorced)
                    </td>
                </tr>
            </table>

            <table style="margin-top: 10px;">
                <tr>
                    <td rowspan="2" style="width: 180px; font-weight: bold;">Rumah Tinggal<br>(House)</td>
                    <td><span class="checkbox"></span> Milik sendiri (Private)</td>
                    <td><span class="checkbox"></span> Sewa (Rented)</td>
                </tr>
                <tr>
                    <td><span class="checkbox"></span> Milik keluarga (Family)</td>
                    <td><span class="checkbox"></span> Kost (Boarded)</td>
                </tr>
            </table>
        </div>

        <!-- SECTION III: RIWAYAT PENDIDIKAN -->
        <div class="section" style="page-break-before: always;">
            <div class="section-title">III. RIWAYAT PENDIDIKAN (Formal & Informal Education Record)</div>
            
            <table>
                <tr style="background-color: #f0f0f0;">
                    <th><strong>TINGKAT</strong><br>(Level)</th>
                    <th><strong>NAMA SEKOLAH</strong><br>(Name of School)</th>
                    <th><strong>TEMPAT</strong><br>(Place)</th>
                    <th><strong>JURUSAN</strong><br>(Major)</th>
                    <th><strong>DARI</strong><br>(From)</th>
                    <th><strong>SAMPAI</strong><br>(To)</th>
                    <th><strong>GELAR</strong><br>(Academic Title)</th>
                </tr>
                @foreach($educations as $education)
                <tr>
                    <td>{{ $education->education->educationname ?? '' }}</td>
                    <td>{{ $education->institutionname ?? '' }}</td>
                    <td></td>
                    <td>{{ $education->major ?? '' }}</td>
                    <td>{{ $education->startdate ? \Carbon\Carbon::parse($education->startdate)->format('Y') : '' }}</td>
                    <td>{{ $education->enddate ? \Carbon\Carbon::parse($education->enddate)->format('Y') : '' }}</td>
                    <td></td>
                </tr>
                @endforeach
                @for($i = count($educations); $i < 6; $i++)
                <tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endfor
            </table>

            @if(count($trainings) > 0)
            <div style="margin-top: 20px;">
                <strong>KURSUS / TRAINING (Courses)</strong>
                <table style="margin-top: 5px;">
                    <tr style="background-color: #f0f0f0;">
                        <th><strong>JENIS</strong><br>(Subject)</th>
                        <th><strong>PENYELENGGARA</strong><br>(Executor)</th>
                        <th><strong>TEMPAT</strong><br>(Place)</th>
                        <th><strong>DARI</strong><br>(From)</th>
                        <th><strong>SAMPAI</strong><br>(To)</th>
                        <th><strong>DIBIAYAI OLEH</strong><br>(Supported by)</th>
                    </tr>
                    @foreach($trainings as $training)
                    <tr>
                        <td>{{ $training->trainingname ?? '' }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $training->starttrainingdate ? \Carbon\Carbon::parse($training->starttrainingdate)->format('Y') : '' }}</td>
                        <td>{{ $training->endtrainingdate ? \Carbon\Carbon::parse($training->endtrainingdate)->format('Y') : '' }}</td>
                        <td></td>
                    </tr>
                    @endforeach
                </table>
            </div>
            @endif
        </div>

        <!-- SECTION IX: RIWAYAT PEKERJAAN -->
        @if(count($workExperiences) > 0)
        <div class="section">
            <div class="section-title">IX. RIWAYAT PEKERJAAN (Employment History)</div>
            <p style="font-size: 10px; font-style: italic;">dimulai dari pekerjaan terakhir / start from the latest job</p>
            
            @foreach($workExperiences->take(3) as $index => $work)
            <div style="margin: 15px 0;">
                <strong>{{ $index + 1 }}.</strong>
                <table>
                    <tr style="background-color: #f0f0f0;">
                        <th><strong>NAMA PERUSAHAAN</strong><br>(Company)</th>
                        <th><strong>JABATAN</strong><br>(Position)</th>
                        <th><strong>JML.KARY</strong><br>(Total Employees)</th>
                        <th><strong>NAMA ATASAN</strong><br>(Name of Superior)</th>
                        <th><strong>DARI</strong><br>(From)</th>
                        <th><strong>SAMPAI</strong><br>(To)</th>
                        <th><strong>GAJI</strong><br>(Salary)</th>
                    </tr>
                    <tr>
                        <td>{{ $work->companyname ?? '' }}</td>
                        <td>{{ $work->joblevel ?? '' }}</td>
                        <td></td>
                        <td></td>
                        <td>{{ $work->startdate ? \Carbon\Carbon::parse($work->startdate)->format('m/Y') : '' }}</td>
                        <td>{{ $work->enddate ? \Carbon\Carbon::parse($work->enddate)->format('m/Y') : ($work->iscurrent ? 'Sekarang' : '') }}</td>
                        <td>{{ $work->salary ? 'Rp ' . number_format($work->salary, 0, ',', '.') : '' }}</td>
                    </tr>
                </table>
                <div style="margin-top: 8px; border: 1px solid #000; padding: 5px; min-height: 40px;">
                    <strong>ALASAN KELUAR (Reason for Leaving):</strong><br>
                    {{ $work->eexp_comments ?? '' }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <div style="margin-top: 40px; page-break-inside: avoid;">
            <p style="text-align: justify; font-size: 11px;">
                Dengan ini saya menyatakan bahwa informasi yang saya berikan adalah benar. Apabila terdapat informasi yang tidak benar maka saya bersedia mempertanggungjawabkan segala akibatnya.
            </p>
            <p style="text-align: justify; font-size: 11px;">
                Saya memahami bahwa keputusan diterima atau tidaknya lamaran saya tergantung pada proses seleksi berikutnya.
            </p>
            
            <div style="margin-top: 30px; display: flex; justify-content: space-between;">
                <div style="text-align: center;">
                    <p>Jakarta, {{ date('d F Y') }}</p>
                    <br><br><br>
                    <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 200px;">
                        {{ $applicant->firstname }} {{ $applicant->lastname }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
