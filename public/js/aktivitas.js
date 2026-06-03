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
        <div class="activity-item" data-id="${activity.id}" data-type="${activity.type}">
            <div class="activity-checkbox">
                <input type="checkbox" class="select-activity" value="${activity.id}">
            </div>
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
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="status-badge ${activity.status}">
                    ${getStatusText(activity.status)}
                </span>
                <button class="delete-activity-btn" data-id="${activity.id}" data-title="${escapeHtml(activity.title)}" title="Hapus aktivitas">
                    🗑️
                </button>
            </div>
        </div>
    `).join('');
    
    // Event listener untuk tombol hapus per item
    document.querySelectorAll('.delete-activity-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.stopPropagation();
            const id = this.getAttribute('data-id');
            const title = this.getAttribute('data-title');
            deleteSingleActivity(id, title);
        });
    });
}

function updateStatCounts(stats) {
    const totalLoginEl = document.getElementById('totalLogin');
    const totalClientEl = document.getElementById('totalClient');
    const totalEditEl = document.getElementById('totalEdit');
    const totalInvoiceEl = document.getElementById('totalInvoice');
    
    if (totalLoginEl) totalLoginEl.innerText = stats?.total_login || 0;
    if (totalClientEl) totalClientEl.innerText = stats?.total_client || 0;
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

// ==================== HAPUS PER ITEM (1-1) ====================
async function deleteSingleActivity(activityId, activityTitle) {
    const result = await Swal.fire({
        title: 'Hapus Aktivitas?',
        text: `Apakah Anda yakin ingin menghapus aktivitas: "${activityTitle}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch(`/api/activities/${activityId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            Swal.fire('Berhasil!', 'Aktivitas berhasil dihapus', 'success');
            loadActivities('all');
        } else {
            Swal.fire('Gagal!', data.message || 'Gagal menghapus aktivitas', 'error');
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error!', 'Terjadi kesalahan', 'error');
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

// ==================== CLEAR ALL ACTIVITIES ====================
async function clearAllActivities() {
    const result = await Swal.fire({
        title: 'Hapus Semua Aktivitas?',
        text: 'Data aktivitas akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus Semua!',
        cancelButtonText: 'Batal'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch('/api/activities/clear', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.success) {
            Swal.fire('Berhasil!', 'Semua aktivitas berhasil dihapus', 'success');
            loadActivities('all');
        } else {
            Swal.fire('Gagal!', data.message || 'Gagal menghapus aktivitas', 'error');
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error!', 'Terjadi kesalahan', 'error');
    }
}

// ==================== SIDEBAR USER ====================
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

// ==================== LOGOUT ====================
logoutBtn.addEventListener("click", async (e) => {
    const result = await Swal.fire({ 
        title: 'Yakin ingin logout?', 
        icon: 'question', 
        showCancelButton: true, 
        confirmButtonColor: '#d33', 
        confirmButtonText: 'Ya, Logout!', 
        cancelButtonText: 'Batal' 
    });
    
    if (result.isConfirmed) {
        try {
            await fetch('/logout', { 
                method: 'POST', 
                credentials: 'include',
                headers: { 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json' 
                } 
            });
            window.location.href = '/login'; // ← INI yang kurang!
        } catch (err) { 
            console.error(err); 
            Swal.fire('Error!', 'Gagal logout!', 'error'); 
        }
    }
});

// ==================== CHECKLIST FUNCTIONS ====================
let selectedActivities = new Set();

function updateDeleteButton() {
    const deleteBtn = document.getElementById('deleteSelectedBtn');
    const count = selectedActivities.size;
    
    if (deleteBtn) {
        if (count > 0) {
            deleteBtn.style.display = 'flex';
            deleteBtn.innerHTML = `🗑️ Hapus ${count} Aktivitas Yang Dipilih`;
        } else {
            deleteBtn.style.display = 'none';
        }
    }
}

function bindCheckboxEvents() {
    const checkboxes = document.querySelectorAll('.select-activity');
    const selectAll = document.getElementById('selectAllCheckbox');
    
    checkboxes.forEach(cb => {
        cb.removeEventListener('change', handleCheckboxChange);
        cb.addEventListener('change', handleCheckboxChange);
    });
    
    if (selectAll) {
        selectAll.removeEventListener('change', handleSelectAll);
        selectAll.addEventListener('change', handleSelectAll);
    }
}

function handleCheckboxChange(e) {
    const id = parseInt(e.target.value);
    if (e.target.checked) {
        selectedActivities.add(id);
    } else {
        selectedActivities.delete(id);
        const selectAll = document.getElementById('selectAllCheckbox');
        if (selectAll) selectAll.checked = false;
    }
    updateDeleteButton();
}

function handleSelectAll(e) {
    const allCheckboxes = document.querySelectorAll('.select-activity');
    allCheckboxes.forEach(cb => {
        cb.checked = e.target.checked;
        const id = parseInt(cb.value);
        if (e.target.checked) {
            selectedActivities.add(id);
        } else {
            selectedActivities.delete(id);
        }
    });
    updateDeleteButton();
}

async function deleteSelectedActivities() {
    if (selectedActivities.size === 0) return;
    
    const result = await Swal.fire({
        title: `Hapus ${selectedActivities.size} aktivitas?`,
        text: 'Data aktivitas yang dipilih akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    });
    
    if (!result.isConfirmed) return;
    
    try {
        const res = await fetch('/api/activities/delete-multiple', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                ids: Array.from(selectedActivities)
            })
        });
        
        const data = await res.json();
        
        if (data.success) {
            Swal.fire('Berhasil!', `${selectedActivities.size} aktivitas dihapus`, 'success');
            selectedActivities.clear();
            loadActivities('all');
        } else {
            Swal.fire('Gagal!', data.message, 'error');
        }
    } catch (err) {
        console.error(err);
        Swal.fire('Error!', 'Terjadi kesalahan', 'error');
    }
}

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    console.log("DOM loaded, memuat aktivitas...");
    
    initFilters();
    loadActivities('all');  // ← HARUS ADA INI
    
    // Event listener untuk tombol clear all
    const clearBtn = document.getElementById('clearAllActivities');
    if (clearBtn) {
        clearBtn.addEventListener('click', clearAllActivities);
    }
    
    // Event listener untuk tombol delete selected
    const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
    if (deleteSelectedBtn) {
        deleteSelectedBtn.addEventListener('click', deleteSelectedActivities);
    }
});