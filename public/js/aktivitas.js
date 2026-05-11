// ==================== AKTIVITAS JS (REAL DATABASE) ====================

// ==================== FUNGSI UTILITY ====================
function getIconByType(type) {
    const icons = {
    login: '🔐',
    invoice: '📄',
    edit: '✏️',
    create: '➕',
    delete: '🗑️'
};
    return icons[type] || '📋';
}

function formatTime(time) {
    if (!time) return '-';
    const d = new Date(time);
    const now = new Date();
    const diff = Math.floor((now - d) / (1000 * 60 * 60));
    
    if (diff < 1) return 'Baru saja';
    if (diff < 24) return `${diff} jam yang lalu`;
    if (diff < 168) return `${Math.floor(diff / 24)} hari yang lalu`;
    
    return d.toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function getStatusText(status) {
    if (status === 'success') return '✓ Berhasil';
    if (status === 'warning') return '⚠️ Peringatan';
    return '✗ Gagal';
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

// ==================== LOAD DARI DATABASE ====================
async function loadActivities(filter = 'all') {
    try {
        let url = '/api/activities';
        if (filter !== 'all') {
            url += `?type=${filter}`;
        }
        
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            renderActivities(data.activities, filter);
            updateStatCounts(data.stats);
        } else {
            console.error('Gagal load data');
            showEmptyState('Gagal memuat data aktivitas');
        }
    } catch (error) {
        console.error('Error:', error);
        showEmptyState('Terjadi kesalahan saat memuat data');
    }
}

function renderActivities(activities, filter) {
    const count = activities.length;
    const activityCountEl = document.getElementById('activityCount');
    const activityListEl = document.getElementById('activityList');
    
    if (activityCountEl) {
        activityCountEl.innerText = `${count} aktivitas`;
    }
    
    if (!activityListEl) return;
    
    if (count === 0) {
        activityListEl.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <h4>Tidak Ada Aktivitas</h4>
                <p>Tidak ditemukan aktivitas untuk filter ini</p>
            </div>
        `;
        return;
    }
    
    activityListEl.innerHTML = activities.map(activity => `
        <div class="activity-item" data-type="${activity.type}">
            <div class="activity-icon ${activity.type}">
                ${getIconByType(activity.type)}
            </div>
            <div class="activity-content">
                <div class="activity-title">${escapeHtml(activity.title)}</div>
                <div class="activity-detail">${escapeHtml(activity.detail || '-')}</div>
                <div class="activity-meta">
                    <span>👤 ${escapeHtml(activity.user_name)}</span>
                    <span>⏰ ${formatTime(activity.created_at)}</span>
                    ${activity.ip_address ? `<span>🌐 ${activity.ip_address}</span>` : ''}
                </div>
            </div>
            <div>
                <span class="status-badge ${activity.status}">
                    ${getStatusText(activity.status)}
                </span>
            </div>
        </div>
    `).join('');
}

function updateStatCounts(stats) {
    const totalLoginEl = document.getElementById('totalLogin');
    const totalClientEl = document.getElementById('totalClient');  // ← GANTI
    const totalEditEl = document.getElementById('totalEdit');
    const totalInvoiceEl = document.getElementById('totalInvoice');
    
    if (totalLoginEl) totalLoginEl.innerText = stats?.total_login || 0;
    if (totalClientEl) totalClientEl.innerText = stats?.total_client || 0;  // ← GANTI
    if (totalEditEl) totalEditEl.innerText = stats?.total_edit || 0;
    if (totalInvoiceEl) totalInvoiceEl.innerText = stats?.total_invoice || 0;
}

function showEmptyState(message) {
    const activityListEl = document.getElementById('activityList');
    if (activityListEl) {
        activityListEl.innerHTML = `
            <div class="empty-state">
                <div class="empty-state-icon">⚠️</div>
                <h4>Error</h4>
                <p>${message}</p>
            </div>
        `;
    }
}

// ==================== FILTER HANDLER ====================
function initFilters() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const filter = this.getAttribute('data-filter');
            loadActivities(filter);
        });
    });
}

// ==================== SIDEBAR USER ====================
function initSidebar() {
    const userName = localStorage.getItem("user_name");
    const userEmail = localStorage.getItem("user_email");
    
    if (userName && userEmail) {
        const nameEl = document.getElementById('userNameSidebar');
        const emailEl = document.getElementById('userEmailSidebar');
        const avatarEl = document.getElementById('avatarSidebar');
        
        if (nameEl) nameEl.innerText = userName;
        if (emailEl) emailEl.innerText = userEmail;
        if (avatarEl) avatarEl.innerText = userName.substring(0, 2).toUpperCase();
    }
}

// ==================== LOGOUT ====================
function initLogout() {
    const logoutBtn = document.getElementById('logoutBtn');
    if (!logoutBtn) return;
    
    logoutBtn.addEventListener('click', async () => {
        const result = await Swal.fire({
            title: 'Yakin ingin logout?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout!',
            cancelButtonText: 'Batal'
        });
        
        if (result.isConfirmed) {
            localStorage.clear();
            sessionStorage.clear();
            window.location.href = '/';
        }
    });
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    initFilters();
    initSidebar();
    initLogout();
    loadActivities('all');
});