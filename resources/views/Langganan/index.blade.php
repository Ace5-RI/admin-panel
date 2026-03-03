<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="{{ asset('css/Homepage.css') }}">
</head>
<body>

<div class="layout">

  <!-- Sidebar -->
  <aside class="sidebar">
    <h2>Admin Panel</h2>
    <p id="roleInfo"></p>
    <button onclick="logout()">Logout</button>
  </aside>

  <!-- Content -->
  <main class="content">

    <h1>Manajemen Klien</h1>

    <div id="notificationContainer"></div>

    <!-- Form (Admin Only) -->
    <div class="card" id="formSection">
      <input type="text" id="clientName" placeholder="Nama Klien">
      <input type="date" id="startDate">
      <button onclick="saveClient()">Simpan</button>
    </div>

    <!-- Table -->
    <div class="card">
      <table>
        <thead>
          <tr>
            <th>Nama</th>
            <th>Start</th>
            <th>Expiry</th>
            <th>Sisa Hari</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="clientTable"></tbody>
      </table>
    </div>

  </main>

</div>

<script src="{{ asset('js/Homepage.js') }}"></script>
</body>
</html>