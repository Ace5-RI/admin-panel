// ================== DASHBOARD JS CLEAN ==================

// Dummy client data (nanti diganti API)
const clients = [
    { name: "Klien A", status: "aktif", company: "Company A", expire: "28/04/2026", price: 12000000 },
    { name: "Klien B", status: "nonaktif", company: "Company B", expire: "05/05/2026", price: 8000000 },
    { name: "Klien C", status: "aktif", company: "Company C", expire: "12/05/2026", price: 15000000 },
];

// ================== DASHBOARD STAT ==================
const totalClient = clients.length;
const activeClient = clients.filter(c => c.status === "aktif").length;
const inactiveClient = clients.filter(c => c.status !== "aktif").length;
const activePercent = totalClient ? (activeClient / totalClient) * 100 : 0;
const inactivePercent = totalClient ? (inactiveClient / totalClient) * 100 : 0;

// Update HTML stats
document.getElementById("totalClient").innerText = totalClient;
document.getElementById("totalTransaction").innerText = activeClient;
document.getElementById("activeClient").innerText = inactiveClient;
document.getElementById("inactiveClient").innerText = clients.reduce((sum, c)=>sum+c.price,0).toLocaleString("id-ID",{style:"currency",currency:"IDR"});

document.getElementById("activePercent").innerText = activePercent.toFixed(0)+"%";
document.getElementById("transactionPercent").innerText = activePercent.toFixed(0)+"%";
document.getElementById("inactivePercent").innerText = inactivePercent.toFixed(0)+"%";

// ================== CHARTS ==================
const userChart = new Chart(document.getElementById("userChart"), {
    type: "bar",
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Okt","Nov","Des"],
        datasets: [{ data:[5,10,8,15,12,18,15,10,10,30,28,34], backgroundColor:"#22c55e", borderRadius:10 }]
    },
    options:{ plugins:{ legend:{ display:false } }, scales:{ y:{ beginAtZero:true } } }
});

const activityChart = new Chart(document.getElementById("activityChart"), {
    type:"line",
    data:{
        labels:["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Okt","Nov","Des"],
        datasets:[{
            label:"User Activity",
            data:[5,10,8,15,12,18,15,10,10,30,28,34],
            borderColor:"#3b82f6",
            backgroundColor:"rgba(59,130,246,0.2)",
            tension:0.5,
            fill:false
        }]
    },
    options:{ plugins:{legend:{display:false}}, scales:{ y:{beginAtZero:true} } }
});

// ================== RENDER CARDS ==================
const cardsContainer = document.getElementById("cardsContainer");
cardsContainer.innerHTML = ""; // bersihin dulu

clients.forEach(user=>{
    const daysLeft = Math.max(0, Math.floor((new Date(user.expire) - new Date()) / (1000*60*60*24)));
    const initials = user.name.split(" ").map(w=>w[0]).join("").toUpperCase();
    const card = document.createElement("div");
    card.className = "card";
    card.innerHTML = `
        <div class="avatar">${initials}</div>
        <div class="card-header">
            <h2>${user.name}</h2>
            <p>${user.company}</p>
        </div>
        <div class="days-left"><span>${daysLeft} hari lagi</span></div>
        <div class="card-body">
            <div class="card-info">📧 ${user.name.toLowerCase().replace(" ",".")}@example.com</div>
            <div class="card-info">🏢 ${user.company}</div>
            <div class="card-info">📅 ${user.expire}</div>
            <div class="card-info price">📈 ${user.price.toLocaleString("id-ID",{style:"currency",currency:"IDR"})}</div>
        </div>
        <button class="invoice"><a href="https://wa.me/qr/3WTMUO54ZOXHB1" class="invoice-link">Invoice</a></button>
        <div class="warning">⚠️ Langganan akan berakhir dalam ${daysLeft} hari</div>
    `;
    cardsContainer.appendChild(card);
});

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
                window.location.href="/";
            }
        }catch(err){alert("Gagal logout!"); console.error(err);}
    });
}

document.querySelector('.add').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.remove('open');
    }
});