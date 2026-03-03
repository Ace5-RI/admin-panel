<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Dashboard</title>
</head>
<body>
    <div class="panel">
        <img src="{{ asset('img/logos.png') }}" alt="" class="logo">
        <h1>Admin Panel</h1>
        <p style="margin-top: -20px" class="gray">Manajemen Klien</p>
    </div>

    <div class="main">
    <h1 class="title">Dashboard</h1>
    <p  style="margin-left: -18px">Selamat Datang Kembali</p>

    <div class="container">
    <div class="total">
        <h4>Total Klien</h4>
        <h1 id="totalClient"></h1>
        <div class="percentage green" id="clientPercent"></div>
    </div>

    <div class="total">
        <h4>Total Transaksi</h4>
        <h1 id="totalTransaction"></h1>
        <div class="percentage green" id="transactionPercent"></div>
    </div>

    <div class="total">
        <h4>Klien Aktif</h4>
        <h1 id="activeClient"></h1>
        <div class="percentage green" id="activePercent"></div>
    </div>

    <div class="total">
        <h4>Klien Nonaktif</h4>
        <h1 id="inactiveClient"></h1>
        <div class="percentage red" id="inactivePercent"></div>
    </div>
</div>
    </div>
</div>

    
</div>
<script src="js/dashboard.js"></script>
</body>
</html>