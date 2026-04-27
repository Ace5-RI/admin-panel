<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/aktivitas.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <title>Aktivitas - Admin Panel</title>
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

        

        <hr class="hr" style="margin-top: 5px">

        <a class="menu-btn {{ request()->is('bantuan') ? 'active' : '' }}" href="/aktivitas">
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
    <div class="page-header">
        <h1 class="page-title">Log Aktivitas</h1>
        <p class="page-subtitle">Pantau semua aktivitas yang terjadi di sistem</p>
        
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card login">
            <div class="stat-info">
                <h4>Total Login Dan Logout</h4>
                <div class="stat-number" id="totalLogin">0</div>
            </div>
            <div class="stat-icon">🔐</div>
        </div>
        <div class="stat-card invoice">
            <div class="stat-info">
                <h4>Invoice Dibuat</h4>
                <div class="stat-number" id="totalInvoice">0</div>
            </div>
            <div class="stat-icon">📄</div>
        </div>
        <div class="stat-card edit">
            <div class="stat-info">
                <h4>Data Diubah</h4>
                <div class="stat-number" id="totalEdit">0</div>
            </div>
            <div class="stat-icon">✏️</div>
        </div>
        <div class="stat-card payment">
            <div class="stat-info">
                <h4>Pembayaran</h4>
                <div class="stat-number" id="totalPayment">0</div>
            </div>
            <div class="stat-icon">💳</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filter-section">
        <div class="filter-title">📌 Filter Aktivitas</div>
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">📋 Semua</button>
            <button class="filter-btn" data-filter="login">🔐 Login Dan Logout</button>
            <button class="filter-btn" data-filter="invoice">📄 Invoice</button>
            <button class="filter-btn" data-filter="payment">💳 Pembayaran</button>
            <button class="filter-btn" data-filter="edit">✏️ Edit</button>
            <button class="filter-btn" data-filter="create">➕ Tambah</button>
            <button class="filter-btn" data-filter="delete">🗑️ Hapus</button>
        </div>
    </div>

    <!-- Timeline -->
    <div class="activity-timeline">
        <div class="timeline-header">
            <h3>📋 Timeline Aktivitas</h3>
            <div class="timeline-count" id="activityCount">0 aktivitas</div>
        </div>
        <div class="timeline-items" id="activityList">
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <h4>Belum Ada Aktivitas</h4>
                <p>Aktivitas akan muncul disini setelah ada kegiatan di sistem</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/aktivitas.js') }}"></script>
</body>
</html>