// dashboard.js - JAVASCRIPT KHUSUS UNTUK HALAMAN DASHBOARD

console.log("DASHBOARD JS LOADED 🔥");

// ================== LOAD DASHBOARD DATA ==================
async function loadDashboard() {
    try {
        const res = await fetch('/api/dashboard?range=year');
        const data = await res.json();
        console.log(data.warningClients);
        console.log(data);

        // ================= STAT =================
        document.getElementById("totalClient").innerText = data.totalClients;
        document.getElementById("activeClient").innerText = data.activeClients;
        document.getElementById("inactiveClient").innerText = data.inactiveClients;

        document.getElementById("totalRevenue").innerText =
            "Rp " + Number(data.totalrevenue).toLocaleString("id-ID");

        // ================= CHART 1 =================
        new Chart(document.getElementById("userChart"), {
            type: "bar",
            data: {
                labels: data.months.map(m => {
                    const [month, year] = m.split(" ");
                    return [month, year];
                }),
                datasets: [{
                    data: data.clientdata,
                    backgroundColor: "#22c55e",
                    borderRadius: 10
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: { beginAtZero: true }
                }
            }
        });

        // ================= CHART 2 =================
        new Chart(document.getElementById("activityChart"), {
            type: "line",
            data: {
                labels: data.months.map(m => {
                    const [month, year] = m.split(" ");
                    return [month, year];
                }),
                datasets: [{
                    data: data.revenuedata,
                    borderColor: "#3b82f6",
                    tension: 0.5
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false
                        }
                    }
                }
            }
        });

        // ================= WARNING CARDS =================
        const container = document.getElementById("warningContainer");
        container.innerHTML = "";

        data.warningClients.forEach(client => {
            let color = "green";
            let statusText = "";

            if (client.days_left <= 5) {
                color = "red";
                statusText = "⚠️ Langganan telah berakhir";
            } else if (client.days_left <= 15) {
                color = "orange";
                statusText = `🟠 Langganan akan berakhir dalam ${client.days_left} hari`;
            } else if (client.days_left <= 20) {
                color = "yellow";
                statusText = `🟡 Langganan akan berakhir dalam ${client.days_left} hari`;
            } else {
                color = "green";
                statusText = `🟢 Masih aktif, berakhir dalam ${client.days_left} hari`;
            }

            const showDeleteBtn = client.days_left <= 0;
            
            const card = `
                <div class="warning-card ${color}" data-id="${client.id}">
                    <div class="badge">${client.days_left} hari</div>
                    <div class="card-name">${escapeHtml(client.name)}</div>
                    <div class="card-company">${escapeHtml(client.company)}</div>
                    <div class="detail-row">
                        <span>📧</span>
                        <span>${escapeHtml(client.email || '-')}</span>
                    </div>
                    <div class="detail-row">
                        <span>📅</span>
                        <span>${client.subscription_end_date}</span>
                    </div>
                    <div class="detail-row">
                        <span>💰</span>
                        <span>${client.price}</span>
                    </div>
                    <div class="status-message">${statusText}</div>
                    ${showDeleteBtn ? `<button class="delete-warning-btn" data-id="${client.id}" data-name="${escapeHtml(client.name)}">✕ Hapus</button>` : ''}
                </div>
            `;
            
            container.innerHTML += card;
        });

    } catch (err) {
        console.error("Dashboard error:", err);
    }
}

function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ================== SIDEBAR AVATAR & WELCOME ==================
const avatarSidebar = document.getElementById("avatarSidebar");
const nameSidebar = document.getElementById("userNameSidebar");
const emailSidebar = document.getElementById("userEmailSidebar");

function updateSidebar(user) {
    if (nameSidebar) nameSidebar.textContent = user.name;
    if (emailSidebar) emailSidebar.textContent = user.email;
    if (avatarSidebar) {
        const nameParts = user.name.split(" ").slice(0, 2);
        avatarSidebar.textContent = nameParts.map(w => w[0]).join("").toUpperCase();
    }
}

// Ambil data dari localStorage
const userLS = localStorage.getItem("user_name");
const emailLS = localStorage.getItem("user_email");

if (userLS && emailLS) {
    updateSidebar({ name: userLS, email: emailLS });
    
    if (!localStorage.getItem("welcome_shown")) {
        Swal.fire({
            title: "Selamat Datang 👋",
            text: "Halo " + userLS,
            icon: "success",
            confirmButtonText: "OK"
        });
        localStorage.setItem("welcome_shown", "true");
    }
}

// ================== LOGOUT FIXED UNTUK DASHBOARD ==================
const logoutBtn = document.getElementById("logoutBtn");

if (logoutBtn) {
    logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        
        // Konfirmasi logout
        const result = await Swal.fire({
            title: 'Yakin ingin logout?',
            text: "Anda akan keluar dari dashboard",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        });
        
        if (!result.isConfirmed) return;
        
        // Tampilkan loading
        Swal.fire({
            title: 'Logout...',
            text: 'Sedang memproses',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const token = localStorage.getItem("auth_token");
            
            // Kirim request logout ke server
            const response = await fetch('/logout', {
                method: 'POST',
                credentials: 'include',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    ...(token && { 'Authorization': `Bearer ${token}` })
                }
            });
            
            // Hapus semua data localStorage TERLEBIH DAHULU
            localStorage.clear();
            sessionStorage.clear();
            
            // Hapus cookies yang mungkin tersisa
            document.cookie.split(";").forEach(function(c) {
                document.cookie = c.replace(/^ +/, "").replace(/=.*/, "=;expires=" + new Date().toUTCString() + ";path=/");
            });
            
            await Swal.fire({
                title: 'Logout Berhasil!',
                text: 'Anda telah keluar dari sistem',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
            
            // Redirect ke halaman login
            window.location.href = '/';
            
        } catch (err) {
            console.error('Logout error:', err);
            
            // Jika fetch gagal, tetap hapus data lokal
            localStorage.clear();
            sessionStorage.clear();
            
            await Swal.fire({
                title: 'Logout',
                text: 'Mengarahkan ke halaman login...',
                icon: 'info',
                timer: 1500,
                showConfirmButton: false
            });
            
            window.location.href = '/';
        }
    });
} else {
    console.error("Tombol logout tidak ditemukan!");
}

// ================== DELETE WARNING ==================
let warningClientIdToDelete = null;
let warningClientNameToDelete = null;

// Event delegation untuk tombol hapus
document.body.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-warning-btn');
    if (btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        if (id) {
            warningClientIdToDelete = id;
            warningClientNameToDelete = name;
            
            const namaSpan = document.querySelector('#popupHapusWarning .nama-klien-warning');
            if (namaSpan) {
                namaSpan.innerHTML = `"${name}"`;
            }
            document.getElementById('popupHapusWarning').classList.add('open');
        }
    }
});

// Konfirmasi hapus
const confirmBtn = document.getElementById('confirmDeleteWarningBtn');
if (confirmBtn) {
    confirmBtn.addEventListener('click', async function() {
        if (!warningClientIdToDelete) return;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const res = await fetch(`/klien/${warningClientIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            if (data.success) {
                document.getElementById('popupHapusWarning').classList.remove('open');
                
                const deletedCard = document.querySelector(`.warning-card[data-id='${warningClientIdToDelete}']`);
                if (deletedCard) {
                    deletedCard.remove();
                }
                
                Swal.fire('Berhasil!', `Klien ${warningClientNameToDelete} berhasil dihapus`, 'success');
                loadDashboard();
            } else {
                Swal.fire('Gagal!', data.message || 'Gagal menghapus!', 'error');
            }
        } catch (err) {
            console.error(err);
            Swal.fire('Error!', 'Terjadi kesalahan!', 'error');
        }
        
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

// Tombol batal
const cancelBtn = document.getElementById('cancelDeleteWarningBtn');
if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
        document.getElementById('popupHapusWarning').classList.remove('open');
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

// Klik di luar popup
const popupWarning = document.getElementById('popupHapusWarning');
if (popupWarning) {
    popupWarning.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
            warningClientIdToDelete = null;
            warningClientNameToDelete = null;
        }
    });
}

// Load dashboard saat halaman siap
loadDashboard();

// ================== BOX CLICK HANDLER ==================

async function showTotalKlienPopup() {
    const listContainer = document.getElementById("totalKlienList");
    listContainer.innerHTML = '<div class="loading-text">Loading data...</div>';
    document.getElementById("popupTotalKlien").classList.add("open");
    
    try {
        const res = await fetch('/api/dashboard/total-klien');
        const data = await res.json();
        
        if (data.success) {
            document.getElementById("totalKlienCount").innerText = data.total;
            listContainer.innerHTML = "";
            
            data.data.forEach(client => {
                const clientHtml = `
                    <div class="client-item">
                        <div class="client-info">
                            <h4>${escapeHtml(client.name)}</h4>
                            <p class="client-company">${escapeHtml(client.company)}</p>
                            <p>📧 ${escapeHtml(client.email)}</p>
                            <p>📅 Berakhir: ${client.end_date}</p>
                        </div>
                        <span class="client-status ${client.status_class}">${client.status}</span>
                    </div>
                `;
                listContainer.innerHTML += clientHtml;
            });
        }
    } catch (err) {
        console.error("Error:", err);
        listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>';
    }
}

async function showKlienAktifPopup() {
    const listContainer = document.getElementById("klienAktifList");
    listContainer.innerHTML = '<div class="loading-text">Loading data...</div>';
    document.getElementById("popupKlienAktif").classList.add("open");
    
    try {
        const res = await fetch('/api/dashboard/klien-aktif');
        const data = await res.json();
        
        if (data.success) {
            document.getElementById("aktifCount").innerText = data.total;
            listContainer.innerHTML = "";
            
            data.data.forEach(client => {
                const clientHtml = `
                    <div class="client-item">
                        <div class="client-info">
                            <h4>${escapeHtml(client.name)}</h4>
                            <p class="client-company">${escapeHtml(client.company)}</p>
                            <p>📧 ${escapeHtml(client.email)}</p>
                            <p>📅 Berakhir: ${client.end_date}</p>
                        </div>
                        <span class="client-status status-aktif">✅ Aktif</span>
                    </div>
                `;
                listContainer.innerHTML += clientHtml;
            });
        }
    } catch (err) {
        console.error("Error:", err);
        listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>';
    }
}

async function showTidakAktifPopup() {
    const listContainer = document.getElementById("tidakAktifList");
    listContainer.innerHTML = '<div class="loading-text">Loading data...</div>';
    document.getElementById("popupTidakAktif").classList.add("open");
    
    try {
        const res = await fetch('/api/dashboard/klien-tidak-aktif');
        const data = await res.json();
        
        if (data.success) {
            document.getElementById("tidakAktifCount").innerText = data.total;
            listContainer.innerHTML = "";
            
            data.data.forEach(client => {
                const clientHtml = `
                    <div class="client-item">
                        <div class="client-info">
                            <h4>${escapeHtml(client.name)}</h4>
                            <p class="client-company">${escapeHtml(client.company)}</p>
                            <p>📧 ${escapeHtml(client.email)}</p>
                            <p>📅 Berakhir: ${client.end_date}</p>
                        </div>
                        <span class="client-status status-berakhir">❌ Tidak Aktif</span>
                    </div>
                `;
                listContainer.innerHTML += clientHtml;
            });
        }
    } catch (err) {
        console.error("Error:", err);
        listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>';
    }
}

async function showTotalPendapatanPopup() {
    const listContainer = document.getElementById("pendapatanList");
    listContainer.innerHTML = '<div class="loading-text">Loading data...</div>';
    document.getElementById("popupTotalPendapatan").classList.add("open");
    
    try {
        const res = await fetch('/api/dashboard/total-pendapatan');
        const data = await res.json();
        
        if (data.success) {
            document.getElementById("totalPendapatanValue").innerHTML = data.total_revenue_formatted;
            document.getElementById("grandTotalPendapatan").innerHTML = data.total_revenue_formatted;
            listContainer.innerHTML = "";
            
            data.data.forEach(client => {
                const clientHtml = `
                    <div class="client-item">
                        <div class="client-info">
                            <h4>${escapeHtml(client.name)}</h4>
                            <p class="client-company">${escapeHtml(client.company)}</p>
                        </div>
                        <div class="client-price">${client.revenue_formatted}</div>
                    </div>
                `;
                listContainer.innerHTML += clientHtml;
            });
        }
    } catch (err) {
        console.error("Error:", err);
        listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>';
    }
}

// Inisialisasi klik box
function initBoxClickHandlers() {
    const boxes = document.querySelectorAll('.total');
    
    if (boxes[0]) boxes[0].addEventListener('click', showTotalKlienPopup);
    if (boxes[1]) boxes[1].addEventListener('click', showKlienAktifPopup);
    if (boxes[2]) boxes[2].addEventListener('click', showTidakAktifPopup);
    if (boxes[3]) boxes[3].addEventListener('click', showTotalPendapatanPopup);
}

// Tutup popup
function closeAllPopups() {
    document.querySelectorAll('.modal-popup').forEach(popup => {
        popup.classList.remove('open');
    });
}

document.querySelectorAll('.close-popup, .btn-tutup').forEach(btn => {
    btn.addEventListener('click', closeAllPopups);
});

document.querySelectorAll('.modal-popup').forEach(popup => {
    popup.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
        }
    });
});

// Panggil saat halaman load
document.addEventListener('DOMContentLoaded', () => {
    initBoxClickHandlers();
});

// ================== TUTUP POPUP ==================
function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) {
        popup.classList.remove('open');
    }
}

function closeAllPopups() {
    document.querySelectorAll('.modal-popup').forEach(popup => {
        popup.classList.remove('open');
    });
}

// Event listener untuk tombol tutup (X) dan tombol Tutup
document.addEventListener('DOMContentLoaded', function() {
    // Tutup pakai tombol close (x)
    document.querySelectorAll('.close-popup').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const popupId = this.getAttribute('data-popup');
            if (popupId) {
                closePopup(popupId);
            }
        });
    });
    
    // Tutup pakai tombol "Tutup"
    document.querySelectorAll('.btn-tutup').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const popupId = this.getAttribute('data-popup');
            if (popupId) {
                closePopup(popupId);
            }
        });
    });
    
    // Klik di luar modal untuk menutup
    document.querySelectorAll('.modal-popup').forEach(popup => {
        popup.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.remove('open');
            }
        });
    });
});