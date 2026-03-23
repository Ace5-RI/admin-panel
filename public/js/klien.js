function showTable(){
    document.getElementById("tableView").style.display = "block";
    document.getElementById("cardView").style.display = "none";
}

function showCard(){
    document.getElementById("tableView").style.display = "none";
    document.getElementById("cardView").style.display = "block";
}

const openBtn = document.getElementById("open-add");
const openBtns = document.querySelectorAll(".open-add");

// tombol tambah
openBtn.addEventListener("click", () => {
    openPopup("tambah");
});

// icon tabel (lihat, edit, hapus)
openBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        const type = btn.dataset.type;
        openPopup(type);
    });
});

// fungsi buka popup
function openPopup(type) {
    document.querySelectorAll(".add").forEach(p => {
        p.classList.remove("open");
    });

    const target = document.getElementById("popup" + capitalize(type));
    if (target) {
        target.classList.add("open");
    }
}

function capitalize(text) {
    return text.charAt(0).toUpperCase() + text.slice(1);
}

// tombol close semua popup
document.querySelectorAll(".close").forEach(btn => {
    btn.addEventListener("click", () => {
        document.querySelectorAll(".add").forEach(p => {
            p.classList.remove("open");
        });
    });
});

// FIX tombol batal popup tambah
document.getElementById("closeadd").addEventListener("click", () => {
    document.getElementById("popupTambah").classList.remove("open");
});

document.querySelectorAll(".open-add").forEach(btn => {
    btn.addEventListener("click", () => {
        if(btn.dataset.type === "lihat") {

            const row = btn.closest("tr");

           const nama = row.querySelector("span").innerText;

document.querySelector(".avatar").innerText =
    nama.split(" ").map(n => n[0]).join("").toUpperCase();

document.getElementById("lihatPerusahaan2").innerText = perusahaan;
            const email = row.children[2].innerText;
            const perusahaan = row.children[1].innerText;

            document.getElementById("lihatNama").innerText = nama;
            document.getElementById("lihatEmail").innerText = email;
            document.getElementById("lihatPerusahaan").innerText = perusahaan;
        }
    });
});

document.querySelectorAll(".avatar-table").forEach(el => {
    const nama = el.parentElement.querySelector("span").innerText;
    el.innerText = nama.split(" ").map(n => n[0]).join("").toUpperCase();
});