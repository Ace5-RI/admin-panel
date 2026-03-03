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