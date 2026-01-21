<?php
include "config/koneksi.php";
include "config/defaultFunc.php";

// Logika Register
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // 1. Enkripsi Password (Wajib agar aman dari hacker)
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // 2. Cek apakah email sudah ada?
    $cek = mysqli_query($koneksi, "SELECT * FROM users WHERE email = '$email'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah terdaftar!');</script>";
    } else {
        // 3. Simpan ke Database
        $query = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password_hash')";
        if (mysqli_query($koneksi, $query)) {
            echo "<script>
                alert('Pendaftaran Berhasil! Silakan Login.');
                window.location.href='login.php';
            </script>";
        } else {
            echo "<script>alert('Gagal Mendaftar!');</script>";
        }
    }
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Akun - BAAk Link</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>

<body style="background-color: #006341;">

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card shadow p-4" style="border-radius: 15px;">
                    <h3 class="text-center text-success fw-bold mb-4">Daftar Akun Baru</h3>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" name="register" class="btn btn-success w-100 fw-bold">DAFTAR SEKARANG</button>
                    </form>

                    <div class="text-center mt-3">
                        <small>Sudah punya akun? <a href="login.php">Login disini</a></small> <br>
                        <small><a href="index.php">Kembali ke Home</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>