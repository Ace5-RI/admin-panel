// ================= VIEW =================
function showTable(){
    document.getElementById("tableView").style.display = "block";
    document.getElementById("cardView").style.display = "none";
}

function showCard(){
    document.getElementById("tableView").style.display = "none";
    document.getElementById("cardView").style.display = "block";
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
function myFunction() {
    document.getElementById("myDropdown").classList.toggle("show");
}

window.onclick = function(event) {
    if (!event.target.matches('.dropbtn')) {
        document.querySelectorAll(".dropdown-content").forEach(drop => {
            drop.classList.remove("show");
        });
    }
};