// Dummy data (tanpa database)
let clients = [
    { name: "A", status: "aktif" },
    { name: "B", status: "nonaktif" },
    { name: "C", status: "aktif" },
    { name: "D", status: "aktif" }
];

// Hitung total
let totalClient = clients.length;

// Hitung aktif & nonaktif
let activeClient = clients.filter(c => c.status === "aktif").length;
let inactiveClient = clients.filter(c => c.status === "nonaktif").length;

// Hitung persentase
let activePercent = totalClient > 0 ? (activeClient / totalClient) * 100 : 0;
let inactivePercent = totalClient > 0 ? (inactiveClient / totalClient) * 100 : 0;

// Masukin ke HTML
document.getElementById("totalClient").innerText = totalClient;
document.getElementById("activeClient").innerText = activeClient;
document.getElementById("inactiveClient").innerText = inactiveClient;

document.getElementById("activePercent").innerText = activePercent.toFixed(0) + "%";
document.getElementById("inactivePercent").innerText = inactivePercent.toFixed(0) + "%";

// Dummy transaksi
let totalTransaction = 12;
let transactionPercent = 25;

document.getElementById("totalTransaction").innerText = totalTransaction;
document.getElementById("transactionPercent").innerText = transactionPercent + "%";

const ctx = document.getElementById("userChart");

const chart = new Chart(ctx, {
    type: "bar",
    data: {
        labels: ["User", "Admin"],
        datasets: [{
            data: [10, 15], // demo data
            backgroundColor: "#22c55e",
            borderRadius: 10,
            barThickness:250,
            responsive:true
        }]
    },
    options: {
        plugins:{
            legend:{
                display:false
            }
        },
        layout:{
 padding:10
},
        scales:{
            y:{
                beginAtZero:true
            }
        }
    }
});

setInterval(() => {

    chart.data.datasets[0].data = [
        Math.floor(Math.random()*20),
        Math.floor(Math.random()*20)
    ];

    chart.update();

},10000);

const ctx2 = document.getElementById("activityChart");

const activityChart = new Chart(ctx2, {
    type: "line",
    data: {
        labels: ["Jan","Feb","Mar","Apr","May","Jun","Jul","August","Sep","Okt","Nov","Des"],
        datasets: [{
            label: "User Activity",
            data: [5,10,8,15,12,18,15,10,10,30,28,34,1,2,1,2],
            borderColor: "#3b82f6",
            backgroundColor: "rgba(59,130,246,0.2)",
            tension: 0.5,
            fill: false
        }]
    },
    options: {
        plugins:{
            legend:{
                display:false
            }
        },
        layout:{
            padding:10
        },
        scales:{
            x:{
                grid:{
                    display:false
                }
            },
            y:{
                beginAtZero:true,
                grid:{
                    color:"#eee"
                }
            }
        }
    }
});

const username = "Wayan Mahendra";

const avatar = document.getElementById("avatarUser");

const initials = username
  .split(" ")              // pisahkan jadi ["Wayan", "Mahendra"]
  .map(word => word.charAt(0)) // ambil huruf pertama tiap kata
  .join("")                // gabung jadi "WM"
  .toUpperCase();

avatar.textContent = initials;

const buttons = document.querySelectorAll(".menu-btn, .menu-lgt");

buttons.forEach(btn => {
    btn.addEventListener("click", () => {

        buttons.forEach(b => b.classList.remove("active"));

        btn.classList.add("active");

    });
});



const user = {
name: "Siti Nurhaliza",
company: "Innovate Solutions",
email: "siti.n@innovate.id",
expire: "28/3/2026",
price: "Rp 32.000.000",
daysLeft: 15
}

document.getElementById("userName").textContent = user.name
document.getElementById("userCompany").textContent = user.company
document.getElementById("userCompany2").textContent = user.company
document.getElementById("userEmail").textContent = user.email
document.getElementById("expiredDate").textContent = user.expire
document.getElementById("price").textContent = user.price

document.getElementById("daysLeft").textContent = user.daysLeft + " hari lagi"

document.getElementById("warningText").textContent =
"Langganan akan berakhir dalam " + user.daysLeft + " hari"

// avatar huruf pertama
document.getElementById("avatarUser").textContent =
user.name.charAt(0) + user.name.split(" ")[1].charAt(0)

