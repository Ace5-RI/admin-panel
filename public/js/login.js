// login.js - JS AWAL ANDA (tetap dipertahankan)
// Hanya menambahkan bagian yang diperlukan

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
// Ambil token dari meta tag (tambahkan ini)
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

// ==================== TAMBAHAN: Simpan role yang dipilih ====================
let selectedRole = 'admin'; // default

// Update role saat tombol diklik
buttons.forEach(button => {
    button.addEventListener('click', function(){
        selectedRole = this.innerText.toLowerCase(); // 'admin' atau 'user'
    });
});

// ==================== MODIFIKASI: Login function ====================
// Tambahkan parameter 'role' dan 'csrfToken'
async function loginUser(email, password, role){
    try {
        // 🔥 TAMBAH DI SINI (PALING ATAS)
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include'
        });

        // 🔥 BARU LOGIN
        const response = await fetch("/login", {
            method: "POST",
            credentials: "include", // 🔥 WAJIB
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ email, password, role })
        });

       const text = await response.text();
console.log("RESPONSE REGISTER:", text);

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

// ==================== MODIFIKASI: Register function ====================
// Tambahkan parameter 'phone', 'address', dan 'password_confirmation'
async function registerUser(name, email, password, phone){
    try {
        // 🔥 WAJIB TAMBAH DI SINI
        await fetch('/sanctum/csrf-cookie', {
            credentials: 'include'
        });

        const response = await fetch("/register", {
            method: "POST",
            credentials: "include", // 🔥 WAJIB
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({ 
                name,
                email,
                password,
                password_confirmation: password,
                phone_number: phone
            })
        });

        const text = await response.text();
console.log("REGISTER RESPONSE:", text);

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

// ==================== JS AWAL ANDA (FORM LOGIN) ====================
document.getElementById("loginForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const email = document.getElementById("loginEmail").value.trim();
    const password = document.getElementById("loginPassword").value.trim();

    // ==================== TAMBAHAN: Validasi sederhana ====================
    if (!email || !password) {
        alert("Email dan password harus diisi!");
        return;
    }

    // ==================== TAMBAHAN: Loading state ====================
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Loading...";
    submitBtn.disabled = true;

    // ==================== TAMBAHAN: Kirim role juga ====================
    const res = await loginUser(email, password, selectedRole);

    if(res.success){
        // ==================== TAMBAHAN: Simpan data user ====================
        localStorage.setItem("user_name", res.user.name);
        localStorage.setItem("user_email", res.user.email);
        localStorage.setItem("user_role", res.user.role);
        localStorage.setItem("auth_token", res.token);
        localStorage.setItem("welcome_message", "Selamat datang " + res.user.name);
        
        
        // ==================== TAMBAHAN: Redirect ====================
        window.location.href = "/dashboard";
    } else {
        // ==================== TAMBAHAN: Tampilkan error detail ====================
        const errorMsg = res.message || res.errors || "Login gagal! Periksa email dan password Anda.";
        alert(errorMsg);
    }
    
    // ==================== TAMBAHAN: Kembalikan tombol ====================
    submitBtn.innerText = originalText;
    submitBtn.disabled = false;
});

// ==================== JS AWAL ANDA (FORM REGISTER) ====================
document.getElementById("registerForm").addEventListener("submit", async function(e){
    e.preventDefault();

    const nama = document.getElementById("nama").value.trim();
const email = document.getElementById("regEmail").value.trim();
const password = document.getElementById("regPassword").value.trim();
const phone = document.getElementById("regPhone").value.trim();
const passwordConfirm = document.getElementById("regPasswordConfirmation").value.trim();
    
    // ==================== TAMBAHAN: Ambil field tambahan ====================
   

    // ==================== TAMBAHAN: Validasi ====================
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

    // ==================== TAMBAHAN: Loading state ====================
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerText;
    submitBtn.innerText = "Loading...";
    submitBtn.disabled = true;

    // ==================== MODIFIKASI: Panggil register dengan parameter lengkap ====================
    const res = await registerUser(nama, email, password, phone);

    if(res.success){

Swal.fire({
    title: "Registrasi Berhasil 🎉",
    text: "Silakan login",
    icon: "success",
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    card.classList.remove("active"); // pindah ke login
});

    // reset form
    document.getElementById("registerForm").reset(); // Kembali ke form login
        
    } else {
        // ==================== TAMBAHAN: Tampilkan error detail ====================
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
    
    // ==================== TAMBAHAN: Kembalikan tombol ====================
    submitBtn.innerText = originalText;
    submitBtn.disabled = false;
});

