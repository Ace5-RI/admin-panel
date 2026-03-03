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
    <div class="login-container">
    
    <img src="{{ asset('img/login.png') }}" alt="Login Image">
    <div class="overlay">
        <img src="{{ asset('img/logo.png') }}" class="main-img">
    <div class="text">
        <h2>Welcome To Login Page</h2><br>
        <p style="margin-left: 4px" class="posisi">Silahkan Login Untuk Melanjutkan</p>
        <h2 class="kiri">Admin Portal</h2>

        <p class="klien">Kelola Klien Dengan Lebih Mudah</p>
        <p class="email">Email
        <input type="text" name="email" class="input-email" placeholder="Masukkan Email">
        </p>

     
        <p class="password">Password
        <input type="password" name="password" class="input-password" placeholder="Masukkan Password">
        </p>

        <p class="txt">Masuk Sebagai</p>

        <p class="register">Belum Punya Akun <a href="register.html" class="tombol">Register</a></p>
        <div class="select-container">
            
    <button class="select-btn">Admin</button>
    <button class="select-btn">User</button>
</div>
<button type="submit" class="submit-btn">Masuk</button>
    </div>


    
       
    </div>
    </div>
    <script src="{{ asset('js/login.js') }}"></script>
</body>
</html>