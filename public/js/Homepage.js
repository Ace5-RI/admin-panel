let clients = JSON.parse(localStorage.getItem("clients")) || [];
let editingIndex = null;

/* ================= LOGIN ================= */

function login() {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;

  if (username === "admin" && password === "123") {
    localStorage.setItem("role", "admin");
  } 
  else if (username === "user" && password === "123") {
    localStorage.setItem("role", "user");
  } 
  else {
    alert("Login salah!");
    return;
  }

  window.location.href = "dashboard.html";
}

function logout() {
  localStorage.removeItem("role");
  window.location.href = "index.html";
}

/* ================= CHECK AUTH ================= */

if (window.location.pathname.includes("dashboard.html")) {
  const role = localStorage.getItem("role");

  if (!role) {
    window.location.href = "index.html";
  } else {
    document.getElementById("roleInfo").innerText =
      "Login sebagai: " + role.toUpperCase();

    if (role === "user") {
      document.getElementById("formSection").style.display = "none";
    }

    renderClients();
  }
}

/* ================= DATE ================= */

function calculateExpiry(date) {
  let start = new Date(date);
  let expiry = new Date(start);
  expiry.setFullYear(expiry.getFullYear() + 1);
  return expiry;
}

function getRemainingDays(date) {
  let today = new Date();
  let expiry = calculateExpiry(date);
  return Math.ceil((expiry - today) / (1000 * 60 * 60 * 24));
}

/* ================= CRUD ================= */

function saveClient() {
  const role = localStorage.getItem("role");
  if (role !== "admin") return;

  const name = document.getElementById("clientName").value;
  const startDate = document.getElementById("startDate").value;

  if (!name || !startDate) return;

  const client = { name, startDate };

  if (editingIndex !== null) {
    clients[editingIndex] = client;
    editingIndex = null;
  } else {
    clients.push(client);
  }

  localStorage.setItem("clients", JSON.stringify(clients));
  renderClients();
}

function deleteClient(index) {
  clients.splice(index, 1);
  localStorage.setItem("clients", JSON.stringify(clients));
  renderClients();
}

function editClient(index) {
  const client = clients[index];
  document.getElementById("clientName").value = client.name;
  document.getElementById("startDate").value = client.startDate;
  editingIndex = index;
}

/* ================= RENDER ================= */

function renderClients() {
  const table = document.getElementById("clientTable");
  const notif = document.getElementById("notificationContainer");

  table.innerHTML = "";
  notif.innerHTML = "";

  clients.forEach((client, index) => {
    const expiry = calculateExpiry(client.startDate);
    const remaining = getRemainingDays(client.startDate);

    if (remaining <= 30 && remaining > 0) {
      notif.innerHTML += `
        <div class="notification">
          ⚠ Langganan <b>${client.name}</b> akan berakhir dalam ${remaining} hari!
        </div>
      `;
    }

    table.innerHTML += `
      <tr>
        <td>${client.name}</td>
        <td>${client.startDate}</td>
        <td>${expiry.toISOString().split("T")[0]}</td>
        <td>${remaining > 0 ? remaining : "Expired"}</td>
        <td>
          ${
            localStorage.getItem("role") === "admin"
              ? `
                <button onclick="editClient(${index})">Edit</button>
                <button onclick="deleteClient(${index})" style="background:red">Hapus</button>
              `
              : "-"
          }
        </td>
      </tr>
    `;
  });
}