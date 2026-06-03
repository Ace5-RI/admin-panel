



// ==================== VARIABEL GLOBAL ====================
let deleteRow = null;
let editRow = null;
let currentEditId = null;

// ==================== AUTO FORMAT RUPIAH ====================
function initRupiahFormat() {
    const pendapatan = document.getElementById('pendapatan');
    const editPendapatan = document.getElementById('editPendapatan');
    
    function formatRupiah(angka) {
        // Hapus semua karakter selain angka
        let bersih = angka.replace(/[^0-9]/g, '');
        if (!bersih) return '';
        
        // Balikin ke angka (support besar)
        let nilai = parseFloat(bersih);
        if (isNaN(nilai)) return '';
        
        // Format dengan titik per 3 digit
        return nilai.toLocaleString('id-ID');
    }
    
    if (pendapatan) {
        pendapatan.addEventListener('input', function(e) {
            let formatted = formatRupiah(this.value);
            if (formatted) this.value = formatted;
        });
        
        pendapatan.closest('form')?.addEventListener('submit', function() {
            if (pendapatan) pendapatan.value = pendapatan.value.replace(/\./g, '');
        });
    }
    
    if (editPendapatan) {
        editPendapatan.addEventListener('input', function(e) {
            let formatted = formatRupiah(this.value);
            if (formatted) this.value = formatted;
        });
        
        editPendapatan.closest('form')?.addEventListener('submit', function() {
            if (editPendapatan) editPendapatan.value = editPendapatan.value.replace(/\./g, '');
        });
    }
}

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

// ==================== FUNGSI RENDER STATUS SISA HARI ====================
function getWarnaSisaHari(daysLeft) {
    if (daysLeft <= 0) return "expired";
    if (daysLeft <= 5) return "danger";
    if (daysLeft <= 15) return "warning";
    if (daysLeft <= 20) return "info";
    return "active";
}

function getIconSisaHari(daysLeft) {
    if (daysLeft <= 0) return "❌";
    if (daysLeft <= 5) return "🔴";
    if (daysLeft <= 15) return "🟠";
    if (daysLeft <= 20) return "🟡";
    return "✅";  // 21 hari ke atas - icon centang hijau
}

function getTextSisaHari(daysLeft) {
    if (daysLeft <= 0) return "Berakhir";
    if (daysLeft <= 5) return `${daysLeft} hari`;
    if (daysLeft <= 15) return `${daysLeft} hari`;
    if (daysLeft <= 20) return `${daysLeft} hari`;
    if (daysLeft <= 30) return `${daysLeft} hari`;  // 21-30 hari: tampilkan angka
    return "Aktif";  // >30 hari: cukup "Aktif"
}

function renderAllStatus() {
    const statusCells = document.querySelectorAll('.status-cell');
    
    statusCells.forEach(cell => {
        const endDateStr = cell.getAttribute('data-end-date');
        if (!endDateStr) return;
        
        const today = new Date();
        const endDate = new Date(endDateStr);
        const daysLeft = Math.ceil((endDate - today) / (1000 * 60 * 60 * 24));
        
        const warna = getWarnaSisaHari(daysLeft);
        const icon = getIconSisaHari(daysLeft);
        const text = getTextSisaHari(daysLeft);
        
        const statusHtml = `
            <span class="status status-${warna}">
                <span class="status-icon">${icon}</span>
                <span class="status-text">${text}</span>
            </span>
        `;
        
        cell.innerHTML = statusHtml;
    });
}

// Panggil render status saat halaman load
document.addEventListener("DOMContentLoaded", function() {
    renderAllStatus();
});

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

// ==================== DROPDOWN 1 - FILTER ====================
window.toggleDropdown = function(event) {
    if (event) event.preventDefault();
    document.getElementById("myDropdown").classList.toggle("show");
}

window.filterStatus = function(status, event) {
    if (event) event.preventDefault();
    
    const rows = document.querySelectorAll("#tableBody tr");
    rows.forEach(row => {
        const endDate = row.getAttribute('data-akhir');
        if (!endDate) {
            row.style.display = "none";
            return;
        }
        
        const today = new Date();
        const end = new Date(endDate);
        const daysLeft = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
        
        let show = false;
        
        if (status === "all") {
            show = true;
        } else if (status === "aktif" && daysLeft > 20) {
            show = true;
        } else if (status === "akan_berakhir" && daysLeft > 0 && daysLeft <= 20) {
            show = true;
        } else if (status === "berakhir" && daysLeft <= 0) {
            show = true;
        }
        
        row.style.display = show ? "" : "none";
    });
    
    document.getElementById("myDropdown")?.classList.remove("show");
}

// ==================== DROPDOWN 2 - SORT ====================
window.toggleSortDropdown = function(event) {
    if (event) event.preventDefault();
    document.getElementById("sortDropdown").classList.toggle("show");
}

window.sortTable = function(type, event) {
    if (event) event.preventDefault();
    
    const tbody = document.getElementById("tableBody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    
    rows.sort((a, b) => {
        const endDateA = new Date(a.getAttribute('data-akhir'));
        const endDateB = new Date(b.getAttribute('data-akhir'));
        const today = new Date();
        
        const daysLeftA = Math.ceil((endDateA - today) / (1000 * 60 * 60 * 24));
        const daysLeftB = Math.ceil((endDateB - today) / (1000 * 60 * 60 * 24));
        
        if (type === 'tercepat') {
            return daysLeftA - daysLeftB;
        } else if (type === 'terlama') {
            return daysLeftB - daysLeftA;
        }
        return 0;
    });
    
    rows.forEach(row => tbody.appendChild(row));
    document.getElementById("sortDropdown").classList.remove("show");
}

// ==================== DEFAULT SORT (TERCEPAT) SAAT LOAD ====================
function defaultSort() {
    const tbody = document.getElementById("tableBody");
    const rows = Array.from(tbody.querySelectorAll("tr"));
    
    rows.sort((a, b) => {
        const endDateA = new Date(a.getAttribute('data-akhir'));
        const endDateB = new Date(b.getAttribute('data-akhir'));
        const today = new Date();
        
        const daysLeftA = Math.ceil((endDateA - today) / (1000 * 60 * 60 * 24));
        const daysLeftB = Math.ceil((endDateB - today) / (1000 * 60 * 60 * 24));
        
        return daysLeftA - daysLeftB;
    });
    
    rows.forEach(row => tbody.appendChild(row));
}

// Panggil default sort saat halaman load
document.addEventListener("DOMContentLoaded", function() {
    defaultSort();
});

// ==================== TUTUP DROPDOWN SAAT KLIK DI LUAR ====================
window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        const dropdowns = document.getElementsByClassName("dropdown-content");
        for (let i = 0; i < dropdowns.length; i++) {
            const openDropdown = dropdowns[i];
            if (openDropdown.classList.contains('show')) {
                openDropdown.classList.remove('show');
            }
        }
    }
}
// ==================== EVENT LISTENERS ====================
document.addEventListener("DOMContentLoaded", function () {
    
    // Init date calculations
    initDateCalculations();
    
    initRupiahFormat(); 
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
            const endDate = row.getAttribute('data-akhir');
            if (!endDate) {
                row.style.display = "none";
                return;
            }
            
            const today = new Date();
            const end = new Date(endDate);
            const daysLeft = Math.ceil((end - today) / (1000 * 60 * 60 * 24));
            
            let show = false;
            
            if (status === "all") {
                show = true;
            } else if (status === "aktif" && daysLeft > 30) {
                show = true;
            } else if (status === "akan_berakhir" && daysLeft > 0 && daysLeft <= 30) {
                show = true;
            } else if (status === "berakhir" && daysLeft <= 0) {
                show = true;
            }
            
            row.style.display = show ? "" : "none";
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
        
        // Ambil nilai pendapatan dan bersihkan titik
        let pendapatanValue = document.getElementById("pendapatan")?.value || '0';
        let pendapatanBersih = pendapatanValue.replace(/\./g, ''); // hapus titik
        
        const formData = {
            nama: document.getElementById("nama")?.value || '',
            perusahaan: document.getElementById("perusahaan")?.value || '',
            email: document.getElementById("email")?.value || '',
            nomer: document.getElementById("nomer")?.value || '',
            description: document.getElementById("editDeskripsi")?.value || '',
            pendapatan: parseInt(pendapatanBersih) || 0,  // <== pake yang udah bersih
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
    
    // ==================== TABLE CLICK HANDLER (LIHAT, EDIT, DELETE) ====================
    const tableBody = document.querySelector("#tableBody");
    
    if (!tableBody) {
        console.log("Table body tidak ditemukan");
        return;
    }
    
    tableBody.addEventListener("click", function (e) {
        let btn = e.target.closest(".icon");
        if (!btn) return;
        
        const row = btn.closest("tr");
        if (!row) return;
        
        const id = row.getAttribute('data-id');
        const phone = row.getAttribute('data-phone') || '-';
        const mulai = row.getAttribute('data-mulai') || '';
        const akhir = row.getAttribute('data-akhir') || '';
        const pendapatan = row.getAttribute('data-pendapatan') || 0;
        const description = row.getAttribute('data-description') || '-';  // TAMBAH INI
        
        // ========== LIHAT ==========
        if (btn.classList.contains("lihat")) {
            const nama = row.querySelector(".klien span")?.innerText || '';
            const perusahaan = row.children[1]?.innerText || '';
            const email = row.children[2]?.innerText || '';
            const revenue = row.children[5]?.innerText || 'Rp 0';
            
            function formatTgl(tgl) {
                if (!tgl) return '-';
                const d = new Date(tgl);
                const bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                return `${d.getDate()} ${bulan[d.getMonth()]} ${d.getFullYear()}`;
            }
            
            let durasi = '-';
            let sisa = '-';
            
            if (mulai && akhir) {
                const start = new Date(mulai);
                const end = new Date(akhir);
                const today = new Date();
                
                const diffDays = Math.floor((end - start) / (1000 * 60 * 60 * 24));
                const tahunDurasi = Math.floor(diffDays / 365);
                const bulanDurasi = Math.floor((diffDays % 365) / 30);
                const hariDurasi = (diffDays % 365) % 30;
                
                if (tahunDurasi > 0 && bulanDurasi > 0 && hariDurasi > 0) {
                    durasi = `${tahunDurasi} tahun ${bulanDurasi} bulan ${hariDurasi} hari`;
                } else if (tahunDurasi > 0 && bulanDurasi > 0) {
                    durasi = `${tahunDurasi} tahun ${bulanDurasi} bulan`;
                } else if (tahunDurasi > 0 && hariDurasi > 0) {
                    durasi = `${tahunDurasi} tahun ${hariDurasi} hari`;
                } else if (tahunDurasi > 0) {
                    durasi = `${tahunDurasi} tahun`;
                } else if (bulanDurasi > 0 && hariDurasi > 0) {
                    durasi = `${bulanDurasi} bulan ${hariDurasi} hari`;
                } else if (bulanDurasi > 0) {
                    durasi = `${bulanDurasi} bulan`;
                } else {
                    durasi = `${hariDurasi} hari`;
                }
                
                const sisaDays = Math.floor((end - today) / (1000 * 60 * 60 * 24));
                
                if (sisaDays > 0) {
                    const tahunSisa = Math.floor(sisaDays / 365);
                    const bulanSisa = Math.floor((sisaDays % 365) / 30);
                    const hariSisa = (sisaDays % 365) % 30;
                    
                    if (tahunSisa > 0 && bulanSisa > 0 && hariSisa > 0) {
                        sisa = `Berakhir dalam ${tahunSisa} tahun ${bulanSisa} bulan ${hariSisa} hari`;
                    } else if (tahunSisa > 0 && bulanSisa > 0) {
                        sisa = `Berakhir dalam ${tahunSisa} tahun ${bulanSisa} bulan`;
                    } else if (tahunSisa > 0 && hariSisa > 0) {
                        sisa = `Berakhir dalam ${tahunSisa} tahun ${hariSisa} hari`;
                    } else if (tahunSisa > 0) {
                        sisa = `Berakhir dalam ${tahunSisa} tahun`;
                    } else if (bulanSisa > 0 && hariSisa > 0) {
                        sisa = `Berakhir dalam ${bulanSisa} bulan ${hariSisa} hari`;
                    } else if (bulanSisa > 0) {
                        sisa = `Berakhir dalam ${bulanSisa} bulan`;
                    } else if (hariSisa > 0) {
                        sisa = `Berakhir dalam ${hariSisa} hari`;
                    } else {
                        sisa = `Berakhir hari ini!`;
                    }
                } else if (sisaDays === 0) {
                    sisa = `Berakhir hari ini!`;
                } else {
                    sisa = `Sudah berakhir ${Math.abs(sisaDays)} hari yang lalu`;
                }
            }
            
            document.getElementById('lihatAvatar').innerText = nama.substring(0, 2).toUpperCase();
            document.getElementById('lihatNama').innerText = nama;
            document.getElementById('lihatEmail').innerText = email;
            document.getElementById('lihatStatus').innerText = sisa.includes('Berakhir dalam') || sisa.includes('Berakhir hari') ? 'Aktif' : 'Tidak Aktif';
            document.getElementById('lihatPerusahaan').innerText = perusahaan;
            document.getElementById('lihatPhone').innerText = phone;
            document.getElementById('lihatDeskripsi').innerText = description;
            document.getElementById('lihatPendapatan').innerText = revenue;
            document.getElementById('lihatMulai').innerText = formatTgl(mulai);
            document.getElementById('lihatAkhir').innerText = formatTgl(akhir);
            document.getElementById('lihatDurasi').innerText = durasi;
            document.getElementById('lihatSisa').innerText = sisa;
            
            document.getElementById("popupLihat")?.classList.add("open");
        }
        
        // ========== EDIT ==========
        if (btn.classList.contains("edit")) {
            editRow = row;
            currentEditId = id;
            
            const nama = row.querySelector(".klien span")?.innerText || '';
            const perusahaan = row.children[1]?.innerText || '';
            const email = row.children[2]?.innerText || '';
            const description = row.getAttribute('data-description') || '';
            
            document.getElementById("editNama").value = nama;
            document.getElementById("editPerusahaan").value = perusahaan;
            document.getElementById("editEmail").value = email;
            document.getElementById("editNomer").value = phone;
            document.getElementById("editDeskripsi").value = description;
            document.getElementById("editMulai").value = mulai;
            document.getElementById("editAkhir").value = akhir;
            document.getElementById("editPendapatan").value = pendapatan;
            
            if (mulai && akhir && document.getElementById("editDurasi")) {
                const start = new Date(mulai);
                const end = new Date(akhir);
                const diffTime = Math.abs(end - start);
                const diffYears = diffTime / (1000 * 60 * 60 * 24 * 365);
                const tahun = Math.round(diffYears);
                document.getElementById("editDurasi").value = tahun > 0 ? tahun : '';
            }
            
            const popupEdit = document.getElementById("popupEdit");
            if (popupEdit) {
                popupEdit.classList.add("open");
            }
        }
        
        // ========== DELETE ==========
        if (btn.classList.contains("delete")) {
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
        
        if (!editRow || !currentEditId) {
            showNotification('Data tidak ditemukan!', 'error');
            return;
        }
        
        let revenueValue = document.getElementById("editPendapatan")?.value || '0';
let revenueBersih = revenueValue.replace(/\./g, '');

const formData = {
    name: document.getElementById("editNama")?.value || '',
    company: document.getElementById("editPerusahaan")?.value || '',
    email: document.getElementById("editEmail")?.value || '',
    phone_number: document.getElementById("editNomer")?.value || '',
    description: document.getElementById("editDeskripsi")?.value || '',
    subscription_end_date: document.getElementById("editAkhir")?.value || '',
    revenue: parseInt(revenueBersih) || 0,  // <== pake yang udah bersih
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

// Inisialisasi
document.addEventListener("DOMContentLoaded", function () {
    initEditDateCalculations();
    initUpdateHandler();
});

// ================== SIDEBAR AVATAR SESUAI LOGIN ==================
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

if (logoutBtn) {
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
            // Buat form POST dan submit — cara paling reliable
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/logout';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            form.appendChild(csrf);
            document.body.appendChild(form);
            form.submit();
        }
    });
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