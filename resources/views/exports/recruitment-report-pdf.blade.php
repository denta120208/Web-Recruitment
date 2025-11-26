<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Recruitment Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            font-size: 16px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
        }
        th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .footer {
            margin-top: 20px;
            text-align: right;
            font-size: 9px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Recruitment Report</h1>
    <p style="text-align: center; margin-bottom: 20px;">Generated at: {{ $generated_at }}</p>
    
    <table>
        <thead>
            <tr>
                <th>Job Vacancy</th>
                <th>Level</th>
                <th>Lokasi</th>
                <th>Man Power</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Total</th>
                <th>Review</th>
                <th>Interview</th>
                <th>Psiko</th>
                <th>Offering</th>
                <th>MCU</th>
                <th>Hired</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $item)
            <tr>
                <td>{{ mb_convert_encoding($item['job_vacancy_name'] ?? '', 'UTF-8', 'UTF-8') }}</td>
                <td>{{ mb_convert_encoding($item['level'] ?? '', 'UTF-8', 'UTF-8') }}</td>
                <td>{{ mb_convert_encoding($item['location'] ?? '', 'UTF-8', 'UTF-8') }}</td>
                <td>{{ $item['man_power'] ?? 0 }}</td>
                <td>{{ $item['start_date'] ?? '' }}</td>
                <td>{{ $item['end_date'] ?? '' }}</td>
                <td>{{ $item['total_applicants'] ?? 0 }}</td>
                <td>{{ $item['review_applicant'] ?? 0 }}</td>
                <td>{{ $item['interview_user'] ?? 0 }}</td>
                <td>{{ $item['psiko_test'] ?? 0 }}</td>
                <td>{{ $item['offering_letter'] ?? 0 }}</td>
                <td>{{ $item['mcu'] ?? 0 }}</td>
                <td>{{ $item['hired'] ?? 0 }}</td>
                <td>{{ mb_convert_encoding($item['status'] ?? '', 'UTF-8', 'UTF-8') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>Metland Recruitment System</p>
    </div>
</body>
</html>
