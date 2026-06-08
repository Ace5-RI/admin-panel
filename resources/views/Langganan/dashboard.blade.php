<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title>Dashboard</title>
    <base href="/">
    <script>
        window.companyName = "{{ \App\Models\Setting::get('company_name', 'Bali Solution Biz') }}";
    </script>

</head>


<body>
    <div class="panel">
        <img src="{{ \App\Models\Setting::get('company_logo', '/img/logos.png') }}" alt="Logo" class="logo"
            id="sidebarLogo" onerror="this.src='/img/logos.png'">
        <h1>Admin Panel</h1>
        <p>Manajemen Klien</p>

        <div class="menu">
            @if (session('success'))
                <script>
                    alert("{{ session('success') }}");
                </script>
            @endif

            <a class="menu-btn {{ request()->is('dashboard') ? 'active' : '' }}" href="/dashboard">
                <img src="{{ asset('img/menu.png') }}">Dashboard
            </a>

            <a class="menu-btn {{ request()->is('klien') ? 'active' : '' }}" href="/klien">
                <img src="{{ asset('img/klien.png') }}">Klien
            </a>


            <a class="menu-btn {{ request()->is('settings') ? 'active' : '' }}" href="/settings">
                <img src="{{ asset('img/setting.png') }}"> Pengaturan
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

    <div class="main">
        <h1 class="title">Dashboard</h1>
        <p class="subtitle">Selamat Datang Kembali</p>

        <div class="container">
            <div class="total">
                <h4>Total Klien</h4>
                <img src="{{ asset('img/profile.png') }}" class="profile" alt="">
                <h1 id="totalClient"></h1>
            </div>

            <div class="total">
                <h4>Klien Aktif</h4>
                <img src="{{ asset('img/upline.png') }}" class="aktif" alt="">
                <h1 id="activeClient"></h1>
            </div>

            <div class="total">
                <h4>Tidak Aktif</h4>
                <img src="{{ asset('img/dangers.png') }}" class="tanggal" alt="">
                <h1 id="inactiveClient"></h1>
            </div>

            <div class="total">
                <h4>Total Pendapatan</h4>
                <img src="{{ asset('img/cash.png') }}" class="keuangan" alt="">
                <h1 id="totalRevenue"></h1>
            </div>
        </div>

        <!-- Tombol Ganti Tahun -->
        <div class="year-selector">
            <button id="prevYearBtn" class="year-nav">◀</button>
            <span id="currentYear" class="current-year">2026</span>
            <button id="nextYearBtn" class="year-nav">▶</button>
        </div>

        <div class="container2">
<<<<<<< HEAD
            <div class="table">
                <h4 class="table-txt"> Tren Klien Aktif</h4>
                <canvas id="userChart"></canvas>

            </div>
            <div class="table">
                <h4 class="table-txt"> Tren Klien Baru</h4>
                <canvas id="activityChart"></canvas>

            </div>
        </div>
=======
    <div class="table">
        <h4 class="table-txt"> Tren Klien Aktif</h4>
        <canvas id="userChart"></canvas>
        
    </div>
    <div class="table">
        <h4 class="table-txt"> Tren Klien Baru</h4>
        <canvas id="activityChart"></canvas>
       
    </div>
</div>
>>>>>>> 37567aae6bff884bf46c6c95873323d38a9678b3
    </div>

    <div id="warningContainer" class="warning-grid"></div>

    <div class="container4" id="cardsContainer">
        <!-- User cards akan di-render JS -->
        <!-- ================= POPUP HAPUS WARNING (COPY DARI HALAMAN KLIEN) ================= -->

    </div>

    <div class="add" id="popupHapusWarning">
<<<<<<< HEAD
        <div class="hapusklien">
            <div class="header-konfirmasi">
                <img src="{{ asset('img/peringatan.png') }}" class="peringatan" alt="">
                <h2>Konfirmasi Hapus</h2>
=======
    <div class="hapusklien">
        <div class="header-konfirmasi">
            <img src="{{ asset('img/peringatan.png') }}" class="peringatan" alt="">
            <h2>Konfirmasi Hapus</h2>
        </div>
        <hr>
        <p style="font-size: 18px">Apakah Anda Yakin Untuk Menghapus Klien Berikut?</p>
        <p style="font-size: 18px" class="nama-klien-warning">"Client"</p>
        <div class="danger-box">
             Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data terkait klien ini akan dihapus secara permanen.
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
>>>>>>> 37567aae6bff884bf46c6c95873323d38a9678b3
            </div>
            <hr>
            <p style="font-size: 18px">Apakah Anda Yakin Untuk Menghapus Klien Berikut?</p>
            <p style="font-size: 18px" class="nama-klien-warning">"Client"</p>
            <div class="danger-box">
                Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data terkait klien ini akan dihapus secara
                permanen.
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
                    <img src="{{ asset('img/dangers.png') }}" class="modal-icon" alt="Klien Tidak Aktif">
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
                <!-- SELECTOR TAHUN -->
                <div class="tahun-selector">
                    <button type="button" onclick="changeRevenueYear('prev')" class="tahun-nav">◀</button>
                    <select id="tahunPendapatanSelect" onchange="setRevenueYear()">
                        <option value="">Loading...</option>
                    </select>
                    <button type="button" onclick="changeRevenueYear('next')" class="tahun-nav">▶</button>
                </div>

                <div class="grand-total">
                    <span>Total Pendapatan <span id="tahunPendapatanJudul">Tahun <?php echo date('Y'); ?></span> :</span>
                    <strong id="grandTotalPendapatan">Rp 0</strong>
                </div>

                <p class="total-count">Rincian pendapatan per klien:</p>
                <div class="client-list" id="pendapatanList">
                    <div class="loading-text">Loading data...</div>
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
    <!-- Profile Button sudah ada di sidebar (.user-card) -->
<!-- Yang perlu ditambahkan adalah modal HTML untuk profile management -->

<!-- Profile Management Modal -->
<div id="profileManagementModal" class="profile-management-modal">
    <div class="profile-management-overlay"></div>
    <div class="profile-management-container">
        <div class="profile-management-header">
            <div class="profile-header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <h3>Akun Saya</h3>
            <button class="profile-management-close" id="closeProfileModalBtn">&times;</button>
        </div>
        <div class="profile-management-body">
            <div class="profile-avatar-section">
                <div class="profile-avatar-circle" id="profileAvatarCircle">A</div>
                <div class="profile-role-badge" id="profileRoleBadge">ADMIN</div>
            </div>
            
            <form id="profileEditForm" class="profile-edit-form">
                <div class="profile-form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" id="editUserName" name="name" required placeholder="Masukkan nama lengkap">
                </div>
                <div class="profile-form-group">
                    <label>Email</label>
                    <input type="email" id="editUserEmail" name="email" required placeholder="Masukkan email">
                </div>
                <div class="profile-form-group">
                    <label>Role / Jabatan</label>
                    <input type="text" id="editUserRole" name="role" placeholder="Contoh: ADMIN, MANAGER">
                </div>
                <div class="profile-form-group">
                    <label>Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" id="editUserPassword" name="password" placeholder="Password baru">
                </div>
                <div class="profile-form-group">
                    <label>Konfirmasi Password Baru</label>
                    <input type="password" id="editUserPasswordConfirm" placeholder="Konfirmasi password baru">
                </div>
                
                <div class="profile-action-buttons">
                    <button type="submit" class="profile-save-btn" id="profileSaveBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path>
                            <polyline points="17 21 17 13 7 13 7 21"></polyline>
                            <polyline points="7 3 7 8 15 8"></polyline>
                        </svg>
                        Simpan Perubahan
                    </button>
                    <button type="button" class="profile-delete-btn" id="profileDeleteAccountBtn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
        <div class="profile-management-footer">
            <button class="profile-cancel-btn" id="cancelProfileBtn">Batal</button>
        </div>
    </div>
</div>

<!-- Delete Account Confirmation Modal -->
<div id="deleteAccountModal" class="profile-management-modal">
    <div class="profile-management-overlay"></div>
    <div class="profile-management-container" style="max-width: 400px;">
        <div class="profile-management-header" style="background: linear-gradient(135deg, #991b1b, #7f1d1d);">
            <div class="profile-header-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                </svg>
            </div>
            <h3>Hapus Akun</h3>
            <button class="profile-management-close" id="closeDeleteModalBtn">&times;</button>
        </div>
        <div class="profile-management-body" style="text-align: center;">
            <p style="font-size: 16px; margin-bottom: 20px;">Apakah Anda yakin ingin menghapus akun ini?</p>
            <div style="background: #fee2e2; padding: 15px; border-radius: 12px; margin-bottom: 20px; color: #991b1b;">
                ⚠️ Peringatan: Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus secara permanen.
            </div>
            <div class="profile-action-buttons">
                <button type="button" class="profile-cancel-btn" id="cancelDeleteBtn" style="flex: 1;">Batal</button>
                <button type="button" class="profile-confirm-delete-btn" id="confirmDeleteAccountBtn" style="flex: 1;">Ya, Hapus</button>
            </div>
        </div>
    </div>
</div>
    <script>
        // Update logo sidebar dari database
        async function updateSidebarLogo() {
            try {
                const response = await fetch('/api/settings');
                const settings = await response.json();
                const sidebarLogo = document.getElementById('sidebarLogo');
                if (sidebarLogo && settings.company_logo) {
                    sidebarLogo.src = settings.company_logo + '?t=' + Date.now();
                }
                // Update juga variable global
                window.companyName = settings.company_name;
                window.companyLogo = settings.company_logo;
            } catch (error) {
                console.error('Gagal update logo:', error);
            }
        }
        updateSidebarLogo();
    </script>
    <script src="{{ asset('js/profile-popup.js') }}"></script>
</body>
<<<<<<< HEAD

=======
>>>>>>> 37567aae6bff884bf46c6c95873323d38a9678b3
</html>
