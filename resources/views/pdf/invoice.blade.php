<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $client->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            background: #fff;
            padding: 40px;
        }
        
        .invoice {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
        }
        
        /* Header */
        .invoice-header {
            text-align: center;
            padding: 30px;
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: white;
            border-radius: 10px 10px 0 0;
        }
        
        .invoice-header h1 {
            font-size: 32px;
            margin-bottom: 5px;
        }
        
        .invoice-header p {
            font-size: 14px;
            opacity: 0.8;
        }
        
        /* Body */
        .invoice-body {
            padding: 30px;
            border: 1px solid #e5e7eb;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        
        /* Info Klien */
        .client-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border-left: 4px solid #22c55e;
        }
        
        .client-info h3 {
            margin-bottom: 10px;
            font-size: 18px;
        }
        
        .client-info p {
            margin: 5px 0;
            color: #4b5563;
        }
        
        /* Detail Invoice */
        .detail-grid {
            display: flex;
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }
        
        .detail-card {
            background: #f1f5f9;
            padding: 15px;
            border-radius: 8px;
            flex: 1;
        }
        
        .detail-card .label {
            font-size: 11px;
            color: #6b7280;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        
        .detail-card .value {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
        }
        
        /* Tabel */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .invoice-table th {
            text-align: left;
            padding: 12px;
            background: #f1f5f9;
            border-bottom: 2px solid #e5e7eb;
        }
        
        .invoice-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Total */
        .total-box {
            text-align: right;
            padding: 20px;
            background: #f0fdf4;
            border-radius: 10px;
            margin-bottom: 30px;
        }
        
        .total-box .total-label {
            font-size: 18px;
            font-weight: bold;
        }
        
        .total-box .total-amount {
            font-size: 28px;
            font-weight: bold;
            color: #22c55e;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="invoice">
        <div class="invoice-header">
            <h1>INVOICE</h1>
            <p>{{ $nomor }}</p>
        </div>
        
        <div class="invoice-body">
            <div class="client-info">
                <h3>KEPADA YTH:</h3>
                <p><strong>{{ $client->name }}</strong></p>
                <p>{{ $client->company }}</p>
                <p>📧 {{ $client->email }}</p>
                <p>📞 {{ $client->phone_number }}</p>
            </div>
            
            <div class="detail-grid">
                <div class="detail-card">
                    <div class="label">TANGGAL INVOICE</div>
                    <div class="value">{{ $tanggal }}</div>
                </div>
                <div class="detail-card">
                    <div class="label">JATUH TEMPO</div>
                    <div class="value">{{ $jatuh_tempo }}</div>
                </div>
            </div>
            
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th>DESKRIPSI</th>
                        <th class="text-right">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Langganan Tahunan</td>
                        <td class="text-right">Rp {{ number_format($client->revenue, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
            
            <div class="total-box">
                <div class="total-label">TOTAL TAGIHAN</div>
                <div class="total-amount">Rp {{ number_format($client->revenue, 0, ',', '.') }}</div>
            </div>
            
            <div class="footer">
                <p>Terima kasih atas kepercayaan dan kerja samanya.</p>
                <p><strong>Admin Panel</strong></p>
            </div>
        </div>
    </div>
</body>
</html>