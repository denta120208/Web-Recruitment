<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Undangan Psikotest</title>
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
        
        .psikotest-box {
            background: #009290;
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            color: white;
        }
        
        .psikotest-title {
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
            <h1>Undangan Psikotest</h1>
            <p class="subtitle">PT Metropolitan Land, Tbk</p>
        </div>
        
        <div class="greeting">
            Dear <strong>{{ $candidateName }}</strong>,<br><br>
            Dengan ini kami mengundang Anda untuk mengikuti Psikotest offline Calon Karyawan <strong>PT. Metropolitan Land, Tbk</strong> dengan detail sebagai berikut:
        </div>
        
        <div class="psikotest-box">
            <div class="psikotest-title">Detail Psikotest</div>
            
            <div class="detail-item">
                <div class="detail-label">Tanggal Psikotes</div>
                <div class="detail-value">{{ $psikotestDate }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">Jam Psikotes</div>
                <div class="detail-value">{{ $psikotestTime }} WIB - Selesai</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Lokasi</div>
                <div class="detail-value">
                    @php
                        $location = $psikotestLocation;
                        if (preg_match('/https?:\/\/[^\s]+/', $location, $matches)) {
                            $url = $matches[0];
                            $location = preg_replace('/https?:\/\/[^\s]+/', '<a href="' . $url . '" style="color: #ffffffff; text-decoration: underline; font-weight: 600;" target="_blank">' . $url . '</a>', $location);
                        }
                    @endphp
                    {!! nl2br(e($psikotestLocation)) !== $location ? $location : nl2br(e($psikotestLocation)) !!}
                </div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">Panduan Pengerjaan Psikotest:</div>
            <ul class="info-list">
                <li>Mohon untuk tidak terlambat dan hadir pada agenda psikotes setidaknya 10 menit sebelum dimulai</li>
                <li>Berpakaian rapi dan sopan, membawa kartu identitas dan alat tulis</li>
                <li>Baca lebih lanjut petunjuk pelaksanaan sebelum memulai tes</li>
                <li>Mohon dapat mengerjakan tes dalam kondisi FIT karena membutuhkan konsentrasi tinggi saat mengerjakan tes</li>
                <li>Harap diingat bahwa agenda psikotest ini telah dikonfirmasi oleh asessor. Perubahan jadwal tidak dapat dilakukan secara mendadak, mohon untuk memberitahukan kepada PIC yang bersangkutan</li>
            </ul>
        </div>
        
        <div class="greeting">
            Mohon konfirmasi kesediaan mengikuti tes offline dengan mereply email ini.<br><br>
            Terima kasih banyak atas perhatiannya, semoga berhasil!<br><br>
            Hormat kami,<br>
            <strong>HRD Metland</strong><br>
            METROPOLITAN LAND, TBK<br>
            Tel +62 21 2808 7777<br>
            hc.team@metropolitanland.com<br><br>
            PT Metropolitan Land, Tbk<br>
            Lt. 12 MGold Tower<br>
            Jl. KH. Noer Ali, RT.008/RW.002, Pekayon Jaya<br>
            Kota Bekasi 17148<br>
            INDONESIA
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
