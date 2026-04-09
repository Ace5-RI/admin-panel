<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
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

          <form id="loginForm" method="POST" action="{{ route('login') }}">
                    @csrf  {{-- ⭐ WAJIB: Token CSRF --}}
    <label>Email</label>
     <input type="email" name="email" id="loginEmail" placeholder="Masukkan Email" value="{{ old('email') }}" required>


    <label>Password</label>
   <input type="password" name="password" id="loginPassword" placeholder="Masukkan Password" required>



     <p class="masuk">Masuk Sebagai</p>

    <div class="role-select">
    <button type="button" class="select-btn" data-role="admin">Admin</button>
    <button type="button" class="select-btn" data-role="user">User</button>
    <button type="submit" class="submit-btn">Masuk</button>
</div>
                    
                    
                </form>



           

            

            <p class="register">Belum Punya Akun <a href="#" id="showRegister" style="color: green">Register</a>
</p>

    </div>
    <div class="register-wrapper">

                <form class="register-form" id="registerForm" method="POST" action="{{ route('register') }}">
                @csrf
        <h2>Register Account</h2>
        <p class="sub">Silahkan buat akun baru</p>

        <label>Nama Lengkap</label>
                <input type="text" name="name" id="nama" placeholder="Masukkan Username" required>


         <label>Email</label>
                <input type="email" name="email" id="regEmail" placeholder="Masukkan Email" required>


          <label>Nomor Telepon</label>
                <input type="tel" name="phone" id="regPhone" placeholder="081234567890">
                
        <label>Password</label>
                <input type="password" name="password" id="regPassword" placeholder="Masukkan Password" required>

         <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="regPasswordConfirmation" placeholder="Konfirmasi Password" required>

                <button type="submit" class="regis-btn">Register</button>


       
            
           <p class="register">Sudah punya akun <a href="#" id="showLogin" style="color: green">login</a>
        
           </form>

    <div class="register-info">
        <h2>Welcome!</h2>
        <p>Silahkan buat akun untuk menggunakan sistem</p>
    </div>

</div>
    
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>