<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Bergabung</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
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
            max-width: 700px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }
        .container::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 6px;
            background: linear-gradient(90deg, #009290, #00b8b5, #009290, #006d6b);
            background-size: 300% 300%;
            animation: gradientMove 3s ease infinite;
        }
        @keyframes gradientMove {
            0%,100% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
        }
        .logo-section { text-align: center; margin-bottom: 30px; }
        h1 { color: #2d3748; font-size: 28px; font-weight: 700; margin-bottom: 10px; }
        .subtitle { color: #718096; font-size: 16px; margin-bottom: 40px; }
        .content { color: #2d3748; font-size: 16px; line-height: 1.8; }
        .content p { margin-bottom: 16px; }
        .highlight-box {
            background: #f7fafc;
            border-radius: 16px;
            padding: 20px 24px;
            margin: 24px 0;
            border-left: 4px solid #009290;
        }
        .highlight-row { margin-bottom: 8px; }
        .highlight-label { font-weight: 600; color: #4a5568; }
        .highlight-value { color: #2d3748; }
        .footer { margin-top: 32px; color: #2d3748; font-size: 15px; line-height: 1.7; }
        .company-name { color: #009290; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1>Selamat Bergabung</h1>
            <p class="subtitle">PT Metropolitan Land, Tbk</p>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $candidateName }}</strong>,</p>

            <p>Dengan senang hati kami menginformasikan bahwa Anda akan resmi bergabung dengan <strong>PT Metropolitan Land, Tbk</strong> sebagai <strong>{{ $jobTitle }}</strong> pada:</p>

            <div class="highlight-box">
                <p class="highlight-row"><span class="highlight-label">📅 Tanggal Join:</span> <span class="highlight-value">{{ $joinDate }}</span></p>
                <p class="highlight-row"><span class="highlight-label">📍 Lokasi Kerja:</span> <span class="highlight-value">{{ $workLocation }}</span></p>
            </div>

            <p>Kami ucapkan selamat bergabung dan semoga sukses memulai perjalanan baru bersama kami. Silakan hadir pada pukul <strong>08.00</strong> untuk penandatanganan kontrak PKWT dan juga hari pertama bekerja.</p>

            <p>Apabila ada pertanyaan lebih lanjut, jangan ragu untuk menghubungi HR di <strong>(+62 812-8569-3833)</strong> atau melalui email ini.</p>

            <div class="footer">
                <p>Hormat kami,</p>
                <p><strong>HRD Metland</strong><br>
                METROPOLITAN LAND, TBK<br>
                Tel +62 21 2808 7777<br>
                hc.team@metropolitanland.com</p>

                <p>PT Metropolitan Land, Tbk<br>
                Lt. 12 MGold Tower<br>
                Jl. KH. Noer Ali, RT.008/RW.002, Pekayon Jaya<br>
                Kota Bekasi 17148<br>
                INDONESIA</p>

                <p class="company-name">Metland Recruitment</p>
            </div>
        </div>
    </div>
</body>
</html>
