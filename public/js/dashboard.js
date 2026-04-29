// dashboard.js - JAVASCRIPT KHUSUS UNTUK HALAMAN DASHBOARD

console.log("DASHBOARD JS LOADED 🔥");

// ================== GLOBAL VARIABLES ==================
let currentYear = new Date().getFullYear();
let userChart = null;
let activityChart = null;
let warningClientIdToDelete = null;
let warningClientNameToDelete = null;

const invoiceCss = document.createElement('link');
invoiceCss.rel = 'stylesheet';
invoiceCss.href = '/css/invoice.css';
document.head.appendChild(invoiceCss);

// ================== FUNGSI MAX Y AXIS ==================
function getMaxYAxis(data) {
    const validData = data.filter(v => typeof v === 'number' && !isNaN(v));
    if (validData.length === 0) return 10;
    const maxData = Math.max(...validData);
    if (maxData <= 10) return 10;
    if (maxData <= 15) return 15;
    if (maxData <= 20) return 20;
    if (maxData <= 25) return 25;
    if (maxData <= 30) return 30;
    if (maxData <= 40) return 40;
    if (maxData <= 50) return 50;
    return Math.ceil(maxData / 10) * 10;
}

// ================== UTILITY FUNCTIONS ==================
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

function formatDateInvoice(dateStr) {
    if (!dateStr) return '-';
    const d = new Date(dateStr);
    const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
}

// ==================== INVOICE POPUP ====================
function openInvoicePopup(data) {
    const oldPopup = document.getElementById('popupInvoice');
    if (oldPopup) oldPopup.remove();
    
    const today = new Date();
    const endDate = new Date(data.akhir);
    const daysLeft = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
    
    let statusText = '';
    let statusClass = '';
    if (daysLeft <= 0) {
        statusText = 'EXPIRED';
        statusClass = 'status-expired';
    } else if (daysLeft <= 7) {
        statusText = `BERAKHIR DALAM ${daysLeft} HARI`;
        statusClass = 'status-warning';
    } else {
        statusText = 'AKTIF';
        statusClass = 'status-active';
    }
    
    const nomorInvoice = `INV/${new Date().getFullYear()}/${String(data.id).padStart(4, '0')}`;
    
    const popupHtml = `
    <div class="add open" id="popupInvoice" style="opacity:1; pointer-events:auto; z-index:10001;">
        <div class="invoice-container">
            <div class="invoice-header">
                <div>
                    <h2>INVOICE</h2>
                    <p>${nomorInvoice}</p>
                </div>
                <button class="close-invoice">&times;</button>
            </div>
            
            <div class="invoice-body">
                <div class="invoice-to">
                    <div class="to-label">KEPADA YTH:</div>
                    <div class="to-name">${escapeHtml(data.nama)}</div>
                    <div class="to-detail">${escapeHtml(data.perusahaan || '-')}</div>
                    <div class="to-detail">📧 ${escapeHtml(data.email)}</div>
                </div>
                
                <div class="invoice-info-grid">
                    <div class="info-card">
                        <div class="info-label">TANGGAL INVOICE</div>
                        <div class="info-value">${formatDateInvoice(today)}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">STATUS</div>
                        <div class="info-value ${statusClass}">${statusText}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">MASA BERAKHIR</div>
                        <div class="info-value">${formatDateInvoice(data.akhir)}</div>
                    </div>
                    <div class="info-card">
                        <div class="info-label">SISA HARI</div>
                        <div class="info-value">${daysLeft > 0 ? daysLeft + ' hari' : 'Berakhir'}</div>
                    </div>
                </div>
                
                <div class="invoice-divider"></div>
                
                <table class="invoice-table-modern">
                    <thead><tr><th>DESKRIPSI</th><th class="text-right">TOTAL</th></tr></thead>
                    <tbody><tr><td>Langganan Tahunan</td><td class="text-right">${data.pendapatan}</td></tr></tbody>
                    <tfoot><tr><td class="text-right"><strong>TOTAL</strong></td><td class="text-right"><strong>${data.pendapatan}</strong></td></tr></tfoot>
                </table>
                
                <div class="invoice-divider"></div>
                
                <div class="invoice-message">
                    <p>Terima kasih atas kepercayaan dan kerja samanya.</p>
                    <div class="signature">
                        <p>Hormat kami,</p>
                        <p><strong>Admin Panel</strong></p>
                    </div>
                </div>
                
                <div class="invoice-note">*Invoice ini digenerate secara otomatis oleh sistem.</div>
                
                <div class="invoice-actions">
                    <button class="btn-print" onclick="window.printInvoiceFromDashboard()">🖨️ CETAK</button>
                    <button class="btn-wa" id="sendWABtn">📱 KIRIM KE WA</button>
                    <button class="btn-close close-invoice">TUTUP</button>
                </div>
            </div>
        </div>
    </div>`;
    
    document.body.insertAdjacentHTML('beforeend', popupHtml);
    
   // Tombol WA - Kirim Link Download PDF
// Tombol WA - Kirim Link Download PDF (TANPA EMOJI)
const waBtn = document.getElementById('sendWABtn');

if (waBtn) {
    waBtn.addEventListener('click', function() {

        fetch(`/invoice/generate/${data.id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(res => res.json())
        .then(res => {
            if (!res.success) {
                alert("Gagal generate invoice");
                return;
            }

            const pdfLink = res.url;

            const message = encodeURIComponent(
                `Yth. ${data.nama}\n\n` +
                `Berikut invoice Anda:\n\n` +
                `${pdfLink}\n\n` +
                `Hormat kami,\nAdmin Panel`
            );

            const phoneNumber = "6281338447310"; // TEST MANUAL

            window.open(`https://wa.me/${phoneNumber}?text=${message}`, '_blank');
        });

    });
}
    document.querySelectorAll('.close-invoice').forEach(btn => {
        btn.addEventListener('click', () => document.getElementById('popupInvoice')?.remove());
    });
    
    document.getElementById('popupInvoice')?.addEventListener('click', function(e) {
        if (e.target === this) this.remove();
    });
}

// Fungsi print
window.printInvoiceFromDashboard = function() {
    const invoiceContainer = document.querySelector('#popupInvoice .invoice-container');
    if (!invoiceContainer) return;
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html><head><title>Invoice</title>
        <style>
            body { font-family: Arial, sans-serif; padding: 40px; }
            .invoice-container { max-width: 550px; margin: 0 auto; }
            .invoice-header { background: #1a1a2e; color: white; padding: 20px; text-align: center; }
            .invoice-body { padding: 20px; }
            .invoice-to { background: #f8fafc; padding: 15px; margin-bottom: 20px; }
            .invoice-info-grid { display: flex; gap: 15px; margin-bottom: 20px; flex-wrap: wrap; }
            .info-card { background: #f1f5f9; padding: 10px; flex: 1; }
            .invoice-table-modern { width: 100%; border-collapse: collapse; margin: 20px 0; }
            .invoice-table-modern th, .invoice-table-modern td { padding: 10px; border-bottom: 1px solid #e5e7eb; text-align: left; }
            .text-right { text-align: right; }
            .invoice-divider { border-top: 1px dashed #e5e7eb; margin: 15px 0; }
            .invoice-message { text-align: center; margin: 20px 0; }
            .invoice-note { text-align: center; font-size: 10px; color: #9ca3af; }
            .invoice-actions { display: none; }
        </style>
        </head><body>${invoiceContainer.outerHTML}</body></html>
    `);
    printWindow.document.close();
    printWindow.print();
    printWindow.close();
};

// ================== LOAD DASHBOARD ==================
async function loadDashboard() {
    try {
        const res = await fetch(`/api/dashboard?range=year&year=${currentYear}`);
        const data = await res.json();
        console.log("Dashboard Data:", data);

        document.getElementById("totalClient").innerText = data.totalClients;
        document.getElementById("activeClient").innerText = data.activeClients;
        document.getElementById("inactiveClient").innerText = data.inactiveClients;
        document.getElementById("totalRevenue").innerText = "Rp " + Number(data.totalrevenue).toLocaleString("id-ID");

        if (userChart) userChart.destroy();
        if (activityChart) activityChart.destroy();

        const maxY1 = getMaxYAxis(data.clientdata);
        userChart = new Chart(document.getElementById("userChart"), {
            type: "bar",
            data: {
                labels: data.months.map(m => m.split(" ")[0]),
                datasets: [{ data: data.clientdata, backgroundColor: "#22c55e", borderRadius: 10 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, max: maxY1, title: { display: true, text: 'Jumlah Klien Aktif' }, ticks: { stepSize: 1, precision: 0 } }
                }
            }
        });

        const maxY2 = getMaxYAxis(data.revenuedata);
        activityChart = new Chart(document.getElementById("activityChart"), {
            type: "line",
            data: {
                labels: data.months.map(m => m.split(" ")[0]),
                datasets: [{ data: data.revenuedata, borderColor: "#3b82f6", backgroundColor: "rgba(59,130,246,0.1)", tension: 0.3, fill: true, pointRadius: 5 }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true, max: maxY2, title: { display: true, text: 'Jumlah Klien Baru' }, ticks: { stepSize: 1, precision: 0 } } }
            }
        });

        const container = document.getElementById("warningContainer");
        if (container) {
            container.innerHTML = "";
            data.warningClients.forEach(client => {
                let color = "green", statusText = "";
                if (client.days_left <= 0) { color = "red"; statusText = "⚠️ Langganan telah berakhir"; }
                else if (client.days_left <= 5) { color = "red"; statusText = `🔴 Berakhir dalam ${client.days_left} hari`; }
                else if (client.days_left <= 15) { color = "orange"; statusText = `🟠 Berakhir dalam ${client.days_left} hari`; }
                else if (client.days_left <= 20) { color = "yellow"; statusText = `🟡 Berakhir dalam ${client.days_left} hari`; }
                else { color = "green"; statusText = `🟢 Aktif, berakhir ${client.days_left} hari`; }
                
                const showDeleteBtn = client.days_left <= 0;
                const card = `
                    <div class="warning-card ${color}" data-id="${client.id}">
                        <div class="badge">${client.days_left} hari</div>
                        <div class="card-name">${escapeHtml(client.name)}</div>
                        <div class="card-company">${escapeHtml(client.company)}</div>
                        <div class="detail-row"><span>📧</span><span>${escapeHtml(client.email || '-')}</span></div>
                        <div class="detail-row"><span>📅</span><span>${client.subscription_end_date}</span></div>
                        <div class="detail-row"><span>💰</span><span>${client.price}</span></div>
                        <div class="status-message">${statusText}</div>
                        <div class="warning-actions">
                            ${showDeleteBtn ? `<button class="delete-warning-btn" data-id="${client.id}" data-name="${escapeHtml(client.name)}">✕ Hapus</button>` : ''}
                            <button class="invoice-warning-btn" data-id="${client.id}" data-name="${escapeHtml(client.name)}" data-company="${escapeHtml(client.company)}" data-email="${escapeHtml(client.email)}" data-price="${client.price}" data-end="${client.subscription_end_date}">📄 Invoice</button>
                        </div>
                    </div>
                `;
                container.innerHTML += card;
            });
        }
    } catch (err) {
        console.error("Dashboard error:", err);
    }
}

// ================== GANTI TAHUN ==================
function changeYear(direction) {
    if (direction === 'prev') currentYear--;
    else if (direction === 'next') currentYear++;
    document.getElementById('currentYear').innerText = currentYear;
    loadDashboard();
}

// ================== EVENT LISTENER ==================
document.body.addEventListener('click', function(e) {
    const deleteBtn = e.target.closest('.delete-warning-btn');
    if (deleteBtn) {
        const id = deleteBtn.getAttribute('data-id');
        const name = deleteBtn.getAttribute('data-name');
        if (id) {
            warningClientIdToDelete = id;
            warningClientNameToDelete = name;
            const namaSpan = document.querySelector('#popupHapusWarning .nama-klien-warning');
            if (namaSpan) namaSpan.innerHTML = `"${name}"`;
            document.getElementById('popupHapusWarning').classList.add('open');
        }
        return;
    }
    
    const invoiceBtn = e.target.closest('.invoice-warning-btn');
    if (invoiceBtn) {
        const id = invoiceBtn.getAttribute('data-id');
        const name = invoiceBtn.getAttribute('data-name');
        const company = invoiceBtn.getAttribute('data-company');
        const email = invoiceBtn.getAttribute('data-email');
        const price = invoiceBtn.getAttribute('data-price');
        const endDate = invoiceBtn.getAttribute('data-end');
        
        openInvoicePopup({
            id: id, nama: name, perusahaan: company, email: email,
            pendapatan: price, akhir: endDate
        });
        return;
    }
});

// ================== DELETE WARNING ==================
const confirmBtn = document.getElementById('confirmDeleteWarningBtn');
if (confirmBtn) {
    confirmBtn.addEventListener('click', async function() {
        if (!warningClientIdToDelete) return;
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(`/klien/${warningClientIdToDelete}`, {
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' }
            });
            const data = await res.json();
            if (data.success) {
                document.getElementById('popupHapusWarning').classList.remove('open');
                Swal.fire('Berhasil!', `Klien ${warningClientNameToDelete} berhasil dihapus`, 'success');
                loadDashboard();
            } else {
                Swal.fire('Gagal!', data.message || 'Gagal menghapus!', 'error');
            }
        } catch (err) { console.error(err); Swal.fire('Error!', 'Terjadi kesalahan!', 'error'); }
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

const cancelBtn = document.getElementById('cancelDeleteWarningBtn');
if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
        document.getElementById('popupHapusWarning').classList.remove('open');
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

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

// ================== EVENT LISTENER TOMBOL TAHUN ==================
document.getElementById('prevYearBtn')?.addEventListener('click', () => changeYear('prev'));
document.getElementById('nextYearBtn')?.addEventListener('click', () => changeYear('next'));

// ================== SIDEBAR ==================
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

const userLS = localStorage.getItem("user_name");
const emailLS = localStorage.getItem("user_email");

if (userLS && emailLS) {
    updateSidebar({ name: userLS, email: emailLS });
    if (!localStorage.getItem("welcome_shown")) {
        Swal.fire({ title: "Selamat Datang 👋", text: "Halo " + userLS, icon: "success", confirmButtonText: "OK" });
        localStorage.setItem("welcome_shown", "true");
    }
}

// ================== LOGOUT ==================
const logoutBtn = document.getElementById("logoutBtn");
if (logoutBtn) {
    logoutBtn.addEventListener("click", async (e) => {
        e.preventDefault();
        const result = await Swal.fire({ title: 'Yakin ingin logout?', icon: 'question', showCancelButton: true, confirmButtonColor: '#d33', confirmButtonText: 'Ya, Logout!', cancelButtonText: 'Batal' });
        if (!result.isConfirmed) return;
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            await fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } });
            localStorage.clear();
            sessionStorage.clear();
            await Swal.fire({ title: 'Logout Berhasil!', icon: 'success', timer: 1200, showConfirmButton: false });
            window.location.href = '/';
        } catch (err) { console.error(err); Swal.fire('Error!', 'Gagal logout!', 'error'); }
    });
}

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
                listContainer.innerHTML += `
                    <div class="client-item">
                        <div class="client-info"><h4>${escapeHtml(client.name)}</h4><p class="client-company">${escapeHtml(client.company)}</p><p>📧 ${escapeHtml(client.email)}</p><p>📅 Berakhir: ${client.end_date}</p></div>
                        <span class="client-status ${client.status_class}">${client.status}</span>
                    </div>
                `;
            });
        }
    } catch (err) { console.error(err); listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>'; }
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
                listContainer.innerHTML += `
                    <div class="client-item">
                        <div class="client-info"><h4>${escapeHtml(client.name)}</h4><p class="client-company">${escapeHtml(client.company)}</p><p>📧 ${escapeHtml(client.email)}</p><p>📅 Berakhir: ${client.end_date}</p></div>
                        <span class="client-status status-aktif">✅ Aktif</span>
                    </div>
                `;
            });
        }
    } catch (err) { console.error(err); listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>'; }
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
                listContainer.innerHTML += `
                    <div class="client-item">
                        <div class="client-info"><h4>${escapeHtml(client.name)}</h4><p class="client-company">${escapeHtml(client.company)}</p><p>📧 ${escapeHtml(client.email)}</p><p>📅 Berakhir: ${client.end_date}</p></div>
                        <span class="client-status status-berakhir">❌ Tidak Aktif</span>
                    </div>
                `;
            });
        }
    } catch (err) { console.error(err); listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>'; }
}

// ================== TOTAL PENDAPATAN ==================
let currentRevenueYear = new Date().getFullYear();

async function showTotalPendapatanPopup() {
    const listContainer = document.getElementById("pendapatanList");
    const yearSelect = document.getElementById("tahunPendapatanSelect");
    if (yearSelect) yearSelect.value = currentRevenueYear;
    listContainer.innerHTML = '<div class="loading-text">Loading data...</div>';
    document.getElementById("popupTotalPendapatan").classList.add("open");
    await loadRevenueData(currentRevenueYear);
}

async function loadRevenueData(tahun) {
    const listContainer = document.getElementById("pendapatanList");
    const grandTotalElement = document.getElementById("grandTotalPendapatan");
    try {
        const res = await fetch(`/api/dashboard/total-pendapatan?tahun=${tahun}`);
        const data = await res.json();
        if (data.success) {
            if (grandTotalElement) grandTotalElement.innerHTML = data.total_revenue_formatted;
            const tahunJudul = document.getElementById("tahunPendapatanJudul");
            if (tahunJudul) tahunJudul.innerHTML = `Tahun ${tahun}`;
            listContainer.innerHTML = "";
            if (data.data && data.data.length > 0) {
                data.data.forEach(client => {
                    listContainer.innerHTML += `
                        <div class="client-item">
                            <div class="client-info"><h4>${escapeHtml(client.name)}</h4><p class="client-company">${escapeHtml(client.company)}</p><p>📅 Mulai: ${client.start_date || '-'}</p></div>
                            <div class="client-price">${client.revenue_formatted}</div>
                        </div>
                    `;
                });
            } else { listContainer.innerHTML = '<div class="loading-text">Tidak ada data pendapatan untuk tahun ini</div>'; }
        } else { listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>'; }
    } catch (err) { console.error(err); listContainer.innerHTML = '<div class="loading-text">Gagal memuat data</div>'; }
}

function changeRevenueYear(direction) {
    if (direction === 'prev') currentRevenueYear--;
    else if (direction === 'next') currentRevenueYear++;
    const yearSelect = document.getElementById("tahunPendapatanSelect");
    if (yearSelect) yearSelect.value = currentRevenueYear;
    loadRevenueData(currentRevenueYear);
}

function setRevenueYear() {
    const yearSelect = document.getElementById("tahunPendapatanSelect");
    if (yearSelect) {
        currentRevenueYear = parseInt(yearSelect.value);
        loadRevenueData(currentRevenueYear);
    }
}

function populateYearSelector() {
    const yearSelect = document.getElementById('tahunPendapatanSelect');
    if (!yearSelect) { console.log("Element tahunPendapatanSelect tidak ditemukan"); return; }
    const tahunList = [2026, 2027, 2028, 2029, 2030];
    yearSelect.innerHTML = '';
    tahunList.forEach(year => { const option = document.createElement('option'); option.value = year; option.textContent = year; yearSelect.appendChild(option); });
    currentRevenueYear = 2026;
    yearSelect.value = currentRevenueYear;
    loadRevenueData(currentRevenueYear);
}

function initBoxClickHandlers() {
    const boxes = document.querySelectorAll('.total');
    if (boxes[0]) boxes[0].addEventListener('click', showTotalKlienPopup);
    if (boxes[1]) boxes[1].addEventListener('click', showKlienAktifPopup);
    if (boxes[2]) boxes[2].addEventListener('click', showTidakAktifPopup);
    if (boxes[3]) boxes[3].addEventListener('click', showTotalPendapatanPopup);
}

function closePopup(popupId) {
    const popup = document.getElementById(popupId);
    if (popup) popup.classList.remove('open');
}

function closeAllPopups() {
    document.querySelectorAll('.modal-popup').forEach(popup => popup.classList.remove('open'));
}

// ================== INITIALIZATION ==================
document.addEventListener('DOMContentLoaded', function() {
    initBoxClickHandlers();
    populateYearSelector();
    loadDashboard();
    
    document.querySelectorAll('.close-popup').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const popupId = this.getAttribute('data-popup');
            if (popupId) closePopup(popupId);
        });
    });
    
    document.querySelectorAll('.btn-tutup').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const popupId = this.getAttribute('data-popup');
            if (popupId) closePopup(popupId);
        });
    });
    
    document.querySelectorAll('.modal-popup').forEach(popup => {
        popup.addEventListener('click', function(e) {
            if (e.target === this) this.classList.remove('open');
        });
    });
});