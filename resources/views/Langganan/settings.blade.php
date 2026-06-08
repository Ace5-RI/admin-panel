<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    
    <title>Pengaturan Perusahaan</title>
    
    <base href="/">
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}">
    
    <script>
        window.companyName = "{{ \App\Models\Setting::get('company_name', 'Bali Solution Biz') }}";
    </script>
</head>
<body>

<div class="panel">
    <div class="sidebar-header">
        <img src="{{ \App\Models\Setting::get('company_logo', '/img/logos.png') }}" alt="Logo" class="logo" id="sidebarLogo" onerror="this.src='/img/logos.png'">
        <h1>Admin Panel</h1>
        <p>Manajemen Klien</p>
    </div>

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

        <a class="menu-btn {{ request()->is('settings') ? 'active' : '' }}" href="/settings">
            <img src="{{ asset('img/setting.png') }}"> Pengaturan
        </a>

        <hr class="hr">

        <a class="menu-btn {{ request()->is('aktivitas') ? 'active' : '' }}" href="/aktivitas">
            <img src="{{ asset('img/help.png') }}"> Aktivitas
        </a>
        <hr class="hr">

        <div class="user-card">
            <div class="user-icon" id="avatarSidebar">A</div>
            <div class="user-info">
                <div class="user-name" id="userNameSidebar">Admin User</div>
                <div class="user-role" id="userRoleSidebar">ADMIN</div>
                <div class="user-email" id="userEmailSidebar">admin@adminportal.com</div>
            </div>
        </div>

        <button class="menu-lgt menu-bottom2" id="logoutBtn">
            <img src="{{ asset('img/Logout.png') }}"> Log Out
        </button>
    </div>
</div>

<!-- MAIN CONTENT SETTINGS -->
<div class="main-content">
    <div class="settings-container">
        <div class="settings-header">
            <h1> Pengaturan Perusahaan</h1>
            <p>Kelola informasi perusahaan, logo, dan data bank yang akan muncul di seluruh sistem</p>
        </div>

        <div class="settings-grid">
            <!-- FORM KIRI -->
            <div class="settings-card">
                <div class="card-header">
                    <h2> Informasi Perusahaan</h2>
                </div>
                <div class="card-body">
                    <div id="alertMessage"></div>

                    <!-- Logo Upload -->
                    <div class="form-group">
                        <label>Logo Perusahaan</label>
                        <div class="logo-upload-area" id="logoUploadArea">
                            <div class="logo-preview" id="logoPreview">
                                <span class="no-logo"></span>
                            </div>
                            <div class="upload-icon"></div>
                            <div class="upload-text">
                                <strong>Klik untuk upload</strong><br>
                                JPG, PNG, SVG (Max. 2MB)
                            </div>
                        </div>
                        <input type="file" id="logoInput" accept="image/jpeg,image/png,image/svg+xml" class="hidden-input">
                    </div>

                    <!-- Nama Perusahaan -->
                    <div class="form-group">
                        <label>Nama Perusahaan <span class="required">*</span></label>
                        <input type="text" id="companyName" placeholder="Contoh: PT. Maju Bersama">
                    </div>

                    <!-- Alamat -->
                    <div class="form-group">
                        <label>Alamat Lengkap</label>
                        <textarea id="companyAddress" placeholder="Jl. Contoh No. 123, Kota, Provinsi"></textarea>
                    </div>

                    <!-- Kontak -->
                    <div class="form-group">
                        <label>Nomor Telepon</label>
                        <input type="text" id="companyPhone" placeholder="+62 812-3456-7890">
                    </div>

                    <div class="form-group">
                        <label>Email Perusahaan</label>
                        <input type="email" id="companyEmail" placeholder="info@perusahaan.com">
                    </div>

                    <!-- Bank Section -->
                    <div style="margin-top: 32px;">
                        <div class="card-header" style="padding: 0 0 16px 0; background: none;">
                            <h2 style="font-size: 16px;">🏦 Informasi Rekening Bank</h2>
                        </div>

                        <div class="bank-section">
                            <h3>🟢 Bank BCA</h3>
                            <input type="text" id="bankBCA" placeholder="Nomor Rekening BCA">
                        </div>

                        <div class="bank-section">
                            <h3>🔴 Bank Mandiri</h3>
                            <input type="text" id="bankMandiri" placeholder="Nomor Rekening Mandiri">
                        </div>

                        <div class="bank-section">
                            <h3>👤 Atas Nama</h3>
                            <input type="text" id="bankAccountName" placeholder="Nama Pemilik Rekening">
                        </div>
                    </div>

                    <!-- Actions -->
<div class="form-actions">
    <button type="button" class="btn btn-primary" id="saveSettingsBtn">💾 Simpan Pengaturan</button>
    <button type="button" class="btn btn-secondary" id="resetBtn">↺ Reset</button>
</div>
                </div>
            </div>

            <!-- PREVIEW KANAN -->
            <div class="settings-card preview-card">
                <div class="card-header">
                    <h2>👁️ Live Preview</h2>
                </div>
                <div class="preview-content">
                    <div class="company-preview">
                        <div class="preview-logo" id="previewLogoIcon">🏢</div>
                        <div class="preview-name" id="previewCompanyName">Nama Perusahaan</div>
                        <div class="preview-info">
                            <span id="previewAddress">Jl. Contoh No. 123</span><br>
                            <span id="previewPhone">+62 812-3456-7890</span><br>
                            <span id="previewEmail">info@perusahaan.com</span>
                        </div>
                    </div>

                    <div class="info-row">
                        <span class="info-label">💳 BCA</span>
                        <span class="info-value" id="previewBCA">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">💳 Mandiri</span>
                        <span class="info-value" id="previewMandiri">-</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">👤 A.n</span>
                        <span class="info-value" id="previewAccountName">-</span>
                    </div>

                    <div class="alert alert-warning" style="margin-top: 20px;">
                        <span>ℹ️</span>
                        <span>Perubahan akan langsung diterapkan ke seluruh sistem</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SCRIPTS (SAMA PERSIS KAYAK DASHBOARD) -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/settings.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="{{ asset('js/settings.js') }}"></script>
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
        window.companyName = settings.company_name;
        window.companyLogo = settings.company_logo;
    } catch (error) {
        console.error('Gagal update logo:', error);
    }
}
updateSidebarLogo();



// Logout handler
document.getElementById('logoutBtn')?.addEventListener('click', async (e) => {
    e.preventDefault();
    const result = await Swal.fire({
        title: 'Yakin ingin logout?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        confirmButtonText: 'Ya, Logout!',
        cancelButtonText: 'Batal'
    });
    if (!result.isConfirmed) return;
    try {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            }
        });
        localStorage.clear();
        sessionStorage.clear();
        await Swal.fire({ title: 'Logout Berhasil!', icon: 'success', timer: 1200, showConfirmButton: false });
        window.location.href = '/';
    } catch (err) {
        console.error(err);
        Swal.fire('Error!', 'Gagal logout!', 'error');
    }
});

// Event listener untuk tombol simpan
document.getElementById('saveSettingsBtn')?.addEventListener('click', function() {
    saveSettings();
});

document.getElementById('resetBtn')?.addEventListener('click', function() {
    resetToDefault();
});
</script>
</body>
</html>