<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <title>Dashboard</title>
    <base href="/">

   
</head>


<body>
<div class="panel">
    <img src="{{ asset('img/logos.png') }}" alt="" class="logo">
    <h1>Admin Panel</h1>
    <p>Manajemen Klien</p>

    <div class="menu">
        @if (session('success'))
        <script>alert("{{ session('success') }}");</script>
        @endif

        <a class="menu-btn {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
            <img src="{{ asset('img/menu.png') }}">Dashboard
        </a>

        <a class="menu-btn {{ request()->is('klien') ? 'active' : '' }}" href="/klien">
            <img src="{{ asset('img/klien.png') }}">Klien
        </a>

        <a class="menu-btn {{ request()->is('analistik') ? 'active' : '' }}" href="/analistik">
            <img src="{{ asset('img/analis.png') }}">Analistik
        </a>

        <hr class="hr" style="margin-top: 5px">

        <a class="menu-btn {{ request()->is('bantuan') ? 'active' : '' }}" href="/help">
            <img src="{{ asset('img/help.png') }}">Bantuan
        </a>
        <hr class="hr" style="margin-top: 5px">

        <div class="user-card">
            <div class="user-icon" id="avatarSidebar">A</div>
            <div class="user-info">
                <div class="user-name" id="userNameSidebar">Admin User</div>
                <div class="user-role" id="userRoleSidebar">ADMIN</div>
                <div class="user-email" id="userEmailSidebar">admin@adminportal.com</div>
            </div>
        </div>

        <button class="menu-lgt menu-bottom2" id="logoutBtn">
            <img src="{{ asset('img/Logout.png') }}">Log Out
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
</div>

<div class="total">
    <h4>Klien Aktif</h4>
    <h1 id="activeClient"></h1>
</div>

<div class="total">
    <h4>Tidak Aktif</h4>
    <h1 id="inactiveClient"></h1>
</div>

<div class="total">
    <h4>Total Pendapatan</h4>
    <h1 id="totalRevenue"></h1>
</div>
        </div>

        <div class="container2">
            <div class="table"><canvas id="userChart"></canvas></div>
            <div class="table"><canvas id="activityChart"></canvas></div>
        </div>
    </div>

    <div id="warningContainer" class="warning-grid"></div>

    <div class="container4" id="cardsContainer">
        <!-- User cards akan di-render JS -->
        <!-- ================= POPUP HAPUS WARNING (COPY DARI HALAMAN KLIEN) ================= -->

    </div>

    <div class="add" id="popupHapusWarning">
    <div class="hapusklien">
        <div class="header-konfirmasi">
            <img src="{{ asset('img/peringatan.png') }}" class="peringatan" alt="">
            <h2>Konfirmasi Hapus</h2>
        </div>
        <hr>
        <p style="font-size: 18px">Apakah Anda Yakin Untuk Menghapus Klien Berikut?</p>
        <p style="font-size: 18px" class="nama-klien-warning">"Client"</p>
        <div class="danger-box">
            ⚠️ Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data terkait klien ini akan dihapus secara permanen.
        </div>
        <button class="hapusred" id="confirmDeleteWarningBtn">Ya, Hapus</button>
        <button class="close2" id="cancelDeleteWarningBtn">Batal</button>
    </div>
</div>
</div>



<!-- ================= MODAL POPUP UNTUK BOX STATISTIK ================= -->

<!-- Modal Total Klien -->
<div id="popupTotalKlien" class="modal-popup">
    <div class="modal-content modal-klien">
        <div class="modal-header">
            <div class="header-title">
                <img src="{{ asset('img/profile.png') }}" class="modal-icon" alt="Total Klien">
                <h2>Total Klien</h2>
            </div>
            <span class="close-popup" data-popup="popupTotalKlien">&times;</span>
        </div>
        <div class="modal-body">
            <p class="total-count">Total <strong id="totalKlienCount">0</strong> klien terdaftar</p>
            <div class="client-list" id="totalKlienList">
                <div class="loading-text">Loading data...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-tutup" data-popup="popupTotalKlien">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Klien Aktif -->
<div id="popupKlienAktif" class="modal-popup">
    <div class="modal-content modal-aktif">
        <div class="modal-header">
            <div class="header-title">
                <img src="{{ asset('img/upline.png') }}" class="modal-icon" alt="Klien Aktif">
                <h2>Klien Aktif</h2>
            </div>
            <span class="close-popup" data-popup="popupKlienAktif">&times;</span>
        </div>
        <div class="modal-body">
            <p class="total-count"><strong id="aktifCount">0</strong> klien dengan langganan aktif</p>
            <div class="client-list" id="klienAktifList">
                <div class="loading-text">Loading data...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-tutup" data-popup="popupKlienAktif">Tutup</button>
        </div>
    </div>
</div>

<!-- Modal Klien Tidak Aktif -->
<div id="popupTidakAktif" class="modal-popup">
    <div class="modal-content modal-tidak-aktif">
        <div class="modal-header">
            <div class="header-title">
                <img src="{{ asset('img/tanggal.png') }}" class="modal-icon" alt="Klien Tidak Aktif">
                <h2>Klien Tidak Aktif</h2>
            </div>
            <span class="close-popup" data-popup="popupTidakAktif">&times;</span>
        </div>
        <div class="modal-body">
            <p class="total-count"><strong id="tidakAktifCount">0</strong> klien dengan langganan berakhir</p>
            <div class="client-list" id="tidakAktifList">
                <div class="loading-text">Loading data...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-tutup" data-popup="popupTidakAktif">Tutup</button>
        </div>
    </div>
</div>



<!-- Modal Total Pendapatan -->
<div id="popupTotalPendapatan" class="modal-popup">
    <div class="modal-content modal-pendapatan">
        <div class="modal-header">
            <div class="header-title">
                <img src="{{ asset('img/cash.png') }}" class="modal-icon" alt="Total Pendapatan">
                <h2>Total Pendapatan</h2>
            </div>
            <span class="close-popup" data-popup="popupTotalPendapatan">&times;</span>
        </div>
        <div class="modal-body">
            <p class="total-count">Total pendapatan: <strong id="totalPendapatanValue">Rp 0</strong></p>
            <div class="client-list" id="pendapatanList">
                <div class="loading-text">Loading data...</div>
            </div>
            <div class="grand-total">
                <strong>Total Keseluruhan: <span id="grandTotalPendapatan">Rp 0</span></strong>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn-tutup" data-popup="popupTotalPendapatan">Tutup</button>
        </div>
    </div>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>