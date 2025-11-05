<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP Pendaftaran</title>
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
        
        .logo {
            width: 100px;
            height: 100px;
            display: inline-block;
            margin-bottom: 20px;
            animation: float 3s ease-in-out infinite;
        }
        
        .logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            filter: drop-shadow(0 10px 30px rgba(0, 146, 144, 0.3));
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
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
        
        .otp-box {
            background: #009290;
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            margin: 30px 0;
            position: relative;
            overflow: hidden;
        }
        
        .otp-box::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }
        
        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .otp-label {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            font-weight: 600;
        }
        
        .otp-code {
            font-size: 56px;
            font-weight: 800;
            color: white;
            letter-spacing: 15px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            position: relative;
            z-index: 1;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .timer-section {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
            position: relative;
            z-index: 1;
        }
        
        .timer-icon {
            width: 20px;
            height: 20px;
            fill: rgba(255, 255, 255, 0.9);
        }
        
        .timer-text {
            color: rgba(255, 255, 255, 0.9);
            font-size: 15px;
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
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .info-icon {
            width: 20px;
            height: 20px;
            fill: #009290;
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
        
        .warning {
            background: #fff5f5;
            border-left: 4px solid #f56565;
            border-radius: 12px;
            padding: 20px;
            margin-top: 25px;
        }
        
        .warning-title {
            color: #c53030;
            font-weight: 700;
            font-size: 14px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .warning-icon {
            width: 18px;
            height: 18px;
            fill: #f56565;
        }
        
        .warning-text {
            color: #742a2a;
            font-size: 13px;
            line-height: 1.6;
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
        
        .sparkle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: #009290;
            border-radius: 50%;
            animation: sparkle 2s ease-in-out infinite;
        }
        
        @keyframes sparkle {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        
        .sparkle:nth-child(1) { top: 20%; left: 15%; animation-delay: 0s; }
        .sparkle:nth-child(2) { top: 60%; left: 85%; animation-delay: 0.5s; }
        .sparkle:nth-child(3) { top: 80%; left: 20%; animation-delay: 1s; }
        .sparkle:nth-child(4) { top: 30%; right: 15%; animation-delay: 1.5s; }
        
        @media (max-width: 600px) {
            .container {
                padding: 30px 20px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .otp-code {
                font-size: 42px;
                letter-spacing: 10px;
            }
            
            .logo {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        <div class="sparkle"></div>
        
        <div class="logo-section">
            <h1>Verifikasi Akun Anda</h1>
            <p class="subtitle">Terima kasih telah bergabung dengan <strong>Metland Recruitment</strong></p>
        </div>
        
        <div class="otp-box">
            <div class="otp-label">Kode Verifikasi OTP</div>
            <div class="otp-code">{{ $otp }}</div>
            <div class="timer-section">
                <svg class="timer-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/>
                </svg>
                <span class="timer-text">Berlaku selama 10 menit</span>
            </div>
        </div>
        
        <div class="info-section">
            <div class="info-title">
                <svg class="info-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>
                </svg>
                Cara Menggunakan Kode OTP
            </div>
            <ul class="info-list">
                <li>Masukkan 6 digit kode di atas pada halaman verifikasi</li>
                <li>Kode ini hanya berlaku untuk satu kali penggunaan</li>
                <li>Jangan bagikan kode ini kepada siapapun</li>
                <li>Kode akan kadaluarsa dalam 10 menit</li>
            </ul>
        </div>
        
        <div class="warning">
            <div class="warning-title">
                <svg class="warning-icon" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>
                </svg>
                Perhatian Keamanan
            </div>
            <p class="warning-text">
                Jika Anda tidak melakukan pendaftaran, abaikan email ini. 
                Tim Metland Recruitment tidak akan pernah meminta kode OTP Anda melalui telepon, email, atau pesan.
            </p>
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