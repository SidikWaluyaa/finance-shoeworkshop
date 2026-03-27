<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #1f2937;
            margin: 0;
            padding: 0;
        }
        .header {
            padding-bottom: 20px;
            border-bottom: 2px solid #f3f4f6;
            margin-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .document-title {
            font-size: 18px;
            text-transform: uppercase;
            text-align: right;
            margin-top: -30px;
        }
        .info-grid {
            width: 100%;
            margin-bottom: 30px;
        }
        .info-col {
            width: 50%;
            vertical-align: top;
        }
        .label {
            color: #6b7280;
            font-size: 10px;
            text-transform: uppercase;
            margin-bottom: 4px;
        }
        .value {
            font-weight: bold;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th {
            background-color: #f9fafb;
            text-align: left;
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-size: 11px;
            text-transform: uppercase;
        }
        td {
            padding: 10px;
            border-bottom: 1px solid #f3f4f6;
            vertical-align: top;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 40%;
            margin-left: 60%;
        }
        .total-row {
            padding: 10px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .grand-total {
            font-size: 16px;
            font-weight: bold;
            color: #4f46e5;
            padding-top: 15px;
        }
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-success { background-color: #d1fae5; color: #065f46; }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #9ca3af;
            padding-top: 10px;
            border-top: 1px solid #f3f4f6;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">SHOEWORKSHOP</div>
        <div class="document-title">{{ $title }}</div>
        <p style="font-size: 10px; color: #6b7280; margin-top: 5px;">
            Jl. Raya Kedon No. 123, Malang, Jawa Timur<br>
            Telp: +62 812 3456 7890 | Email: finance@shoeworkshop.com
        </p>
    </div>

    @yield('content')

    <div class="footer">
        Dicetak secara otomatis oleh ShoeWorkshop Financial System pada {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>
