<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login - Info Batang</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
*{box-sizing:border-box;font-family:Segoe UI, Tahoma, sans-serif}

body{
    margin:0;
    min-height:100vh;
    background:#e9e9e9;
    display:flex;
    justify-content:center;
    align-items:center;
}

/* Card utama */
.login-wrapper{
    width:900px;
    background:white;
    border-radius:18px;
    overflow:hidden;
    box-shadow:0 10px 40px rgba(0,0,0,.15);
}

/* Header biru */
.login-header{
    background:linear-gradient(135deg, #1e5ba8 0%, #2b7fd6 100%);
    color:white;
    padding:60px 40px;
    display:flex;
    justify-content:center;
    align-items:center;
    gap:30px;
}

.login-header h1{
    font-size:64px;
    font-weight:700;
    letter-spacing:4px;
    margin:0;
}

.logo-box img{
    width:90px;
    height:auto;
    display:block;
}

/* Panel bawah */
.login-body{
    background:#efefef;
    padding:50px 40px;
    border-top-left-radius:60px;
    border-top-right-radius:60px;
    margin-top:-40px;
}

.login-card{
    max-width:420px;
    margin:auto;
    text-align:center;
}

.login-card p{
    font-size:13px;
    color:#333;
    margin-bottom:30px;
    line-height:1.5;
}

.input{
    width:100%;
    padding:14px 16px;
    border:1px solid #ccc;
    border-radius:6px;
    margin-bottom:15px;
    font-size:14px;
}

.btn-login{
    width:100%;
    padding:14px;
    border:none;
    border-radius:6px;
    background:#184a91;
    color:white;
    font-weight:600;
    cursor:pointer;
    margin-top:10px;
}

.btn-google{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    background:white;
    border-radius:6px;
    margin-top:15px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
    cursor:pointer;
}

.register{
    margin-top:15px;
    font-size:13px;
}

.register a{
    color:#184a91;
    font-weight:600;
    text-decoration:none;
}

/* Responsive */
@media (max-width:768px){
    .login-wrapper{
        width:95%;
        margin:20px;
    }

    .login-header{
        padding:40px 30px;
        gap:20px;
    }

    .login-header h1{
        font-size:48px;
    }

    .logo-box img{
        width:70px;
    }
}
</style>
</head>

<body>

<div class="login-wrapper">

    <!-- HEADER -->
    <div class="login-header">
        <h1>LOGIN</h1>

        <div class="logo-box">
            <img src="{{ asset('images/image 5.png') }}" alt="Logo Info Batang">
        </div>
    </div>

    <!-- BODY -->
    <div class="login-body">
        <div class="login-card">

            <p>Selamat batang di Web Admin Info Batang. Silakan login terlebih dahulu untuk mengakses sistem.</p>

            <!-- masih UI, belum konek backend -->
            <form>

                <input class="input" type="text" placeholder="Username">
                <input class="input" type="password" placeholder="Password">

                <button class="btn-login">Login</button>

                <div class="btn-google">
                    <img src="https://www.svgrepo.com/show/475656/google-color.svg" width="18" alt="Google">
                    Login dengan Google
                </div>

                <div class="register">
                    Belum memiliki akun? <a href="#">Register</a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
