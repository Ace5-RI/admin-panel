console.log("JS KELOAD 🔥");

const buttons = document.querySelectorAll('.select-btn');
buttons.forEach(button => {
    button.addEventListener('click', function(){
        buttons.forEach(btn => btn.classList.remove('active'));
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

// ==================== Helper: ambil CSRF token ====================
function getCsrfToken() {
    return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
}

function updateCsrfToken(token) {
    document.querySelector('meta[name="csrf-token"]').setAttribute('content', token);
}

async function refreshCsrf() {
    await fetch('/sanctum/csrf-cookie', { credentials: 'include' });
    // Ambil token terbaru dari meta tag yang diupdate Laravel
    return getCsrfToken();
}

// ==================== Login ====================
async function loginUser(email, password){
    try {
        await refreshCsrf();
        const token = getCsrfToken();

        const response = await fetch("/login", {
            method: "POST",
            credentials: "include",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json",
                "X-CSRF-TOKEN": token
            },
            body: JSON.stringify({ email, password, role: 'admin' })
        });

        const text = await response.text();
        console.log("RESPONSE LOGIN:", text);

        // Update CSRF token dari response header jika ada
        const newToken = response.headers.get('X-CSRF-TOKEN');
        if (newToken) updateCsrfToken(newToken);

        try { return JSON.parse(text); }
        catch { return { success: false, message: text }; }

    } catch (error) {
        console.error(error);
        return { success: false, message: "Terjadi kesalahan server" };
    }
}

// ==================== Register ====================
async function registerUser(name, email, password, phone){
    try {
        await refreshCsrf();
        const token = getCsrfToken();

        const res = await fetch('/register', {
            method: 'POST',
            credentials: 'include',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': token
            },
            body: JSON.stringify({
                name, email, password,
                password_confirmation: password,
                phone_number: phone
            })
        });

        const text = await res.text();
        try { return JSON.parse(text); }
        catch { return { success: false, message: text }; }

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

    const res = await loginUser(email, password);

    if(res.success){
        localStorage.setItem("user_name", res.user.name);
        localStorage.setItem("user_email", res.user.email);
        localStorage.setItem("user_role", res.user.role);
        localStorage.setItem("auth_token", res.token);
        localStorage.setItem("welcome_message", "Selamat datang " + res.user.name);
        window.location.href = "/dashboard";
    } else {
        alert(res.message || "Login gagal! Periksa email dan password Anda.");
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
        }).then(() => card.classList.remove("active"));
        document.getElementById("registerForm").reset();
    } else {
        if (res.errors) {
            let msgs = [];
            Object.values(res.errors).forEach(e => msgs.push(e.join(', ')));
            alert("Validasi gagal:\n" + msgs.join('\n'));
        } else {
            alert(res.message || "Register gagal! Silakan coba lagi.");
        }
    }

    submitBtn.innerText = originalText;
    submitBtn.disabled = false;
});