<?php
include "config/koneksi.php";
include "config/defaultFunc.php";

// Jika sudah login, langsung ke index
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

// Logika Login
if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cari user berdasarkan username
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username'");

    if (mysqli_num_rows($cek) === 1) {
        $data = mysqli_fetch_assoc($cek);

        // PERBANDINGAN TEKS BIASA (Tanpa Hash)
        if ($password == $data['password']) { 
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['username'] = $data['username'];

            header("Location: index.php");
            exit;
        } else {
            echo "<script>alert('Password Salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }
}
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - BAAk Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body style="background-color: #006341; display: flex; align-items: center; min-height: 100vh;">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4 border-0" style="border-radius: 15px;">
                    <h4 class="text-center text-success fw-bold mb-4">
                        Sistem Pemendek Link <br> 
                        <span class="small fw-normal text-muted">Biro Administrasi Akademik</span>
                    </h4>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Username</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="username" class="form-control border-start-0 ps-0" placeholder="Username" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold small mb-1">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="passInput" class="form-control" autocomplete="off" required>
                                <button class="btn btn-outline-secondary border-start-0" type="button" id="btnShow">
                                    <i class="bi bi-eye" id="eyeIcon"></i>
                                </button>
                            </div>
                        </div>
                        <button type="submit" name="login" class="btn btn-warning w-100 fw-bold text-success shadow-sm mt-2">MASUK</button>
                    </form>

                    <div class="text-center mt-4 border-top pt-3">
                        <small class="text-muted">Akses Khusus Admin BAAK</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const passInput = document.getElementById('passInput');
        const btnShow = document.getElementById('btnShow');
        const eyeIcon = document.getElementById('eyeIcon');

        btnShow.addEventListener('click', function() {
            if (passInput.type === 'password') {
                passInput.type = 'text';
                eyeIcon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                passInput.type = 'password';
                eyeIcon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>
</body>
</html>