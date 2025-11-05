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
            <h1>🎉 Selamat!</h1>
            <p class="subtitle">Anda telah lolos ke tahap interview</p>
        </div>
        
        <div class="greeting">
            Yth. <strong>{{ $candidateName }}</strong>,<br><br>
            Selamat! Kami dengan senang hati mengundang Anda untuk mengikuti sesi interview untuk posisi <strong>{{ $jobTitle }}</strong>.
        </div>
        
        <div class="interview-box">
            <div class="interview-title">Detail Interview</div>
            
            <div class="detail-item">
                <div class="detail-label">📅 Tanggal</div>
                <div class="detail-value">{{ $interviewDate }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">🕐 Waktu</div>
                <div class="detail-value">{{ $interviewTime }} WIB</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">📍 Lokasi</div>
                <div class="detail-value">{{ $interviewLocation }}</div>
            </div>
            
            <div class="detail-item">
                <div class="detail-label">👤 Interviewer</div>
                <div class="detail-value">{{ $interviewBy }}</div>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">Hal yang Perlu Dipersiapkan</div>
            <ul class="info-list">
                <li>Datang 15 menit sebelum waktu interview</li>
                <li>Membawa CV dan dokumen pendukung lainnya</li>
                <li>Berpakaian rapi dan profesional</li>
              
            </ul>
        </div>
        
        <div class="greeting">
            Jika ada pertanyaan atau kendala, silakan hubungi kami.<br><br>
            Kami menantikan kehadiran Anda!<br><br>
            Salam,<br>
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
