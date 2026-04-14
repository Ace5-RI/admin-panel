// ==================== VARIABEL GLOBAL ====================
let deleteRow = null;
let editRow = null;
let currentEditId = null;

// ==================== CSRF TOKEN ====================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ==================== HELPER FUNCTIONS ====================
function formatTanggal(tgl) {
    if (!tgl) return '-';
    const d = new Date(tgl);
    return d.toLocaleDateString("id-ID", {
        day: "2-digit",
        month: "short",
        year: "numeric"
    });
}



function formatRupiah(angka) {
    if (!angka || angka == 0) return "Rp 0";
    return "Rp " + Number(angka).toLocaleString("id-ID");
}

function showNotification(message, type = 'success') {
    const oldNotif = document.querySelector('.notification');
    if (oldNotif) oldNotif.remove();
    
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `;
    document.body.appendChild(notification);
    
    notification.querySelector('.notification-close')?.addEventListener('click', () => {
        notification.remove();
    });
    
    setTimeout(() => {
        if (notification && notification.remove) {
            notification.remove();
        }
    }, 3000);
}

function closeAllPopup() {
    document.querySelectorAll(".add").forEach(p => p.classList.remove("open"));
}

function resetForm() {
    const form = document.getElementById("formKlien");
    if (form) form.reset();
    
    const akhir = document.getElementById('akhirTambah');
    const durasi = document.getElementById('durasiTambah');
    if (akhir) akhir.value = '';
    if (durasi) durasi.value = '';
    
    editRow = null;
    currentEditId = null;
}

// ==================== AUTO CALCULATE TANGGAL ====================
function initDateCalculations() {
    const mulai = document.getElementById('mulaiTambah');
    const durasi = document.getElementById('durasiTambah');
    const akhir = document.getElementById('akhirTambah');
    
    function hitungTanggal() {
        if (mulai && mulai.value && durasi && durasi.value) {
            let d = new Date(mulai.value);
            d.setFullYear(d.getFullYear() + parseInt(durasi.value));
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            if (akhir) akhir.value = `${y}-${m}-${day}`;
        }
    }
    
    if (durasi) durasi.addEventListener("change", hitungTanggal);
    if (mulai) mulai.addEventListener("change", hitungTanggal);
}

// ==================== CREATE/STORE DATA ====================
async function storeKlien(formData) {
    try {
        const response = await fetch('/klien', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Data berhasil ditambahkan!', 'success');
            setTimeout(() => window.location.reload(), 1500);
            return true;
        } else if (data.errors) {
            Object.values(data.errors).forEach(error => showNotification(error[0], 'error'));
            return false;
        } else {
            showNotification(data.message || 'Gagal menyimpan data', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error storing data:', error);
        showNotification('Gagal menyimpan data', 'error');
        return false;
    }
}

// ==================== UPDATE DATA ====================
async function updateKlien(id, formData) {
    try {
        const response = await fetch('/klien/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id, ...formData })
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Data berhasil diupdate!', 'success');
            setTimeout(() => window.location.reload(), 1500);
            return true;
        } else if (data.errors) {
            Object.values(data.errors).forEach(error => showNotification(error[0], 'error'));
            return false;
        } else {
            showNotification(data.message || 'Gagal mengupdate data', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error updating data:', error);
        showNotification('Gagal mengupdate data', 'error');
        return false;
    }
}

// ==================== DELETE DATA ====================
async function deleteKlien(id) {
    try {
        const response = await fetch(`/klien/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            showNotification('Data berhasil dihapus!', 'success');
            if (deleteRow) {
                deleteRow.remove();
                deleteRow = null;
            }
            return true;
        } else {
            showNotification(data.message || 'Gagal menghapus data', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error deleting data:', error);
        showNotification('Gagal menghapus data', 'error');
        return false;
    }
}

// ==================== EVENT LISTENERS ====================
document.addEventListener("DOMContentLoaded", function () {
    
    // Init date calculations
    initDateCalculations();
    
    // DOM Elements
    const form = document.getElementById("formKlien");
    const popupTambah = document.getElementById("popupTambah");
    
    // ==================== VIEW FUNCTIONS ====================
    window.showTable = function () {
        document.getElementById("tableView")?.classList.remove("hidden");
        document.getElementById("cardView")?.classList.add("hidden");
    };

    window.showCard = function () {
        document.getElementById("tableView")?.classList.add("hidden");
        document.getElementById("cardView")?.classList.remove("hidden");
    };
    
    window.openPopup = function (type) {
        closeAllPopup();
        const target = document.getElementById("popup" + type.charAt(0).toUpperCase() + type.slice(1));
        if (target) target.classList.add("open");
    };
    
    window.filterStatus = function (status) {
        const rows = document.querySelectorAll("#tableBody tr");
        rows.forEach(row => {
            const statusText = row.querySelector(".status")?.innerText.toLowerCase() || '';
            if (status === "all") {
                row.style.display = "";
            } else if (status === "aktif" && statusText.includes("aktif")) {
                row.style.display = "";
            } else if (status === "tidak" && (statusText.includes("tidak") || statusText.includes("expired"))) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    };
    
    // Close popup buttons
    document.querySelectorAll(".close, .close2").forEach(btn => {
        btn.addEventListener("click", () => {
            closeAllPopup();
            resetForm();
        });
    });
    
    // Close popup when clicking outside
    if (popupTambah) {
        popupTambah.addEventListener("click", function (e) {
            if (e.target === popupTambah) {
                resetForm();
                closeAllPopup();
            }
        });
    }
    
    // Close popup edit when clicking outside
    const popupEdit = document.getElementById("popupEdit");
    if (popupEdit) {
        popupEdit.addEventListener("click", function (e) {
            if (e.target === popupEdit) {
                closeAllPopup();
            }
        });
    }
    
    // Close popup hapus when clicking outside
    const popupHapus = document.getElementById("popupHapus");
    if (popupHapus) {
        popupHapus.addEventListener("click", function (e) {
            if (e.target === popupHapus) {
                closeAllPopup();
            }
        });
    }
    
    // Close popup lihat when clicking outside
    const popupLihat = document.getElementById("popupLihat");
    if (popupLihat) {
        popupLihat.addEventListener("click", function (e) {
            if (e.target === popupLihat) {
                closeAllPopup();
            }
        });
    }
    
    // ==================== FORM SUBMIT (CREATE) ====================
    if (form) {
        form.addEventListener("submit", async function (e) {
            e.preventDefault();
            
            const formData = {
                nama: document.getElementById("nama")?.value || '',
                perusahaan: document.getElementById("perusahaan")?.value || '',
                email: document.getElementById("email")?.value || '',
                nomer: document.getElementById("nomer")?.value || '',
                pendapatan: document.getElementById("pendapatan")?.value || 0,
                mulai: document.getElementById("mulai")?.value || '',
                akhir: document.getElementById("akhir")?.value || ''
            };
            
            await storeKlien(formData);
            resetForm();
            closeAllPopup();
        });
    }
    
    // ==================== DELETE CONFIRMATION ====================
    const btnHapus = document.querySelector(".hapusred");
    if (btnHapus) {
        btnHapus.addEventListener("click", async () => {
            if (deleteRow) {
                const id = deleteRow.getAttribute('data-id');
                await deleteKlien(id);
                deleteRow = null;
            }
            closeAllPopup();
        });
    }
    
    // ==================== SEARCH FUNCTION ====================
    const searchInput = document.getElementById("searchInput");
    if (searchInput) {
        searchInput.addEventListener("keyup", function () {
            const keyword = this.value.toLowerCase();
            document.querySelectorAll("#tableBody tr").forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(keyword) ? "" : "none";
            });
        });
    }
    
    // ==================== POPUP TAMBAH BUTTON ====================
    const btnTambah = document.querySelector('.open-add[data-type="tambah"]');
    if (btnTambah) {
        btnTambah.addEventListener('click', function () {
            closeAllPopup();
            if (popupTambah) popupTambah.classList.add('open');
        });
    }
});

// ==================== TABLE CLICK HANDLER (LIHAT, EDIT, DELETE) ====================
document.addEventListener("DOMContentLoaded", function () {
    const tableBody = document.querySelector("#tableBody");
    
    if (!tableBody) {
        console.log("Table body tidak ditemukan");
        return;
    }
    
    tableBody.addEventListener("click", function (e) {
        // Cari icon yang diklik
        let btn = e.target.closest(".icon");
        if (!btn) return;
        
        // Cari row terdekat
        const row = btn.closest("tr");
        if (!row) return;
        
        const id = row.getAttribute('data-id');
        const phone = row.getAttribute('data-phone') || '-';
        const mulai = row.getAttribute('data-mulai') || '';
        const akhir = row.getAttribute('data-akhir') || '';
        const pendapatan = row.getAttribute('data-pendapatan') || 0;
        
        console.log("ID:", id, "Phone:", phone);
        
        // ========== LIHAT ==========
        if (btn.classList.contains("lihat")) {
            console.log("Membuka popup lihat");
            
            const nama = row.querySelector(".klien span")?.innerText || '';
            const perusahaan = row.children[1]?.innerText || '';
            const email = row.children[2]?.innerText || '';
            
            let durasiText = '-';
            let sisaText = '-';
            
            if (mulai && akhir) {
                const start = new Date(mulai);
                const end = new Date(akhir);
                const diffTime = Math.abs(end - start);
                const diffYears = diffTime / (1000 * 60 * 60 * 24 * 365);
                const tahun = Math.round(diffYears * 10) / 10;
                durasiText = `${tahun} Tahun`;
                
                const now = new Date();
                const sisaHari = Math.ceil((end - now) / (1000 * 60 * 60 * 24));
                sisaText = sisaHari > 0 ? `${sisaHari} hari lagi` : 'Sudah berakhir';
            }
            
            // Update avatar
            const avatarEl = document.querySelector("#popupLihat .avatar-table");
            if (avatarEl) avatarEl.innerText = nama.substring(0, 2).toUpperCase();
            
            // Update fields
            document.getElementById("lihatNama").innerText = nama;
            document.getElementById("lihatPerusahaan").innerText = perusahaan;
            document.getElementById("lihatEmail").innerText = email;
            document.getElementById("lihatPhone").innerText = phone;
            document.getElementById("lihatPendapatan").innerText = formatRupiah(pendapatan);
            document.getElementById("lihatMulai").innerText = formatTanggal(mulai);
            document.getElementById("lihatAkhir").innerText = formatTanggal(akhir);
            document.getElementById("lihatDurasi").innerText = durasiText;
            document.getElementById("lihatSisa").innerText = sisaText;
            
            // Update status
            const statusEl = document.querySelector("#popupLihat .status");
            if (statusEl) {
                if (sisaText.includes('hari lagi')) {
                    statusEl.className = "status aktif2";
                    statusEl.innerText = "✔ Aktif";
                } else {
                    statusEl.className = "status nonaktif2";
                    statusEl.innerText = "✘ Tidak Aktif";
                }
            }
            
            document.getElementById("popupLihat")?.classList.add("open");
        }
        
        // ========== EDIT ==========
        if (btn.classList.contains("edit")) {
            console.log("Membuka popup edit - ID:", id);
            editRow = row;
            currentEditId = id;
            
            // Ambil data dari row
            const nama = row.querySelector(".klien span")?.innerText || '';
            const perusahaan = row.children[1]?.innerText || '';
            const email = row.children[2]?.innerText || '';
            
            console.log("Data edit - Nama:", nama, "Phone:", phone);
            
            // Isi form edit
            document.getElementById("editNama").value = nama;
            document.getElementById("editPerusahaan").value = perusahaan;
            document.getElementById("editEmail").value = email;
            document.getElementById("editNomer").value = phone;
            document.getElementById("editMulai").value = mulai;
            document.getElementById("editAkhir").value = akhir;
            document.getElementById("editPendapatan").value = pendapatan;
            
            // Hitung dan set durasi
            if (mulai && akhir && document.getElementById("editDurasi")) {
                const start = new Date(mulai);
                const end = new Date(akhir);
                const diffTime = Math.abs(end - start);
                const diffYears = diffTime / (1000 * 60 * 60 * 24 * 365);
                const tahun = Math.round(diffYears);
                document.getElementById("editDurasi").value = tahun > 0 ? tahun : '';
            }
            
            // Buka popup edit
            const popupEdit = document.getElementById("popupEdit");
            if (popupEdit) {
                popupEdit.classList.add("open");
                console.log("Popup edit opened");
            } else {
                console.log("Popup edit element tidak ditemukan!");
            }
        }
        
        // ========== DELETE ==========
        if (btn.classList.contains("delete")) {
            console.log("Membuka popup hapus");
            deleteRow = row;
            
            const namaKlien = row.querySelector(".klien span")?.innerText || '';
            const hapusText = document.querySelector("#popupHapus .nama-klien");
            if (hapusText) {
                hapusText.innerHTML = `"${namaKlien}"`;
            }
            
            document.getElementById("popupHapus")?.classList.add("open");
        }
    });

});

// ==================== UPDATE HANDLER ====================
function initUpdateHandler() {
    const btnUpdate = document.getElementById("btnUpdate");
    if (!btnUpdate) {
        console.log("Tombol update tidak ditemukan");
        return;
    }
    
    btnUpdate.addEventListener("click", async function (e) {
        e.preventDefault();
        console.log("Tombol update diklik");
        
        if (!editRow || !currentEditId) {
            showNotification('Data tidak ditemukan!', 'error');
            return;
        }
        
        const formData = {
            name: document.getElementById("editNama")?.value || '',
            company: document.getElementById("editPerusahaan")?.value || '',
            email: document.getElementById("editEmail")?.value || '',
            phone_number: document.getElementById("editNomer")?.value || '',
            subscription_end_date: document.getElementById("editAkhir")?.value || '',
            revenue: document.getElementById("editPendapatan")?.value || 0,
            status: 'aktif'  // ✅ Kembali ke 'aktif'
        };
        
        if (!formData.name || !formData.email) {
            showNotification('Nama dan Email harus diisi!', 'error');
            return;
        }
        
        const success = await updateKlien(currentEditId, formData);
        
        if (success) {
            document.getElementById("popupEdit")?.classList.remove("open");
            editRow = null;
            currentEditId = null;
        }
    });
}

// ==================== EDIT FORM AUTO CALCULATE ====================
function initEditDateCalculations() {
    const editMulai = document.getElementById("editMulai");
    const editDurasi = document.getElementById("editDurasi");
    const editAkhir = document.getElementById("editAkhir");
    
    function hitungTanggalEdit() {
        if (editMulai?.value && editDurasi?.value) {
            let d = new Date(editMulai.value);
            d.setFullYear(d.getFullYear() + parseInt(editDurasi.value));
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            if (editAkhir) editAkhir.value = `${y}-${m}-${day}`;
        }
    }
    
    if (editDurasi) editDurasi.addEventListener("change", hitungTanggalEdit);
    if (editMulai) editMulai.addEventListener("change", hitungTanggalEdit);
}

// ==================== UPDATE HANDLER ====================
function initUpdateHandler() {
    const btnUpdate = document.getElementById("btnUpdate");
    if (!btnUpdate) {
        console.log("Tombol update tidak ditemukan");
        return;
    }
    
    btnUpdate.addEventListener("click", async function (e) {
        e.preventDefault();
        console.log("Tombol update diklik");
        
        if (!editRow || !currentEditId) {
            showNotification('Data tidak ditemukan!', 'error');
            return;
        }
        
        const formData = {
            name: document.getElementById("editNama")?.value || '',
            company: document.getElementById("editPerusahaan")?.value || '',
            email: document.getElementById("editEmail")?.value || '',
            phone_number: document.getElementById("editNomer")?.value || '',
            subscription_end_date: document.getElementById("editAkhir")?.value || '',
            revenue: document.getElementById("editPendapatan")?.value || 0,
            status: 'aktif'
        };
        
        if (!formData.name || !formData.email) {
            showNotification('Nama dan Email harus diisi!', 'error');
            return;
        }
        
        const success = await updateKlien(currentEditId, formData);
        
        if (success) {
            document.getElementById("popupEdit")?.classList.remove("open");
            editRow = null;
            currentEditId = null;
        }
    });
}

// Inisialisasi semua
document.addEventListener("DOMContentLoaded", function () {
    initEditDateCalculations();
    initUpdateHandler();
});

// ================== SIDEBAR AVATAR SESUAI LOGIN ==================
const avatarSidebar = document.getElementById("avatarSidebar");
const nameSidebar = document.getElementById("userNameSidebar");
const emailSidebar = document.getElementById("userEmailSidebar");

function updateSidebar(user) {
    nameSidebar.textContent = user.name;
    emailSidebar.textContent = user.email;
    if (avatarSidebar) {
        const nameParts = user.name.split(" ").slice(0, 2); // ambil 2 kata pertama
        avatarSidebar.textContent = nameParts.map(w => w[0]).join("").toUpperCase();
    }
}

// Ambil data dari localStorage (login.js)
const userName = localStorage.getItem("user_name");
const userEmail = localStorage.getItem("user_email");

if (userName && userEmail) {
    updateSidebar({ name: userName, email: userEmail });
} else {
    // fallback: fetch API jika localStorage kosong
    async function fetchLoginUser() {
        try {
            const token = document.querySelector('meta[name="csrf-token"]').content;
            const res = await fetch('/account', {
                method: 'GET',
                credentials: 'include',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': token }
            });
            const data = await res.json();
            if (data.success && data.user) {
                updateSidebar(data.user);
            }
        } catch (err) {
            console.error("Gagal ambil user login:", err);
        }
    }
    fetchLoginUser();
}



// CHART 1 - BAR
