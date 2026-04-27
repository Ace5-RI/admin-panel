<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/klien.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <title>Dashboard</title>
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

        <a class="menu-btn {{ request()->is('aktivitas') ? 'active' : '' }}" href="/aktivitas">
    <img src="{{ asset('img/help.png') }}"> Aktivitas
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

<div class="main" style="margin-top: 50px">
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
            <form method="POST" action="{{ route('klien.store') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" id="nama" placeholder="Contoh: Ahmad Rizki" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" placeholder="email@perusahaan.com" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" name="perusahaan" id="perusahaan" placeholder="Nama Perusahaan" required>
                    </div>
                    <div class="form-group">
                        <label>Total Pendapatan (Rp)</label>
                        <input type="text" name="pendapatan" id="pendapatan" placeholder="1.000.000" value="0">
                    </div>
                    <div class="form-group full-width">
                        <label>Langganan</label>
                        <div class="row-date">
                            <input type="date" name="mulai" id="mulaiTambah">
                            <input type="date" name="akhir" id="akhirTambah">
                            <select id="durasiTambah">
                                <option value="">Pilih Durasi</option>
                                <option value="1">1 Tahun</option>
                                <option value="2">2 Tahun</option>
                                <option value="3">3 Tahun</option>
                                <option value="4">4 Tahun</option>
                                <option value="5">5 Tahun</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group full-width">
                        <label>Nomer Telepon</label>
                        <input type="text" name="nomer" id="nomer" placeholder="08xxxxxx" required>
                    </div>
                </div>
                <div class="info-box">
                    💡 Tip: Masa langganan standar adalah 1 tahun (365 hari).
                </div>
                <div class="form-action">
                    <button type="submit" class="submit-btn">Tambah Klien</button>
                    <button type="button" class="btn-tutup close">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= POPUP LIHAT ================= -->
    <div class="add" id="popupLihat">
        <div class="addklien lihat-popup-modern">
            <div class="lihat-header">
                <div class="avatar-table" id="lihatAvatar">AR</div>
                <h3 id="lihatNama">Ahmad Rizki</h3>
                <p id="lihatEmail">ahmad.rizki@techcorp.co.id</p>
                <div class="status aktif2" id="lihatStatus">Aktif</div>
            </div>

            <div class="lihat-grid">
                <div class="card">
                    <span>Perusahaan</span>
                    <strong id="lihatPerusahaan">-</strong>
                </div>
                <div class="card">
                    <span>No. Telepon</span>
                    <strong id="lihatPhone">-</strong>
                </div>
                <div class="card">
                    <span>Total Pendapatan</span>
                    <strong id="lihatPendapatan">-</strong>
                </div>
                <div class="card">
                    <span>Mulai Langganan</span>
                    <strong id="lihatMulai">-</strong>
                </div>
                <div class="card">
                    <span>Berakhir Langganan</span>
                    <strong id="lihatAkhir">-</strong>
                </div>
                <div class="card">
                    <span>Durasi</span>
                    <strong id="lihatDurasi">-</strong>
                </div>
                <div class="card">
                    <span>Sisa Waktu</span>
                    <strong id="lihatSisa" class="green">-</strong>
                </div>
            </div>

            <button class="btn-tutup2 close">Tutup</button>
        </div>
    </div>

    <!-- ================= POPUP EDIT ================= -->
    <div class="add" id="popupEdit">
        <div class="addklien">
            <h2>Edit Klien</h2>
            <form id="formEditKlien">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" id="editNama" placeholder="Contoh: Ahmad Rizki" required>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" id="editEmail" placeholder="email@perusahaan.com" required>
                    </div>
                    <div class="form-group">
                        <label>Perusahaan</label>
                        <input type="text" id="editPerusahaan" placeholder="Nama Perusahaan" required>
                    </div>
                    <div class="form-group">
                        <label>No. Telepon</label>
                        <input type="text" id="editNomer" placeholder="08xxxxxx" required>
                    </div>
                    <div class="form-group">
                        <label>Total Pendapatan (Rp)</label>
                        <input type="text" id="editPendapatan" value="0">
                    </div>
                    <div class="form-group full-width">
                        <label>Masa Langganan</label>
                        <div class="row-date">
                            <input type="date" id="editMulai" readonly style="background:#f5f5f5">
                            <input type="date" id="editAkhir">
                            <select id="editDurasi">
                                <option value="">Pilih Durasi</option>
                                <option value="1">1 Tahun</option>
                                <option value="2">2 Tahun</option>
                                <option value="3">3 Tahun</option>
                                <option value="4">4 Tahun</option>
                                <option value="5">5 Tahun</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="info-box">
                    💡 Tip: Masa langganan standar adalah 1 tahun (365 hari).
                </div>
                <div class="form-action">
                    <button type="button" id="btnUpdate" class="simpan-btn">Update</button>
                    <button class="btn-tutup close">Tutup</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= POPUP HAPUS ================= -->
    <div class="add" id="popupHapus">
        <div class="hapusklien">
            <div class="header-konfirmasi">
                <img src="{{ asset('img/peringatan.png') }}" class="peringatan" alt="">
                <h2>Konfirmasi Hapus</h2>
            </div>
            <hr>
            <p style="font-size: 18px">Apakah Anda Yakin Untuk Menghapus Klien Berikut?</p>
            <p style="font-size: 18px" class="nama-klien">"Ahmad Rizki"</p>
            <div class="danger-box">
                ⚠️ Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data terkait klien ini akan dihapus secara permanen.
            </div>
            <button class="hapusred">Ya, Hapus</button>
            <button class="close2">Batal</button>
        </div>
    </div>

    <!-- ================= SEARCH & FILTER ================= -->
    <div class="search">
        <input type="text" id="searchInput" class="search2" placeholder="Cari Klien Berdasarkan Nama, Email Atau Perusahaan">
        
        <div class="dropdown-group">
            <div class="dropdown">
                <button onclick="toggleDropdown()" class="dropbtn">Filter Status ▼</button>
                <div id="myDropdown" class="dropdown-content">
                    <a href="#" onclick="filterStatus('all')">Semua</a>
                    <a href="#" onclick="filterStatus('aktif')">Aktif</a>
                    <a href="#" onclick="filterStatus('akan_berakhir')">Akan Berakhir</a>
                    <a href="#" onclick="filterStatus('berakhir')">Berakhir</a>
                </div>
            </div>

            <div class="dropdown">
                <button onclick="toggleSortDropdown()" class="dropbtn">Urutkan ▼</button>
                <div id="sortDropdown" class="dropdown-content">
                    <a href="#" onclick="sortTable('tercepat')">Paling Cepat</a>
                    <a href="#" onclick="sortTable('terlama')">Paling Lama</a>
                </div>
            </div>
        </div>
    </div>

    <!-- ================= TABEL ================= -->
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
            @foreach($clients as $client)
            <tr 
                data-id="{{ $client->id }}"
                data-phone="{{ $client->phone_number }}"
                data-mulai="{{ \Carbon\Carbon::parse($client->subscription_start_date)->format('Y-m-d') }}"
                data-akhir="{{ \Carbon\Carbon::parse($client->subscription_end_date)->format('Y-m-d') }}"
                data-pendapatan="{{ $client->revenue }}"
                data-status="{{ $client->status }}">
                <td class="klien">
                    <div class="avatar">{{ strtoupper(substr($client->name, 0, 2)) }}</div>
                    <span>{{ $client->name }}</span>
                </td>
                <td>{{ $client->company }}</td>
                <td>{{ $client->email }}</td>
                <td>{{ date('d M Y', strtotime($client->subscription_end_date)) }}</td>
                <td class="status-cell" data-end-date="{{ $client->subscription_end_date }}">
                    <!-- Status akan diisi oleh JS -->
                    <span class="status status-loading">Loading...</span>
                </td>
                <td>Rp {{ number_format($client->revenue, 0, ',', '.') }}</td>
                <td>
                    <div class="aksi">
                        <span class="icon lihat">👁️</span>
                        <span class="icon edit">✏️</span>
                        <span class="icon delete">🗑️</span>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="{{ asset('js/klien.js') }}"></script>
</body>
</html>