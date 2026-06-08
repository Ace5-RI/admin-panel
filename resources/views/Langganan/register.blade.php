<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Register</title>
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
            @if (session('error'))
            <div class="alert alert-danger alert-dismissible d-flex align-items-center fade show">
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
            <h2>Welcome To Login Page</h2>
            <p class="sub">Silahkan Login Untuk Melanjutkan</p>

            <label style="margin-top: 10%">Email</label>
            <input type="text" placeholder="Masukkan Email">

            <label style="margin-top: 5%">Password</label>
            <input type="password" placeholder="Masukkan Password">

            <p class="masuk">Masuk Sebagai</p>

            <div class="select-container">
                <button class="select-btn">Admin</button>
                <button class="select-btn">User</button>
            </div>

            <button class="submit-btn">Masuk</button>

            <p class="register">Belum Punya Akun <a href="" style="color: green">Register</a></p>

        </div>

    </div>

</div>
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>
