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
    <p class="subtitle">Selamat Datang Kembali</p>

    <div class="container">
    <div class="total">
        <h4>Total Klien</h4>
        <h1 id="totalClient" style="margin-left:-270px"></h1>
        <div class="percentage green" id="clientPercent"></div>
        <img src="{{ asset('img/profile.png') }}" alt="" class="profile">
    </div>

    <div class="total">
        <h4 style="margin-left: -185px">Klien Aktif</h4>
        <h1 id="totalTransaction" style="margin-left:-270px"></h1>
        <div class="percentage green" id="transactionPercent"></div>
        <img src="{{ asset('img/upline.png') }}" alt="" class="aktif">
    </div>

    <div class="total">
        <h4 style="margin-left: -145px">Akan Berakhir</h4>
        <h1 id="activeClient" style="margin-left:-270px"></h1>
        <div class="percentage green" id="activePercent" ></div>
        <img src="{{ asset('img/tanggal.png') }}" alt="" class="tanggal">
        
    </div>

    <div class="total">
        <h4 style="margin-left: -125px">Total Pendapatan</h4>
        <h1 id="inactiveClient" style="margin-left:-270px"></h1>
        <div class="percentage red" id="inactivePercent"></div>
        <img src="{{ asset('img/cash.png') }}" alt="" class="keuangan">
    </div>
     
    <div class="container2">
<div class="table">
    <canvas id="userChart"></canvas>
</div>
<div class="table">
    <canvas id="activityChart"></canvas>
</div>

    
</div>
</div>

<div class="container">

    <div class="info">
        
    </div>

</div>


</div>


    </div>
   
</div>

    
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/dashboard.js"></script>
</body>
</html>