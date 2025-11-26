<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Interview</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #009290 0%, #006d6b 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 24px;
            padding: 50px 40px;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: linear-gradient(90deg, #009290, #00b8b5, #009290, #006d6b);
            background-size: 300% 300%;
            animation: gradientMove 3s ease infinite;
        }
        
        @keyframes gradientMove {
            0%, 100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        
        .logo-section {
            text-align: center;
            margin-bottom: 30px;
        }
        
        h1 {
            color: #2d3748;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .subtitle {
            color: #718096;
            font-size: 16px;
            margin-bottom: 40px;
        }
        
        .greeting {
            color: #2d3748;
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
        }
        
        .interview-box {
            background: #009290;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            color: white;
        }
        
        .interview-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .detail-item {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 15px;
            margin-bottom: 12px;
        }
        
        .detail-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.9;
            margin-bottom: 5px;
        }
        
        .detail-value {
            font-size: 16px;
            font-weight: 600;
        }
        
        .info-section {
            background: #f7fafc;
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #009290;
        }
        
        .info-title {
            color: #2d3748;
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
        }
        
        .info-list {
            list-style: none;
            color: #4a5568;
            font-size: 14px;
            line-height: 1.8;
        }
        
        .info-list li {
            padding-left: 25px;
            position: relative;
            margin-bottom: 8px;
        }
        
        .info-list li::before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #009290;
            font-weight: bold;
            font-size: 16px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }
        
        .footer-text {
            color: #718096;
            font-size: 14px;
            line-height: 1.6;
        }
        
        .company-name {
            color: #009290;
            font-weight: 700;
        }
        
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1>Undangan Interview</h1>
            <p class="subtitle">PT Metropolitan Land, Tbk</p>
        </div>
        
        <div class="greeting">
            Dear <strong>{{ $candidateName }}</strong>,<br><br>
            Pengajuan lamaran dan CV Anda melalui Metland Recruitment sudah kami terima.<br><br>
            Dengan ini kami mengundang Anda untuk mengikuti tes seleksi Calon Karyawan <strong>PT. Metropolitan Land, Tbk</strong> dengan detail sebagai berikut:
        </div>
        
        <div class="interview-box">
            <div class="interview-title">Detail Interview</div>
            
            <div class="detail-item">
                <div class="detail-label">Tanggal Interview</div>
                <div class="detail-value">{{ $interviewDate }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Jam Interview</div>
                <div class="detail-value">{{ $interviewTime }} WIB (mohon agar dapat hadir 10 menit sebelum interview dimulai)</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Posisi yang Dilamar</div>
                <div class="detail-value">{{ $jobTitle }}</div>
            </div>


            <div class="detail-item">
                <div class="detail-label">Nama Perusahaan</div>
                <div class="detail-value">PT METROPOLITAN LAND, TBK</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Penempatan Lokasi</div>
                <div class="detail-value">{{ $placementLocation ?? 'Kantor Pusat' }}</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Alamat Lokasi Interview</div>
                <div class="detail-value">
                    @php
                        $location = $interviewLocation;
                        if (preg_match('/https?:\/\/[^\s]+/', $location, $matches)) {
                            $url = $matches[0];
                            $location = preg_replace('/https?:\/\/[^\s]+/', '<a href="' . $url . '" style="color: #ffffffff; text-decoration: underline; font-weight: 600;" target="_blank">' . $url . '</a>', $location);
                        }
                    @endphp
                    {!! nl2br(e($interviewLocation)) !== $location ? $location : nl2br(e($interviewLocation)) !!}
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">PIC</div>
                <div class="detail-value">{{ $picName ?? 'Ibu Natasha' }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Interviewer</div>
                <div class="detail-value">{{ $interviewBy }}</div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">PENTING!</div>
            <p style="margin-bottom: 10px; color: #4a5568;">
                Seluruh proses recruitment di PT Metropolitan Land Tbk beserta seluruh unitnya
                <strong>bebas dari biaya apapun</strong>. Mohon berhati-hati atas penipuan dan pihak-pihak
                yang mengatasnamakan PT Metropolitan Land, Tbk.
            </p>

            <div class="info-title" style="margin-top: 20px;">Notes:</div>
            <ul class="info-list">
                <li>Mohon untuk tidak terlambat dan hadir pada agenda interview 10 menit sebelum interview dimulai.</li>
                <li>Berpakaian rapi dan sopan, serta membawa berkas: kartu identitas, CV terupdate, sertifikat training atau keahlian (jika ada), fotokopi paklaring, dan portfolio.</li>
                <li>Harap diingat bahwa agenda interview ini telah dikonfirmasi oleh User. Perubahan jadwal tidak dapat dilakukan secara mendadak, mohon untuk memberitahukan kepada PIC yang bersangkutan.</li>
                <li>Periksa website perusahaan PT Metropolitan Land, Tbk: <a href="https://metropolitanland.com/id/home" target="_blank" style="color: #009290; font-weight: 600;">https://metropolitanland.com/id/home</a></li>
            </ul>
        </div>
        
        <div class="greeting">
            Kami menantikan kehadiran Anda.<br><br>
            Salam,<br>
            <strong>Tim HRD Metland Recruitment</strong>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                Email ini dikirim secara otomatis oleh sistem<br>
                <span class="company-name">Metland Recruitment</span><br>
                {{ date('Y') }} Metland. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
