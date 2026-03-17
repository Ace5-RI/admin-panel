<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/klien.css') }}">
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
    <h1 class="title">Manajemen Klien</h1>
    <p class="subtitle">Kelola dan Pantau Klien</p>


<br>
<div class="search">

<input type="text" class="search2" placeholder="Cari Klien Berdasarkan Nama, Email Atau Perusahaan">

<div class="view-buttons">
    <button onclick="showTable()" class="view-button">Tampilan Tabel</button>
    <button onclick="showCard()">Tampilan Kartu</button>
</div>

<!-- Tampilan tabel -->

</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/klien.js"></script>
</html>
