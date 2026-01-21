<?php
// pages/settings.php
if (!isset($_SESSION['user_id'])) exit;
$uid = $_SESSION['user_id'];

// Ambil data admin saat ini
$q_user = mysqli_query($koneksi, "SELECT * FROM users WHERE id = '$uid'");
$u = mysqli_fetch_assoc($q_user);

// --- LOGIKA 1: UPDATE PROFIL ---
if (isset($_POST['update_profile'])) {
    $new_user = mysqli_real_escape_string($koneksi, $_POST['username']);
    $new_email = mysqli_real_escape_string($koneksi, $_POST['email']);

    $sql = "UPDATE users SET username = '$new_user', email = '$new_email' WHERE id = '$uid'";
    if (mysqli_query($koneksi, $sql)) {
        $_SESSION['username'] = $new_user; // Update session agar nama di header berubah
        echo "<script>alert('Profil berhasil diperbarui!'); window.location.href='index.php?page=settings';</script>";
    }
}

// --- LOGIKA 2: GANTI PASSWORD (TEKS BIASA) ---
if (isset($_POST['update_password'])) {
    $old_pass = $_POST['old_pass'];
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];

    // 1. Cek apakah password lama benar
    if ($old_pass != $u['password']) {
        echo "<script>alert('Password lama salah!');</script>";
    } 
    // 2. Cek apakah password baru & konfirmasi cocok
    else if ($new_pass != $confirm_pass) {
        echo "<script>alert('Konfirmasi password baru tidak cocok!');</script>";
    } 
    else {
        // 3. Update ke Database
        mysqli_query($koneksi, "UPDATE users SET password = '$new_pass' WHERE id = '$uid'");
        echo "<script>alert('Password berhasil diganti!'); window.location.href='index.php?page=settings';</script>";
    }
}
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">Account Settings</h2>

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="bit-card p-4 shadow-sm border-0 h-100" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-success"><i class="bi bi-person-circle me-2"></i>Edit Profil Admin</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 text-uppercase">Username Admin</label>
                        <input type="text" name="username" class="form-control" value="<?= $u['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 text-uppercase">Email</label>
                        <input type="email" name="email" class="form-control" value="<?= $u['email'] ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-success fw-bold px-4 mt-2">Simpan Profil</button>
                </form>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="bit-card p-4 shadow-sm border-0 h-100" style="border-radius: 15px;">
                <h5 class="fw-bold mb-4 text-warning"><i class="bi bi-shield-lock-fill me-2"></i>Ganti Kata Sandi</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 text-uppercase">Password Saat Ini</label>
                        <input type="password" name="old_pass" class="form-control" placeholder="******" required>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 text-uppercase">Password Baru</label>
                        <input type="password" name="new_pass" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1 text-uppercase">Konfirmasi Password Baru</label>
                        <input type="password" name="confirm_pass" class="form-control" placeholder="Ulangi password baru" required>
                    </div>
                    <button type="submit" name="update_password" class="btn btn-warning fw-bold px-4 text-success mt-2">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>