<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/klien.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Dashboard</title>
    <style>

</style>
@php
    $user = Auth::user();
@endphp

<div class="user-icon">
    {{ strtoupper(substr($user->name ?? 'G', 0, 1)) }}
</div>

<div class="user-name">
    {{ $user->name ?? 'Guest User' }}
</div>

<div class="user-role">
    {{ strtoupper($user->role ?? 'guest') }}
</div>
</head>
<body>
    @extends('layouts.app')

    @section('content')
    <div class="panel">
        <img src="{{ asset('img/logos.png') }}" alt="" class="logo">
        <h1>Admin Panel</h1>
        <p>Manajemen Klien</p>

<div class="menu">

<a class="menu-btn {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
    <img src="{{ asset('img/menu.png') }}">
    Dashboard
</a>

<a class="menu-btn {{ request()->is('klien') ? 'active' : '' }}" href="{{ route('klien') }}">
    <img src="{{ asset('img/klien.png') }}">
    Klien
</a>

<a class="menu-btn {{ request()->is('analitik') ? 'active' : '' }}" href="{{ route('analitik') }}">
    <img src="{{ asset('img/analis.png') }}">
    Analistik
</a>

<hr class="hr" style="margin-top: 5px">

<a class="menu-btn {{ request()->is('bantuan') ? 'active' : '' }}" href="{{ route('help') }}">
    <img src="{{ asset('img/help.png') }}">
    Bantuan
</a>
<hr class="hr" style="margin-top: 5px">

<div class="user-card">

    <div class="user-icon">
        {{ strtoupper(substr($user->name ?? 'G', 0, 1)) }}
    </div>

    <div class="user-info">
        <div class="user-name">
            {{ $user->name ?? 'Guest User' }}
        </div>

        <div class="user-role">
            {{ strtoupper($user->role ?? 'guest') }}
        </div>

        <div class="user-email">
            {{ $user->email ?? 'guest@email.com' }}
        </div>
    </div>

</div>


    @csrf
  <button class="menu-lgt menu-bottom2" onclick="window.location.href='/'">
    <img src="{{ asset('img/Logout.png') }}">
    Log Out
</button>
</form>

</div>

 </div>

  <div class="main">

    <div class="header">
        <div>
            <h1 class="title">Manajemen Klien</h1>
            <p class="subtitle">Kelola dan Pantau Klien</p>
        </div>

<button class="buttonadd open-add" data-type="tambah">
    Tambahkan Klien
</button>
</div>

<!-- ================= POPUP TAMBAH ================= -->
<div class="add" id="popupTambah">
    <div class="addklien">

        <div class="modal-header">
            <h2>Tambah Klien Baru</h2>
        </div>

        <form id="formKlien">
            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="nama" placeholder="Contoh: Ahmad Rizki" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="emaik" placeholder="email@perusahaan.com" required>
                </div>

                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" id="perusahaan" placeholder="Nama Perusahaan" required>
                </div>

                <div class="form-group">
                    <label>Total Pendapatan (Rp)</label>
                    <input type="number" id="pendapatan" value="0">
                </div>

                <div class="form-group">
                    <label>Mulai Langganan</label>
                    <input type="date" id="mulai" required>
                </div>

                <div class="form-group">
                    <label>Berakhir Langganan</label>
                    <input type="date" id="akhir" required>
                </div>

            <div class="form-group full-width">
                <label>Nomer Telepon</label>
                <input type="text" id="nomer" placeholder="08xxxxxx" required>
            </div>

            </div>

            <div class="info-box">
                💡 Tip: Masa langganan standar adalah 1 tahun (365 hari).
            </div>

            <div class="form-action">
                <button type="submit" class="submit-btn">Tambah Klien</button>
                <button class="btn-tutup close">Tutup</button>
            </div>
        </form>
    </div>
</div>
  </div>
  
    </div>
</div>

<!-- ================= POPUP LIHAT ================= -->
<div class="add" id="popupLihat">
    <div class="addklien lihat-popup-modern">

        <!-- HEADER -->
        <div class="lihat-header">
            <div class="avatar-table">AR</div>
            <div>
                <h3 id="lihatNama">Ahmad Rizki</h3>
                <p id="lihatPerusahaan">TechCorp Indonesia</p>
            </div>
            
        </div>

        <!-- STATUS -->
        <div class="status aktif2">Aktif</div>

        <!-- INFO GRID -->
        <div class="lihat-grid">
            <div class="card">
                <span>Email</span>
                <strong id="lihatEmail">ahmad.rizki@techcorp.co.id</strong>
            </div>

            <div class="card">
                <span>Perusahaan</span>
                <strong id="lihatPerusahaan2">TechCorp Indonesia</strong>
            </div>

            <div class="card">
                <span>Total Pendapatan</span>
                <strong>Rp 45.000.000</strong>
            </div>

            <div class="card">
                <span>ID Klien</span>
                <strong>#1</strong>
            </div>
        </div>

        <!-- LANGGANAN -->
        <div class="langganan">
            <h4>Detail Langganan</h4>
            <div class="langganan-grid">
                <div>
                    <span>Mulai</span>
                    <strong>23 Nov 2025</strong>
                </div>
                <div>
                    <span>Berakhir</span>
                    <strong>23 Nov 2026</strong>
                </div>
                <div>
                    <span>Durasi</span>
                    <strong>365 hari</strong>
                </div>
                <div>
                    <span>Sisa</span>
                    <strong class="green">245 hari lagi</strong>
                </div>
            </div>
        </div>

        <!-- BUTTON -->
        <button class="btn-tutup2 close">Tutup</button>

    </div>
</div>

<!-- ================= POPUP EDIT ================= -->
<div class="add" id="popupEdit">
    
        <div class="addklien">

        <div class="modal-header">
            <h2>Tambah Klien Baru</h2>
        </div>

        <form id="formKlien">
            <div class="form-grid">

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" placeholder="Contoh: Ahmad Rizki" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" placeholder="email@perusahaan.com" required>
                </div>

                <div class="form-group">
                    <label>Perusahaan</label>
                    <input type="text" placeholder="Nama Perusahaan" required>
                </div>

                <div class="form-group">
                    <label>Total Pendapatan (Rp)</label>
                    <input type="number" value="0">
                </div>

                <div class="form-group">
                    <label>Mulai Langganan</label>
                    <input type="date" required>
                </div>

                <div class="form-group">
                    <label>Berakhir Langganan</label>
                    <input type="date" required>
                </div>

            </div>

            <div class="info-box">
                💡 Tip: Masa langganan standar adalah 1 tahun (365 hari).
            </div>

            <div class="form-action">
                <button type="submit" class="simpan-btn">Simpan</button>
                <button class="btn-tutup close">Tutup</button>
            </div>
        </form>
    </div>
</div>

  </div>
        
    </div>
</div>

<!-- ================= POPUP HAPUS ================= -->
<div class="add" id="popupHapus">
    <div class="hapusklien">
        <br>
        <div class="header-konfirmasi">
    <img src="{{ asset('img/peringatan.png') }}" class="peringatan" alt="">
    <h2>Konfirmasi Hapus</h2>
</div>
        <br>
        <hr>
        <p style="font-size: 18px">Apakah Anda Yakin Untuk Menghapus Klien Berikut?</p>
        
        <p style="font-size: 18px">"Ahmad Rizki"</p>
            <div class="danger-box">
                ⚠️Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data terkait klien ini akan dihapus secara permanen.
            </div>
        
        <br>
        

        <button class="hapusred">Ya, Hapus</button>
        <button class="close2">Batal</button>
    </div>
</div>

        

    
</div>




<br>
<div class="search">

<input type="text" id="searchInput" class="search2" placeholder="Cari Klien Berdasarkan Nama, Email Atau Perusahaan">

<div class="view-buttons">
    <button onclick="showTable()" class="view-button">Tampilan Tabel</button>
    <button onclick="showCard()" class="view-button">Tampilan Kartu</button>
</div>
<div class="dropdown">
  <button onclick="toggleDropdown()" class="dropbtn">Filter Status</button>

  <div id="myDropdown" class="dropdown-content">
    <a href="#" onclick="filterStatus('all')">Semua</a>
    <a href="#" onclick="filterStatus('aktif')">Aktif</a>
    <a href="#" onclick="filterStatus('tidak')">Tidak Aktif</a>
  </div>
</div>
</div>
</div>
<!-- Tampilan tabel -->
<table class="table" id="tableView">
    <thead>
        <tr>
            <th>KLIEN</th>
            <th>PERUSAHAAN</th>
            <th>EMAIL</th>
            <th>MASA BERAKHIR</th>
            <th>STATUS</th>
            <th>PENDAPATAN</th>
            <th>AKSI</th>
        </tr>
    </thead>
    <tbody id="tableBody">
        <tr>
            <td class="klien">
                <div class="avatar">AR</div>
                <span>Ahmad Rizki</span>
            </td>
            <td>TechCorp Indonesia</td>
            <td>ahmad.rizki@techcorp.co.id</td>
            <td>23 Nov 2026</td>
            <td>
                <span class="status aktif">✔ Aktif</span>
            </td>
            <td>Rp 45.000.000</td>
            <td>
    <div class="aksi">
    <span class="icon open-add" data-type="lihat">👁️</span>
<span class="icon open-add" data-type="edit">✏️</span>
<span class="icon delete open-add" data-type="hapus">🗑️</span>
</div>
</td>


        </tr>

                <tr>
            <td class="klien">
                <div class="avatar">HS</div>
                <span>Hendra Sumanto</span>
            </td>
            <td>TechCorp Indonesia</td>
            <td>ahmad.rizki@techcorp.co.id</td>
            <td>23 Nov 2026</td>
            <td>
                <span class="status aktif">✔ Aktif</span>
            </td>
            <td>Rp 45.000.000</td>
            <td>
    <div class="aksi">
<span class="icon open-add" data-type="lihat">👁️</span>
<span class="icon open-add" data-type="edit">✏️</span>
<span class="icon delete open-add" data-type="hapus">🗑️</span>
</div>
</td>


        </tr>

                <tr>
            <td class="klien">
                <div class="avatar">MS</div>
                <span>Made Sukawati</span>
            </td>
            <td>TechCorp Indonesia</td>
            <td>ahmad.rizki@techcorp.co.id</td>
            <td>23 Nov 2026</td>
            <td>
                <span class="status aktif">✔ Aktif</span>
            </td>
            <td>Rp 45.000.000</td>
            <td>
    <div class="aksi">
    <span class="icon open-add" data-type="lihat">👁️</span>
<span class="icon open-add" data-type="edit">✏️</span>
<span class="icon delete open-add" data-type="hapus">🗑️</span>
</div>
</td>


        </tr>

                <tr>
            <td class="klien">
                <div class="avatar">RR</div>
                <span>Rasyah Rasyid</span>
            </td>
            <td>TechCorp Indonesia</td>
            <td>ahmad.rizki@techcorp.co.id</td>
            <td>23 Nov 2026</td>
            <td>
                <span class="status aktif">✔ Aktif</span>
            </td>
            <td>Rp 45.000.000</td>
            <td>
    <div class="aksi">
   <span class="icon open-add" data-type="lihat">👁️</span>
<span class="icon open-add" data-type="edit">✏️</span>
<span class="icon delete open-add" data-type="hapus">🗑️</span>
    </div>
</td>


        </tr>
    </tbody >
</table>

</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/klien.js"></script>
</html>
