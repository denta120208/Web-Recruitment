<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offering Letter</title>
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
        .highlight-box table { width: 100%; border-collapse: collapse; }
        .highlight-box td { padding: 6px 0; vertical-align: top; font-size: 15px; }
        .highlight-label { width: 130px; color: #4a5568; font-weight: 600; }
        .highlight-value { color: #2d3748; }
        ul { margin-left: 20px; margin-bottom: 16px; }
        li { margin-bottom: 6px; }
        .footer { margin-top: 32px; color: #2d3748; font-size: 15px; line-height: 1.7; }
        .company-name { color: #009290; font-weight: 700; }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-section">
            <h1>Offering Letter</h1>
            <p class="subtitle">PT Metropolitan Land, Tbk</p>
        </div>

        <div class="content">
            <p>Dear <strong>{{ $candidateName }}</strong>,</p>

            <p>Dengan senang hati kami informasikan surat penawaran kerja dengan <strong>PT Metropolitan Land, Tbk</strong>.</p>

            <p>Berikut adalah ringkasan penawaran kerja dari kami:</p>

            <div class="highlight-box">
                <table>
                    <tr>
                        <td class="highlight-label">Posisi</td>
                        <td class="highlight-value">: {{ $jobTitle }}</td>
                    </tr>
                    <tr>
                        <td class="highlight-label">Lokasi Kerja</td>
                        <td class="highlight-value">: {{ $placementLocation }}</td>
                    </tr>
                    <tr>
                        <td class="highlight-label">Alamat</td>
                        <td class="highlight-value">: Gedung MGold Tower, Jl. KH. Noer Ali, RT.008/RW.002, Pekayon Jaya, Kec. Bekasi Sel., Kota Bks, Jawa Barat</td>
                    </tr>
                    <tr>
                        <td class="highlight-label">Gaji &amp; Benefit</td>
                        <td class="highlight-value">:
                            <ul>
                                <li>[Gaji Terlampir]</li>
                                <li>BPJS Kesehatan</li>
                                <li>BPJS Ketenagakerjaan</li>
                                <li>Mandiri In Health</li>
                                <li>Pajak ditanggung perusahaan</li>
                                <li>Dan lain sebagainya</li>
                            </ul>
                        </td>
                    </tr>
                </table>
            </div>

            <p>Detail lengkap mengenai penawaran kerja ini terlampir dalam dokumen <strong>Offering Letter</strong>. Mohon untuk mempelajari dengan seksama.</p>

            <p>Jika Anda setuju dengan penawaran ini, silakan menandatangani dokumen tersebut dan mengembalikannya kepada kami paling lambat <strong>{{ $offeringLetterDate }}</strong>.</p>

            <p>Jika Anda menerima offering letter ini, mohon untuk dapat mempersiapkan dokumen-dokumen pendukung untuk karyawan baru sebagai berikut:</p>
            <ul>
                <li>Scan KTP</li>
                <li>Scan KK</li>
                <li>Scan SIM</li>
                <li>Scan NPWP (pemadanan)</li>
                <li>Screenshot rekening Mandiri</li>
            </ul>

            <p>Jika ada pertanyaan atau diskusi terkait offering letter, dapat menghubungi via WhatsApp (+62 812-8569-3833) atau dengan membalas email ini.</p>

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
