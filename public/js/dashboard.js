


async function loadDashboard() {
    try {
        const res = await fetch('/api/dashboard?range=year');
        const data = await res.json();

        // ================= STAT =================
       document.getElementById("totalClient").innerText = data.totalClients;
document.getElementById("activeClient").innerText = data.activeClients;
document.getElementById("inactiveClient").innerText = data.inactiveClients;

document.getElementById("totalRevenue").innerText =
    "Rp " + Number(data.totalrevenue).toLocaleString("id-ID");

        // ================= CHART 1 =================
        new Chart(document.getElementById("userChart"), {
            type: "bar",
            data: {
                labels: data.months,
                datasets: [{
                    data: data.clientdata,
                    backgroundColor: "#22c55e",
                    borderRadius: 10
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // ================= CHART 2 =================
        new Chart(document.getElementById("activityChart"), {
            type: "line",
            data: {
                labels: data.months,
                datasets: [{
                    data: data.revenuedata,
                    borderColor: "#3b82f6",
                    tension: 0.5
                }]
            }
        });

    } catch (err) {
        console.error("Dashboard error:", err);
    }
}

loadDashboard();

// ================== SIDEBAR AVATAR & WELCOME ==================
const avatarSidebar = document.getElementById("avatarSidebar");
const nameSidebar = document.getElementById("userNameSidebar");
const emailSidebar = document.getElementById("userEmailSidebar");

function updateSidebar(user){
    nameSidebar.textContent = user.name;
    emailSidebar.textContent = user.email;
    if(avatarSidebar){
    const nameParts = user.name.split(" ").slice(0,2); // ambil 2 kata pertama
    avatarSidebar.textContent = nameParts.map(w => w[0]).join("").toUpperCase();
}
}

// 1️⃣ Ambil data dari localStorage (dari login.js)
const userLS = localStorage.getItem("user_name");
const emailLS = localStorage.getItem("user_email");

if(userLS && emailLS){
    updateSidebar({name: userLS, email: emailLS});
    
    // tampilkan welcome popup sekali
    if(!localStorage.getItem("welcome_shown")){
        Swal.fire({
            title: "Selamat Datang 👋",
            text: "Halo " + userLS,
            icon: "success",
            confirmButtonText: "OK"
        });
        localStorage.setItem("welcome_shown","true");
    }
} else {
    // fallback: fetch API jika localStorage kosong
    async function loadLoginUser() {
        try{
            const token = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch('/account',{
                method:'GET',
                credentials:'include',
                headers:{'Accept':'application/json','X-CSRF-TOKEN':token}
            });
            const data = await res.json();
            if(data.success && data.user){
                updateSidebar(data.user);
                if(!localStorage.getItem("welcome_shown")){
                    Swal.fire({
                        title:"Selamat Datang 👋",
                        text:"Halo "+data.user.name,
                        icon:"success",
                        confirmButtonText:"OK"
                    });
                    localStorage.setItem("welcome_shown","true");
                }
            }
        }catch(e){ console.error("Error fetch login user:", e); }
    }
    loadLoginUser();
}

// ================== LOGOUT ==================
logoutBtn.addEventListener("click", async () => {
    try {
        const token = document.querySelector('meta[name="csrf-token"]').content;

        const res = await fetch('/logout', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json'
            }
        });

        if (!res.ok) throw new Error("Logout gagal");

        const data = await res.json();

        if (data.success) {
            localStorage.clear();
            window.location.href = "/";
        }

    } catch (err) {
        console.error(err);
        alert("Logout gagal!");
    }
});
