
async function loginUser(email, password){
    try {
        const response = await fetch("https://..../login", { // ganti URL_TEMANMU sesuai yang dikasih temanmu
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json(); 
        return data; // server harus ngembaliin { success: true, user: {...} } atau { success: false, message: "..." }

    } catch (error) {
        console.error(error);
        return { success: false, message: "Terjadi kesalahan server" };
    }
}


async function registerUser(nama, email, password){
    try {
        const response = await fetch("https://....../register", { // ganti URL_TEMANMU sesuai yang dikasih temanmu
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ nama, email, password })
        });

        const data = await response.json(); 
        return data; // server harus ngembaliin { success: true } atau { success: false, message: "Email sudah terdaftar" }

    } catch (error) {
        console.error(error);
        return { success: false, message: "Terjadi kesalahan server" };
    }
}


console.log("JS KELOAD 🔥");

// ambil semua tombol yang punya class select-btn
const buttons = document.querySelectorAll('.select-btn');

// looping setiap tombol
buttons.forEach(button => {

    // kasih event ketika tombol diklik
    button.addEventListener('click', function(){

        // hapus class active dari semua tombol
        buttons.forEach(btn => {
            btn.classList.remove('active');
        });

        // tambahkan class active ke tombol yang diklik
        this.classList.add('active');

    });

});

const card = document.querySelector(".login-card");
const showRegister = document.getElementById("showRegister");
const showLogin = document.getElementById("showLogin");

showRegister.onclick = function(e){
    e.preventDefault();
    card.classList.add("active");
}

showLogin.onclick = function(e){
    e.preventDefault();
    card.classList.remove("active");
}

document.getElementById("loginForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value.trim();

    const res = await loginUser(email, password);

    if(res.success){
        localStorage.setItem("nama", res.user.nama);
        alert("Login berhasil!");
        // nanti bisa redirect
    } else {
        alert("Login gagal!");
    }
});

//Register

document.getElementById("registerForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const nama = document.getElementById("nama").value;
    const email = document.getElementById("regEmail").value.trim();
const password = document.getElementById("regPassword").value.trim();

    const res = await registerUser(nama, email, password);

    if(res.success){
        alert("Register berhasil!");
    } else {
        alert(res.message);
    }
});