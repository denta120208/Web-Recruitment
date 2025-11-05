<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Status Lamaran</title>
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
        
        .message-box {
            background: #f7fafc;
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            border-left: 4px solid #009290;
        }
        
        .message-text {
            color: #4a5568;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 15px;
        }
        
        .encouragement-box {
            background: linear-gradient(135deg, #009290 0%, #006d6b 100%);
            border-radius: 20px;
            padding: 30px;
            margin: 30px 0;
            color: white;
            text-align: center;
        }
        
        .encouragement-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .encouragement-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .encouragement-text {
            font-size: 15px;
            opacity: 0.95;
            line-height: 1.6;
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
            content: '💡';
            position: absolute;
            left: 0;
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
            <h1>Pemberitahuan Status Lamaran</h1>
            <p class="subtitle">Terima kasih atas partisipasi Anda</p>
        </div>
        
        <div class="greeting">
            Yth. <strong>{{ $candidateName }}</strong>,<br><br>
            Terima kasih atas minat dan waktu yang Anda berikan untuk melamar posisi <strong>{{ $jobTitle }}</strong> di Metland.
        </div>
        
        <div class="message-box">
            <div class="message-text">
                Setelah melalui proses seleksi yang ketat dan mempertimbangkan berbagai aspek, dengan berat hati kami informasikan bahwa saat ini kami belum dapat melanjutkan proses rekrutmen Anda untuk posisi tersebut.
            </div>
            <div class="message-text">
                Keputusan ini tidak mengurangi apresiasi kami terhadap kualifikasi dan pengalaman yang Anda miliki. Kami sangat menghargai usaha dan waktu yang telah Anda investasikan dalam proses rekrutmen ini.
            </div>
        </div>
        
        <div class="encouragement-box">
            <div class="encouragement-icon">💪</div>
            <div class="encouragement-title">Jangan Menyerah!</div>
            <div class="encouragement-text">
                Kami mendorong Anda untuk terus mengembangkan diri dan melamar kembali untuk posisi lain yang sesuai dengan keahlian Anda di masa mendatang.
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">Saran untuk Langkah Selanjutnya</div>
            <ul class="info-list">
                <li>Terus pantau lowongan pekerjaan di website kami</li>
                <li>Tingkatkan skill dan pengalaman Anda</li>
                <li>Jangan ragu untuk melamar posisi lain yang sesuai</li>
                <li>Tetap semangat dalam pencarian karir Anda</li>
            </ul>
        </div>
        
        <div class="greeting">
            Kami berharap Anda sukses dalam pencarian karir Anda dan semoga di kesempatan lain kita dapat bekerja sama.<br><br>
            Salam hormat,<br>
            <strong>Tim HRD Metland Recruitment</strong>
        </div>
        
        <div class="footer">
            <p class="footer-text">
                Email ini dikirim secara otomatis oleh sistem<br>
                <span class="company-name">Metland Recruitment</span><br>
                © 2025 Metland. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
