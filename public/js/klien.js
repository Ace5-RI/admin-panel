let deleteRow = null;

// ================= VIEW =================
function showTable(){
    document.getElementById("tableView").classList.remove("hidden");
    document.getElementById("cardView").classList.add("hidden");
}

function showCard(){
    document.getElementById("tableView").classList.add("hidden");
    document.getElementById("cardView").classList.remove("hidden");
}

// ================= POPUP CONTROL =================
const openBtns = document.querySelectorAll(".open-add");

openBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const type = btn.dataset.type;
        openPopup(type);
    });
});

function openPopup(type) {
    closeAllPopup();

    const target = document.getElementById("popup" + capitalize(type));
    if (target) target.classList.add("open");
}

function closeAllPopup() {
    document.querySelectorAll(".add").forEach(p => {
        p.classList.remove("open");
    });
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

// tombol close
document.querySelectorAll(".close").forEach(btn => {
    btn.addEventListener("click", closeAllPopup);
});

document.querySelectorAll(".close2").forEach(btn => {
    btn.addEventListener("click", closeAllPopup);
});

// ================= LIHAT DATA =================
document.querySelectorAll(".open-add").forEach(btn => {
    btn.addEventListener("click", () => {

        if (btn.dataset.type === "lihat") {
            const row = btn.closest("tr");

            const nama = row.querySelector("span").innerText;
            const email = row.children[2].innerText;
            const perusahaan = row.children[1].innerText;

            document.getElementById("lihatNama").innerText = nama;
            document.getElementById("lihatEmail").innerText = email;
            document.getElementById("lihatPerusahaan").innerText = perusahaan;
            document.getElementById("lihatPerusahaan2").innerText = perusahaan;

            // avatar otomatis
            document.querySelector(".avatar-table").innerText =
                nama.split(" ").map(n => n[0]).join("").toUpperCase();
        }

    });
});

// ================= DROPDOWN =================
function toggleDropdown() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        document.querySelectorAll(".dropdown-content").forEach(drop => {
            drop.classList.remove("show");
        });
    }
};

function filterStatus(status) {
    const rows = document.querySelectorAll("#tableView tbody tr");

    rows.forEach(row => {
        const statusText = row.querySelector(".status").innerText.toLowerCase();

        if (status === "all") {
            row.style.display = "";
        } else if (status === "aktif" && statusText.includes("aktif")) {
            row.style.display = "";
        } else if (status === "tidak" && !statusText.includes("aktif")) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        document.querySelectorAll(".dropdown-content").forEach(drop => {
            drop.classList.remove("show");
        });
    }
};

const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("keyup", function () {
    const keyword = this.value.toLowerCase();
    const rows = document.querySelectorAll("#tableView tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();

        if (text.includes(keyword)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});

document.addEventListener("DOMContentLoaded", function () {

    const btnHapus = document.querySelector(".hapusred");
const btnBatal = document.querySelector(".close2");

btnHapus.addEventListener("click", function () {
    if (deleteRow) {
        deleteRow.remove();
        deleteRow = null;
    }

    document.getElementById("popupHapus").classList.remove("open");
});

btnBatal.addEventListener("click", function () {
    document.getElementById("popupHapus").classList.remove("open");
});

    const form = document.getElementById("formKlien");
    const table = document.querySelector("#tableView tbody");

    form.addEventListener("submit", function (e) {
        e.preventDefault();

        // Ambil semua input
        const nama = form.querySelector('input[type="text"]').value;
        const email = form.querySelector('input[type="email"]').value;
        const perusahaan = form.querySelectorAll('input[type="text"]')[1].value;
        const pendapatan = form.querySelector('input[type="number"]').value;
        const mulai = form.querySelectorAll('input[type="date"]')[0].value;
        const akhir = form.querySelectorAll('input[type="date"]')[1].value;

        // Ambil inisial avatar
        const inisial = nama.charAt(0).toUpperCase();

        // Format tanggal (biar cantik)
        const formatTanggal = (tgl) => {
            const d = new Date(tgl);
            return d.toLocaleDateString("id-ID", {
                day: "2-digit",
                month: "short",
                year: "numeric"
            });
        };

        // Format uang
        const formatRupiah = (angka) => {
            return "Rp " + Number(angka).toLocaleString("id-ID");
        };

        // Buat row baru
        const row = document.createElement("tr");

        row.innerHTML = `
            <td class="klien">
                <div class="avatar">${inisial}</div>
                <span>${nama}</span>
            </td>
            <td>${perusahaan}</td>
            <td>${email}</td>
            <td>${formatTanggal(akhir)}</td>
            <td>
                <span class="status aktif">✔ Aktif</span>
            </td>
            <td>${formatRupiah(pendapatan)}</td>
            <td>
                <div class="aksi">
                    <span class="icon">👁️</span>
                    <span class="icon">✏️</span>
                    <span class="icon delete">🗑️</span>
                </div>
            </td>
        `;

        // Masukin ke tabel
        table.appendChild(row);

        // Reset form
        form.reset();

        // Tutup popup
        document.getElementById("popupTambah").classList.remove("open");
    });

});

document.addEventListener("DOMContentLoaded", function () {

    const avatarSidebar = document.getElementById("avatarSidebar");
    const nameSidebar = document.getElementById("userNameSidebar");
    const emailSidebar = document.getElementById("userEmailSidebar");
    const roleSidebar = document.getElementById("userRoleSidebar");

    function updateSidebar(user){
        if(nameSidebar) nameSidebar.textContent = user.name;
        if(emailSidebar) emailSidebar.textContent = user.email;
        if(roleSidebar) roleSidebar.textContent = user.role.toUpperCase();

        if(avatarSidebar){
            const nameParts = user.name.split(" ").slice(0,2);
            avatarSidebar.textContent = nameParts.map(w => w[0]).join("").toUpperCase();
        }
    }

   const userLS = localStorage.getItem("user_name");
const emailLS = localStorage.getItem("user_email");

if(userLS && emailLS){
    updateSidebar({
        name: userLS,
        email: emailLS,
        role: "admin" // atau ambil dari backend nanti
    });
}

});

    const form = document.getElementById("formKlien");
    const table = document.querySelector("#tableView tbody");

    let editRow = null; // buat tracking edit

    // FORMAT
    const formatTanggal = (tgl) => {
        const d = new Date(tgl);
        return d.toLocaleDateString("id-ID", {
            day: "2-digit",
            month: "short",
            year: "numeric"
        });
    };

    const formatRupiah = (angka) => {
        return "Rp " + Number(angka).toLocaleString("id-ID");
    };

    // SUBMIT FORM
    form.addEventListener("submit", function (e) {
        e.preventDefault();

        const nama = document.getElementById("nama").value;
        const email = document.getElementById("email").value;
        const perusahaan = document.getElementById("perusahaan").value;
        const pendapatan = document.getElementById("pendapatan").value;
        const akhir = document.getElementById("akhir").value;

        const inisial = nama.charAt(0).toUpperCase();

        // kalau lagi edit
        if (editRow) {
            editRow.innerHTML = buatRowHTML(nama, perusahaan, email, akhir, pendapatan, inisial);
            editRow = null;
        } else {
            const row = document.createElement("tr");
            row.innerHTML = buatRowHTML(nama, perusahaan, email, akhir, pendapatan, inisial);
            table.appendChild(row);
        }

        form.reset();
        document.getElementById("popupTambah").classList.remove("open");
    });

    // TEMPLATE ROW
    function buatRowHTML(nama, perusahaan, email, akhir, pendapatan, inisial) {
        return `
            <td class="klien">
                <div class="avatar">${inisial}</div>
                <span>${nama}</span>
            </td>
            <td>${perusahaan}</td>
            <td>${email}</td>
            <td>${formatTanggal(akhir)}</td>
            <td><span class="status aktif">✔ Aktif</span></td>
            <td>${formatRupiah(pendapatan)}</td>
            <td>
                <div class="aksi">
                    <span class="icon lihat">👁️</span>
                    <span class="icon edit">✏️</span>
                    <span class="icon delete">🗑️</span>
                </div>
            </td>
        `;
    }

    // HANDLE DELETE & EDIT (EVENT DELEGATION)
    table.addEventListener("click", function (e) {

        // DELETE
       if (e.target.classList.contains("delete")) {
    deleteRow = e.target.closest("tr");

    // buka popup hapus
    document.getElementById("popupHapus").classList.add("open");
}

        // EDIT
        if (e.target.classList.contains("edit")) {
            const row = e.target.closest("tr");
            const td = row.querySelectorAll("td");

            document.getElementById("nama").value = td[0].innerText;
            document.getElementById("perusahaan").value = td[1].innerText;
            document.getElementById("email").value = td[2].innerText;

            editRow = row;

            // buka popup
            document.getElementById("popupTambah").classList.add("open");
        }

    });



// ================== LOGOUT ==================
const logoutBtn = document.getElementById("logoutBtn");
if(logoutBtn){
    logoutBtn.addEventListener("click", async ()=>{
        try{
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch('/logout',{
                method:'POST',
                credentials:'include',
                headers:{'X-CSRF-TOKEN':token,'Accept':'application/json'}
            });
            const data = await res.json();
            if(data.success){
                localStorage.clear();
                window.location.href="/login";
            }
        }catch(err){alert("Gagal logout!"); console.error(err);}
    });
}