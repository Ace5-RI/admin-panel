// 🔴 SEKARANG (pakai localStorage)
// 🔥 NANTI DIGANTI KE API
async function loginUser(email, password){
    const users = JSON.parse(localStorage.getItem("users")) || [];

    const user = users.find(u => 
        u.email === email && 
        u.password === password
    );

    if(user){
        return { success: true, user };
    } else {
        return { success: false };
    }
}

// 🔴 SEKARANG (pakai localStorage)
// 🔥 NANTI DIGANTI KE API
async function registerUser(nama, email, password){
    const users = JSON.parse(localStorage.getItem("users")) || [];

    const existingUser = users.find(u => u.email === email);
    if(existingUser){
        return { success: false, message: "Email sudah terdaftar!" };
    }

    users.push({ nama, email, password });
    localStorage.setItem("users", JSON.stringify(users));

    return { success: true };
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