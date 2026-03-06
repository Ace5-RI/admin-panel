<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <title>Document</title>
</head>

<body>
    <div class="wrapper">

    <div class="login-card">

        <div class="left-panel">
            <img src="{{ asset('img/logo.png') }}" class="logo">
            <h2>Admin Portal</h2>

            <h1>Kelola Klien Dengan<br>Lebih Mudah</h1>
        </div>

        <div class="right-panel">

            <h2>Welcome To Login Page</h2>
            <p class="sub">Silahkan Login Untuk Melanjutkan</p>

            <label>Email</label>
            <input type="text" placeholder="Masukkan Email">

            <label>Password</label>
            <input type="password" placeholder="Masukkan Password">

            <p class="masuk">Masuk Sebagai</p>

            <div class="select-container">
                <button class="select-btn">Admin</button>
                <button class="select-btn">User</button>
            </div>

            <button class="submit-btn">Masuk</button>

            <p class="register">Belum Punya Akun <a href="#">Register</a></p>

        </div>

    </div>

</div>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>