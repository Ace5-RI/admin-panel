async function loadDashboard() {
    try {
        const res = await fetch('/api/dashboard?range=year');
        const data = await res.json();
console.log(data.warningClients);
console.log(data);

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
                labels: data.months.map(m => {
                    const [month, year] = m.split(" ");
                    return [month, year];
                }),
                datasets: [{
                    data: data.clientdata,
                    backgroundColor: "#22c55e",
                    borderRadius: 10
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false
                        }
                    },
                    y: { beginAtZero: true }
                }
            }
        });

        // ================= CHART 2 =================
        new Chart(document.getElementById("activityChart"), {
            type: "line",
            data: {
                labels: data.months.map(m => {
                    const [month, year] = m.split(" ");
                    return [month, year];
                }),
                datasets: [{
                    data: data.revenuedata,
                    borderColor: "#3b82f6",
                    tension: 0.5
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: {
                            maxRotation: 0,
                            minRotation: 0,
                            autoSkip: false
                        }
                    }
                }
            }
        });

  // ================= WARNING =================
// ================= WARNING CARDS =================
const container = document.getElementById("warningContainer");
container.innerHTML = "";

data.warningClients.forEach(client => {
    let color = "green";
    let statusText = "";

    if (client.days_left <= 5) {
        color = "red";
        statusText = "⚠️ Langganan telah berakhir";
    } else if (client.days_left <= 15) {
        color = "orange";
        statusText = `🟠 Langganan akan berakhir dalam ${client.days_left} hari`;
    } else if (client.days_left <= 20) {
        color = "yellow";
        statusText = `🟡 Langganan akan berakhir dalam ${client.days_left} hari`;
    } else {
        color = "green";
        statusText = `🟢 Masih aktif, berakhir dalam ${client.days_left} hari`;
    }

    // TOMBOL HAPUS CUMA UNTUK YANG days_left <= 0
    const showDeleteBtn = client.days_left <= 0;
    
    const card = `
        <div class="warning-card ${color}" data-id="${client.id}">
            <div class="badge">${client.days_left} hari</div>
            <div class="card-name">${client.name}</div>
            <div class="card-company">${client.company}</div>
            <div class="detail-row">
                <span>📧</span>
                <span>${client.email || '-'}</span>
            </div>
            <div class="detail-row">
                <span>📅</span>
                <span>${client.subscription_end_date}</span>
            </div>
            <div class="detail-row">
                <span>💰</span>
                <span>${client.price}</span>
            </div>
            <div class="status-message">${statusText}</div>
            ${showDeleteBtn ? '<button class="delete-warning-btn" onclick="deleteWarning(' + client.id + ')">✕ Hapus</button>' : ''}
        </div>
    `;
    
    container.innerHTML += card;
});

// Fungsi kecil untuk aman dari XSS
function escapeHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

        // ================= CHART & STAT (biarin di bawah / atas bebas) =================
        // (punya lo tadi tetap aman)

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

// ================== DELETE WARNING (CUSTOM POPUP) ==================
let warningClientIdToDelete = null;
let warningClientNameToDelete = null;

function deleteWarning(clientId, clientName) {
    console.log("Delete clicked:", clientId, clientName);
    warningClientIdToDelete = clientId;
    warningClientNameToDelete = clientName;
    
    const namaSpan = document.querySelector('#popupHapusWarning .nama-klien-warning');
    if (namaSpan) {
        namaSpan.innerHTML = `"${clientName}"`;
    }
    document.getElementById('popupHapusWarning').classList.add('open');
}

// PASTIKAN INI DIPAKE (event delegation buat tombol hapus di card)
document.body.addEventListener('click', function(e) {
    const btn = e.target.closest('.delete-warning-btn');
    if (btn) {
        const id = btn.getAttribute('data-id');
        const name = btn.getAttribute('data-name');
        if (id) {
            deleteWarning(id, name);
        }
    }
});

// Event listener untuk tombol konfirmasi hapus
const confirmBtn = document.getElementById('confirmDeleteWarningBtn');
if (confirmBtn) {
    confirmBtn.addEventListener('click', async function() {
        if (!warningClientIdToDelete) return;
        
        try {
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            
            const res = await fetch(`/klien/${warningClientIdToDelete}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            const data = await res.json();

            if (data.success) {
                // Tutup popup
                document.getElementById('popupHapusWarning').classList.remove('open');
                
                // Hapus card langsung dari DOM (biar langsung ilang)
                const deletedCard = document.querySelector(`.warning-card[data-id='${warningClientIdToDelete}']`);
                if (deletedCard) {
                    deletedCard.remove();
                }
                
                // Notifikasi
                alert(`Klien ${warningClientNameToDelete} berhasil dihapus!`);
                
                // Update stat & chart tanpa ngerender ulang warning
                loadDashboard();
            } else {
                alert(data.message || 'Gagal menghapus!');
            }
        } catch (err) {
            console.error(err);
            alert('Terjadi kesalahan!');
        }
        
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

// Tombol batal
const cancelBtn = document.getElementById('cancelDeleteWarningBtn');
if (cancelBtn) {
    cancelBtn.addEventListener('click', function() {
        document.getElementById('popupHapusWarning').classList.remove('open');
        warningClientIdToDelete = null;
        warningClientNameToDelete = null;
    });
}

// Klik di luar popup
const popupWarning = document.getElementById('popupHapusWarning');
if (popupWarning) {
    popupWarning.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.remove('open');
            warningClientIdToDelete = null;
            warningClientNameToDelete = null;
        }
    });
}