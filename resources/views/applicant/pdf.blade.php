<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Formulir Lamaran Pekerjaan - {{ $applicant->firstname }} {{ $applicant->lastname }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            line-height: 1.2;
            margin: 0;
            padding: 15px;
        }
        
        .header {
            text-align: right;
            margin-bottom: 15px;
            font-size: 9px;
        }
        
        .title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin: 15px 0;
        }
        
        .section {
            margin: 10px 0;
        }
        
        .section-title {
            font-weight: bold;
            margin-bottom: 8px;
            border-bottom: 1px solid #000;
            padding-bottom: 2px;
            font-size: 11px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }
        
        td, th {
            border: 1px solid #000;
            padding: 3px;
            vertical-align: top;
            font-size: 9px;
        }
        
        .no-border {
            border: none !important;
        }
        
        .photo-box {
            width: 100px;
            height: 120px;
            border: 2px solid #000;
            text-align: center;
            vertical-align: middle;
            font-size: 8px;
            display: table-cell;
        }
        
        .checkbox {
            display: inline-block;
            width: 12px;
            height: 12px;
            border: 1px solid #000;
            margin-right: 5px;
            text-align: center;
            line-height: 10px;
            font-weight: bold;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .small-text {
            font-size: 8px;
        }
        
        .form-row {
            margin: 5px 0;
        }
        
        .underline {
            border-bottom: 1px solid #000;
            display: inline-block;
            min-width: 200px;
        }
    </style>
</head>
<body>
    <div class="header">
        Formulir : F-HO&PROJECT/GHL/HRD-02 Rev.01
    </div>

    <div class="title">
        FORMULIR LAMARAN PEKERJAAN<br>
        (Application For Employment)
    </div>

    <div class="section">
        Jabatan Yang Dilamar (Type of position desired) : <span class="fill-line"></span>
    </div>

    <!-- SECTION I: DATA PRIBADI -->
    <div class="section">
        <div class="section-title">I. DATA PRIBADI (Personal Data)</div>
        
        <table>
            <tr>
                <td style="width: 70%;">
                    <table class="no-border">
                        <tr>
                            <td class="no-border" style="width: 200px;">NAMA (Name)</td>
                            <td class="no-border">: {{ $applicant->firstname }} {{ $applicant->middlename }} {{ $applicant->lastname }}</td>
                        </tr>
                        <tr>
                            <td class="no-border">ALAMAT SESUAI KTP</td>
                            <td class="no-border">: {{ $applicant->address ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="no-border">(Permanent Address)</td>
                            <td class="no-border"></td>
                        </tr>
                        <tr>
                            <td class="no-border">ALAMAT DOMISILI</td>
                            <td class="no-border">: {{ $applicant->city ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="no-border">(Correspondence Address)</td>
                            <td class="no-border"></td>
                        </tr>
                        <tr>
                            <td class="no-border">ALAMAT EMAIL (Email Address)</td>
                            <td class="no-border">: {{ $applicant->gmail ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="no-border"><br></td>
                            <td class="no-border"></td>
                        </tr>
                        <tr>
                            <td class="no-border">Facebook</td>
                            <td class="no-border">: </td>
                        </tr>
                        <tr>
                            <td class="no-border">Linkedin</td>
                            <td class="no-border">: {{ $applicant->linkedin ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="no-border">Twitter</td>
                            <td class="no-border">: </td>
                        </tr>
                        <tr>
                            <td class="no-border">Instagram</td>
                            <td class="no-border">: {{ $applicant->instagram ?? '' }}</td>
                        </tr>
                        <tr>
                            <td class="no-border">Telepon (Telephone)</td>
                            <td class="no-border">: {{ $applicant->phone ?? '' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 30%; text-align: center;">
                    <div class="photo-box">
                        Pas photo<br>
                        (Photograph)
                    </div>
                </td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td>
                    <span class="checkbox">{{ $applicant->gender == 'Male' ? '✓' : '' }}</span> Pria (Male)
                </td>
                <td>Tinggi (Height)</td>
                <td>Berat (Weight)</td>
                <td>Agama (Religion)</td>
                <td>Kebangsaan (Nationality)</td>
                <td>No.KTP (ID No)</td>
                <td>No.SIM (Driving Licence)</td>
            </tr>
            <tr>
                <td>
                    <span class="checkbox">{{ $applicant->gender == 'Female' ? '✓' : '' }}</span> Wanita (Female)
                </td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td colspan="2">Tempat & Tanggal Lahir (Place & Date of Birth)</td>
                <td>: {{ $applicant->dateofbirth ? $applicant->dateofbirth->format('d/m/Y') : '' }}</td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td rowspan="2" style="width: 150px;">Status Perkawinan<br>(Marital Status)</td>
                <td><span class="checkbox"></span> Belum kawin (Single)</td>
                <td><span class="checkbox"></span> Janda / Duda (Widow / Widower)</td>
            </tr>
            <tr>
                <td><span class="checkbox"></span> Kawin (Married)</td>
                <td><span class="checkbox"></span> Cerai (Divorced)</td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td rowspan="2" style="width: 150px;">Rumah Tinggal<br>(House)</td>
                <td><span class="checkbox"></span> Milik sendiri (Private)</td>
                <td><span class="checkbox"></span> Sewa (Rented)</td>
            </tr>
            <tr>
                <td><span class="checkbox"></span> Milik keluarga (Family)</td>
                <td><span class="checkbox"></span> Kost (Boarded)</td>
            </tr>
        </table>

        <table style="margin-top: 10px;">
            <tr>
                <td rowspan="3" style="width: 150px;">Kendaraan<br>(Vehicles)</td>
                <td>Merk (Brand)</td>
                <td>: </td>
            </tr>
            <tr>
                <td>Tahun (Year)</td>
                <td>: </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span class="checkbox"></span> Milik sendiri (Private) &nbsp;&nbsp;
                    <span class="checkbox"></span> Milik kantor (office)<br>
                    <span class="checkbox"></span> Milik keluarga (Family) &nbsp;&nbsp;
                    <span class="checkbox"></span> Lain-lain (others)
                </td>
            </tr>
        </table>
    </div>

    <!-- SECTION II: SUSUNAN KELUARGA -->
    <div class="section">
        <div class="section-title">II. SUSUNAN KELUARGA (members of Family)</div>
        <p style="font-size: 9px;">Mohon tuliskan anggota keluarga, termasuk diri Anda (Please write down your family members, including you)</p>
        
        <table>
            <tr>
                <th>HUB.KELUARGA<br>(Fam.relationship)</th>
                <th>NAMA<br>(Name)</th>
                <th>L/P<br>(M/F)</th>
                <th>UMUR<br>(Age)</th>
                <th>PENDIDIKAN<br>(Education)</th>
                <th>PEKERJAAN<br>(Employment)</th>
                <th>KETERANGAN<br>(Remarks)</th>
            </tr>
            <tr><td>Ayah (Father)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Ibu (Mother)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Saudara 1 (1st child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Saudara 2 (2nd child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Saudara 3 (3rd child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Suami/istri (Spouse)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
            <tr><td>Anak 1 (1st child)</td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
        </table>
    </div>

    <div style="page-break-before: always;"></div>

    <!-- SECTION III: RIWAYAT PENDIDIKAN -->
    <div class="section">
        <div class="section-title">III. RIWAYAT PENDIDIKAN (Formal & Informal Education Record)</div>
        
        <table>
            <tr>
                <th>TINGKAT<br>(Level)</th>
                <th>NAMA SEKOLAH<br>(Name of School)</th>
                <th>TEMPAT<br>(Place)</th>
                <th>JURUSAN<br>(Major)</th>
                <th colspan="2">TAHUN</th>
                <th>GELAR<br>(Academic Title)</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th>Dari (From)</th>
                <th>Sampai (To)</th>
                <th></th>
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

        <div style="margin-top: 15px;">
            <strong>KURSUS / TRAINING (Courses)</strong>
            <table style="margin-top: 5px;">
                <tr>
                    <th>JENIS<br>(Subject)</th>
                    <th>PENYELENGGARA<br>(Executor)</th>
                    <th>TEMPAT<br>(Place)</th>
                    <th colspan="2">TAHUN</th>
                    <th>DIBIAYAI OLEH<br>(Supported by)</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Dari (From)</th>
                    <th>Sampai (To)</th>
                    <th></th>
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
                @for($i = count($trainings); $i < 4; $i++)
                <tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>
                @endfor
            </table>
        </div>
    </div>

    <!-- SECTION IX: RIWAYAT PEKERJAAN -->
    <div class="section">
        <div class="section-title">IX. RIWAYAT PEKERJAAN (Employment History) dimulai dari pekerjaan terakhir / start from the latest job</div>
        
        @foreach($workExperiences->take(3) as $index => $work)
        <div style="margin: 10px 0;">
            <strong>{{ $index + 1 }}</strong>
            <table>
                <tr>
                    <th>NAMA PERUSAHAAN<br>(Company)</th>
                    <th>JABATAN<br>(Position)</th>
                    <th>JML.KARY<br>(Total Employees)</th>
                    <th>NAMA ATASAN<br>(Name of Superior)</th>
                    <th colspan="2">TAHUN</th>
                    <th>GAJI<br>(Salary)</th>
                </tr>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Dari (From)</th>
                    <th>Sampai (To)</th>
                    <th></th>
                </tr>
                <tr>
                    <td>{{ $work->companyname ?? '' }}</td>
                    <td>{{ $work->joblevel ?? '' }}</td>
                    <td></td>
                    <td></td>
                    <td>{{ $work->startdate ? \Carbon\Carbon::parse($work->startdate)->format('Y') : '' }}</td>
                    <td>{{ $work->enddate ? \Carbon\Carbon::parse($work->enddate)->format('Y') : ($work->iscurrent ? 'Sekarang' : '') }}</td>
                    <td>{{ $work->salary ?? '' }}</td>
                </tr>
            </table>
            <div style="margin-top: 5px;">
                <strong>ALASAN KELUAR (Reason for Leaving)</strong><br>
                {{ $work->eexp_comments ?? '' }}
            </div>
        </div>
        @endforeach
    </div>

    <div style="margin-top: 30px; text-align: center;">
        <p>Dengan ini saya menyatakan bahwa informasi yang saya berikan adalah benar. Apabila terdapat informasi yang tidak benar maka saya bersedia mempertanggungjawabkan segala akibatnya.</p>
        <p>Saya memahami bahwa keputusan diterima atau tidaknya lamaran saya tergantung pada proses seleksi berikutnya.</p>
        
        <div style="margin-top: 30px;">
            <span style="margin-right: 200px;">Jakarta, {{ date('d F Y') }}</span>
        </div>
        
        <div style="margin-top: 80px;">
            <span style="margin-right: 200px;">({{ $applicant->firstname }} {{ $applicant->lastname }})</span>
        </div>
    </div>
</body>
</html>
