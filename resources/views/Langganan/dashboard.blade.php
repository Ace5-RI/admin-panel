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
        <p>Manajemen Klien</p>

<div class="menu">

<a class="menu-btn {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
    <img src="{{ asset('img/menu.png') }}">
    Dashboard
</a>

<a class="menu-btn {{ request()->is('klien') ? 'active' : '' }}" href="/klien">
    <img src="{{ asset('img/klien.png') }}">
    Klien
</a>

<a class="menu-btn {{ request()->is('analistik') ? 'active' : '' }}" href="/analistik">
    <img src="{{ asset('img/analis.png') }}">
    Analistik
</a>

<hr class="hr" style="margin-top: 5px">

<a class="menu-btn {{ request()->is('bantuan') ? 'active' : '' }}" href="/help">
    <img src="{{ asset('img/help.png') }}">
    Bantuan
</a>
<hr class="hr" style="margin-top: 5px">

<div class="user-card">

    <div class="user-icon">
        A
    </div>

    <div class="user-info">
        <div class="user-name" id="userName">
            Admin User
        </div>

        <div class="user-role" id="userRole">
            ADMIN
        </div>

        <div class="user-email" id="userEmail">
            admin@adminportal.com
        </div>
    </div>



</div>


<button class="menu-lgt menu-bottom2" onclick="window.location.href='/'">
    <img src="{{ asset('img/Logout.png') }}">
    Log Out
</button> 

</div>







    </div>

    <div class="main">
    <h1 class="title">Dashboard</h1>
    <p class="subtitle">Selamat Datang Kembali</p>

    <div class="container">
    <div class="total">
        <h4>Total Klien</h4>
        <h1 id="totalClient"></h1>
        <div class="percentage green" id="clientPercent"></div>
        <img src="{{ asset('img/profile.png') }}" alt="" class="profile">
    </div>

    <div class="total">
        <h4 style=>Klien Aktif</h4>
        <h1 id="totalTransaction" ></h1>
        <div class="percentage green" id="transactionPercent"></div>
        <img src="{{ asset('img/upline.png') }}" alt="" class="aktif">
    </div>

    <div class="total">
        <h4 >Akan Berakhir</h4>
        <h1 id="activeClient" style=></h1>
        <div class="percentage green" id="activePercent" ></div>
        <img src="{{ asset('img/tanggal.png') }}" alt="" class="tanggal">
        
    </div>

    <div class="total">
        <h4>Total Pendapatan</h4>
        <h1 id="inactiveClient" "></h1>
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
<br><br>
<div class="container3">

    <div class="info">
        <img src="{{ asset('img/Danger.png') }}" alt="" srcset="" class="danger">
        <p class="lokasi">Perhatian : Langganan Akan Berakhir</p>
        <p class="lokasi1">Terdapat 2 klien dengan langganan yang akan berakhir dalam 30 hari. Segera hubungi Mereka untuk 
           <br> perpanjangan</p>
    </div>

</div>

<div class="container4">
    <div class="card">

        <div class="avatar" id="avatarUser">SN</div>

        <div class="card-header">
            <h2 id="userName">Siti Nurhaliza</h2>
            <p id="userCompany">Innovate Solutions</p>
        </div>

        <div class="days-left">
            <span id="daysLeft">15 hari lagi</span>
        </div>

        <div class="card-body">

            <div class="card-info">
                📧 <span id="userEmail">siti.n@innovate.id</span>
            </div>

            <div class="card-info">
                🏢 <span id="userCompany2">Innovate Solutions</span>
            </div>

            <div class="card-info">
                📅 <span id="expiredDate">28/3/2026</span>
            </div>

            <div class="card-info price">
                📈 <span id="price">Rp 32.000.000</span>
            </div>

        </div>
        <button class="invoice">
            <a href="https://wa.me/qr/3WTMUO54ZOXHB1" class="invoice-link">Invoice</a>
        </button>


        <div class="warning">
            ⚠️ <span id="warningText">Langganan akan berakhir dalam 15 hari</span>
        </div>

    </div>
       <div class="card">

        <div class="avatar" id="avatarUser">SN</div>

        <div class="card-header">
            <h2 id="userName">Siti Nurhaliza</h2>
            <p id="userCompany">Innovate Solutions</p>
        </div>

        <div class="days-left">
            <span id="daysLeft">15 hari lagi</span>
        </div>

        <div class="card-body">

            <div class="card-info">
                📧 <span id="userEmail">siti.n@innovate.id</span>
            </div>

            <div class="card-info">
                🏢 <span id="userCompany2">Innovate Solutions</span>
            </div>

            <div class="card-info">
                📅 <span id="expiredDate">28/3/2026</span>
            </div>

            <div class="card-info price">
                📈 <span id="price">Rp 32.000.000</span>
            </div>

        </div>

        <button class="invoice">
            <a href="https://wa.me/qr/3WTMUO54ZOXHB1" class="invoice-link">Invoice</a>
        </button>

        <div class="warning">
            ⚠️ <span id="warningText">Langganan akan berakhir dalam 15 hari</span>
        </div>

    </div>
      <div class="card">

        <div class="avatar" id="avatarUser">SN</div>

        <div class="card-header">
            <h2 id="userName">Siti Nurhaliza</h2>
            <p id="userCompany">Innovate Solutions</p>
        </div>

        <div class="days-left">
            <span id="daysLeft">15 hari lagi</span>
        </div>

        <div class="card-body">

            <div class="card-info">
                📧 <span id="userEmail">siti.n@innovate.id</span>
            </div>

            <div class="card-info">
                🏢 <span id="userCompany2">Innovate Solutions</span>
            </div>

            <div class="card-info">
                📅 <span id="expiredDate">28/3/2026</span>
            </div>

            <div class="card-info price">
                📈 <span id="price">Rp 32.000.000</span>
            </div>

        </div>

        <button class="invoice">
            <a href="https://wa.me/qr/3WTMUO54ZOXHB1" class="invoice-link">Invoice</a>
        </button>

        <div class="warning">
            ⚠️ <span id="warningText">Langganan akan berakhir dalam 15 hari</span>
        </div>

    </div>

</div>

 

</div>
        


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