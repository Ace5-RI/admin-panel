// login.js - MODIFIKASI (hapus role user)
console.log("JS KELOAD 🔥");

// ==================== JS AWAL ANDA (TETAP) ====================

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

if (showRegister) {
    showRegister.onclick = function(e){
        e.preventDefault();
        card.classList.add("active");
    }
}

if (showLogin) {
    showLogin.onclick = function(e){
        e.preventDefault();
        card.classList.remove("active");
    }
}

// ==================== TAMBAHAN: Ambil CSRF Token ====================
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ==================== HAPUS selectedRole - admin SAJA ====================
// Tidak perlu selectedRole lagi karena hanya admin

// ==================== MODIFIKASI: Login function ====================
async function loginUser(email, password){
    try {
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include'
        });

        const response = await fetch("/login", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ email, password, role: 'admin' }) // ← FIXED role admin
        });

        const text = await response.text();
        console.log("RESPONSE LOGIN:", text);

        let data;
        try {
            data = JSON.parse(text);
        } catch {
            return { success: false, message: text };
        }

        return data;

    } catch (error) {
        console.error(error);
        return { success: false, message: "Terjadi kesalahan server" };
    }
}

// ==================== Register function ====================
async function registerUser(name, email, password, phone){
    try {
        await fetch('/sanctum/csrf-cookie', { credentials: 'include' });

        const res = await fetch('/register', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                name,
                email,
                password,
                password_confirmation: password,
                phone_number: phone
            })
        });

        const text = await res.text();
        let data;
        try {
            data = JSON.parse(text);
        } catch { return { success: false, message: text }; }

        return data;

    } catch (err) {
        console.error(err);
        return { success: false, message: "Server error" };
    }
}

// ==================== FORM LOGIN ====================
document.getElementById("loginForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value.trim();

    if (!email || !password) {
        alert("Email dan password harus diisi!");
        return;
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Loading...";
    submitBtn.disabled = true;

    // Kirim login tanpa parameter role
    const res = await loginUser(email, password);

    if(res.success){
        localStorage.setItem("user_name", res.user.name);
        localStorage.setItem("user_email", res.user.email);
        localStorage.setItem("user_role", res.user.role);
        localStorage.setItem("auth_token", res.token);
        localStorage.setItem("welcome_message", "Selamat datang " + res.user.name);
        
        // Redirect ke dashboard admin
        window.location.href = "/dashboard";
    } else {
        const errorMsg = res.message || res.errors || "Login gagal! Periksa email dan password Anda.";
        alert(errorMsg);
    }
    
    submitBtn.innerText = originalText;
    submitBtn.disabled = false;
});

// ==================== FORM REGISTER ====================
document.getElementById("registerForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const nama = document.getElementById("nama").value.trim();
    const email = document.getElementById("regEmail").value.trim();
    const password = document.getElementById("regPassword").value.trim();
    const phone = document.getElementById("regPhone").value.trim();
    const passwordConfirm = document.getElementById("regPasswordConfirmation").value.trim();
    
    if (!nama || !email || !password) {
        alert("Nama, Email, dan Password harus diisi!");
        return;
    }

    if (password !== passwordConfirm) {
        alert("Password dan Konfirmasi Password tidak cocok!");
        return;
    }

    if (password.length < 6) {
        alert("Password minimal 6 karakter!");
        return;
    }

    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Loading...";
    submitBtn.disabled = true;

    const res = await registerUser(nama, email, password, phone);

    if(res.success){
        Swal.fire({
            title: "Registrasi Berhasil 🎉",
            text: "Silakan login",
            icon: "success",
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            card.classList.remove("active");
        });

        document.getElementById("registerForm").reset();
    } else {
        if (res.errors) {
            let errorMessages = [];
            Object.values(res.errors).forEach(error => {
                errorMessages.push(error.join(', '));
            });
            alert("Validasi gagal:\n" + errorMessages.join('\n'));
        } else {
            alert(res.message || "Register gagal! Silakan coba lagi.");
        }
    }
    
    submitBtn.innerText = originalText;
    submitBtn.disabled = false;
});