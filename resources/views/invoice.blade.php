<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $client->name }}</title>
    <style>
        @page {
            margin: 20px;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice {
            width: 100%;
        }

        .header {
            border-bottom: 2px solid #10b981;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .company-name {
            font-size: 22px;
            font-weight: bold;
            color: #10b981;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 11px;
            color: #666;
            line-height: 1.6;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            margin: 0;
            font-size: 28px;
            color: #222;
        }

        .invoice-number {
            margin-top: 5px;
            font-size: 12px;
            color: #777;
        }

        .section-title {
            font-size: 11px;
            font-weight: bold;
            color: #666;
            margin-bottom: 8px;
        }

        .client-box {
            margin-bottom: 20px;
        }

        .client-name {
            font-size: 15px;
            font-weight: bold;
            color: #111;
        }

        .client-box p {
            margin: 3px 0;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: #f8fafc;
            border: 1px solid #ddd;
        }

        .info-table td {
            padding: 12px;
            border-right: 1px solid #ddd;
        }

        .info-table td:last-child {
            border-right: none;
        }

        .label {
            font-size: 10px;
            font-weight: bold;
            color: #777;
        }

        .value {
            margin-top: 4px;
            font-size: 13px;
            font-weight: bold;
            color: #111;
        }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .invoice-table th {
            background: #10b981;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }

        .invoice-table td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        .text-right {
            text-align: right;
        }

        .total-wrapper {
            margin-top: 20px;
        }

        .total-table {
            width: 250px;
            margin-left: auto;
            border-collapse: collapse;
        }

        .total-table td {
            border: 1px solid #10b981;
            padding: 10px;
        }

        .total-label {
            background: #f0fdf4;
            font-weight: bold;
        }

        .total-amount {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
            color: #10b981;
        }

        .bank-section {
            margin-top: 25px;
            border: 1px solid #ddd;
            padding: 15px;
        }

        .bank-title {
            font-weight: bold;
            margin-bottom: 10px;
            color: #444;
        }

        .bank-table {
            width: 100%;
            border-collapse: collapse;
        }

        .bank-table td {
            padding: 6px 0;
        }

        .bank-account {
            text-align: right;
            font-family: monospace;
            font-weight: bold;
        }

        .bank-owner {
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 11px;
            color: #666;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }
        
        @media print {
            body { margin: 0; padding: 0; }
            .invoice { margin: 0; }
        }
    </style>
</head>
<body>

@php
    $companyName = \App\Models\Setting::get('company_name', 'Bali Solution Biz');
    $companyAddress = \App\Models\Setting::get('company_address', 'Jl. Raya Kuta No. 123, Bali');
    $companyPhone = \App\Models\Setting::get('company_phone', '+62 812 3456 7890');
    $companyEmail = \App\Models\Setting::get('company_email', 'info@balisolutionbiz.com');
    
    $bankBCA = \App\Models\Setting::get('bank_bca', '');
    $bankMandiri = \App\Models\Setting::get('bank_mandiri', '');
    $bankAccountName = \App\Models\Setting::get('bank_account_name', '');
    
    $status = $status ?? 'AKTIF';
@endphp

<div class="invoice">
    <div class="header">
        <table class="header-table">
            <tr>
                <td width="70%">
                    <div class="company-name">{{ $companyName }}</div>
                    <div class="company-info">
                        {{ $companyAddress }}<br>
                        Telp: {{ $companyPhone }}<br>
                        Email: {{ $companyEmail }}
                    </div>
                </td>
                <td width="30%" class="invoice-title">
                    <h1>INVOICE</h1>
                    <div class="invoice-number">{{ $nomor }}</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="client-box">
        <div class="section-title">KEPADA YTH :</div>
        <div class="client-name">{{ $client->name }}</div>
        @if($client->company)<p> Perusahaan : {{ $client->company }}</p>@endif
        <p> Email : {{ $client->email }}</p>
        @if($client->phone_number)<p> Nomer :{{ $client->phone_number }}</p>@endif
    </div>

    <table class="info-table">
        <tr>
            <td>
                <div class="label">TANGGAL INVOICE</div>
                <div class="value">{{ $tanggal }}</div>
            </td>
            <td>
                <div class="label">MASA BERAKHIR</div>
                <div class="value">{{ $jatuh_tempo }}</div>
            </td>
            <td>
                <div class="label">STATUS</div>
                <div class="value">{{ $status }}</div>
            </td>
        </tr>
    </table>

    <table class="invoice-table">
        <thead>
            <tr>
                <th>DESKRIPSI</th>
                <th width="180" class="text-right">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $client->description ?? 'Langganan Tahunan' }}</td>
                <td class="text-right">Rp {{ number_format($client->revenue, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <div class="total-wrapper">
        <table class="total-table">
            <tr><td class="total-label">TOTAL TAGIHAN</td></tr>
            <tr><td class="total-amount">Rp {{ number_format($client->revenue, 0, ',', '.') }}</td></tr>
        </table>
    </div>

    @if($bankBCA || $bankMandiri)
    <div class="bank-section">
        <div class="bank-title">REKENING TUJUAN</div>
        <table class="bank-table">
            @if($bankBCA)<tr><td>BCA</td><td class="bank-account">{{ $bankBCA }}</td></tr>@endif
            @if($bankMandiri)<tr><td>Mandiri</td><td class="bank-account">{{ $bankMandiri }}</td></tr>@endif
        </table>
        @if($bankAccountName)<div class="bank-owner">Atas Nama: {{ $bankAccountName }}</div>@endif
    </div>
    @endif

    <div class="footer">
        <p>{{ \App\Models\Setting::get('invoice_footer', 'Terima kasih atas kepercayaan dan kerja samanya.') }}</p>
        <br>
        <p>Hormat kami,</p>
        <p><strong>{{ $companyName }}</strong></p>
    </div>
</div>

</body>
</html>