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

    <div class="header">
        <div>
            <h1 class="title">Manajemen Klien</h1>
            <p class="subtitle">Kelola dan Pantau Klien</p>
        </div>

<button id="open-add" class="buttonadd">Tambahkan Klien</button>
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
                <button type="submit" class="submit-btn">Tambah Klien</button>
                <button type="button" class="cancel-btn" id="closeadd">Batal</button>
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
            <span class="close-x">&times;</span>
        </div>

        <!-- STATUS -->
        <div class="status">Aktif</div>

        <!-- INFO GRID -->
        <div class="lihat-grid">
            <div class="card">
                <span>Email</span>
                <strong id="lihatEmail">-</strong>
            </div>

            <div class="card">
                <span>Perusahaan</span>
                <strong id="lihatPerusahaan2">-</strong>
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
        <button class="btn-tutup close">Tutup</button>

    </div>
</div>

<!-- ================= POPUP EDIT ================= -->
<div class="add" id="popupEdit">
    <div class="addklien">
        <h2>Edit Klien</h2>
        <p>Form edit nanti di sini</p>
        <button class="close">Tutup</button>
    </div>
</div>

<!-- ================= POPUP HAPUS ================= -->
<div class="add" id="popupHapus">
    <div class="addklien">
        <h2>Hapus Klien</h2>
        <p>Yakin mau hapus?</p>
        <button>Ya</button>
        <button class="close">Batal</button>
    </div>
</div>

        

    
</div>




<br>
<div class="search">

<input type="text" class="search2" placeholder="Cari Klien Berdasarkan Nama, Email Atau Perusahaan">

<div class="view-buttons">
    <button onclick="showTable()" class="view-button">Tampilan Tabel</button>
    <button onclick="showCard()" class="view-button">Tampilan Kartu</button>
</div>
</div>
<!-- Tampilan tabel -->
<table class="table">
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
    <tbody>
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
    </tbody>
</table>

</div>
</body>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="js/klien.js"></script>
</html>
