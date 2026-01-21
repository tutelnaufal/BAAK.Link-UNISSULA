<?php
// 1. KEAMANAN: Cek apakah user login & ada ID link yang mau diedit
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

$id_url = $_GET['id'];
$uid    = $_SESSION['user_id'];

// 2. AMBIL DATA LAMA: Pastikan link itu milik user yang sedang login
$cek = mysqli_query($koneksi, "SELECT * FROM url WHERE id = '$id_url' AND user_id = '$uid'");
if (mysqli_num_rows($cek) == 0) {
    echo "<script>alert('Data tidak ditemukan atau bukan milik Anda!'); window.location='index.php?page=dashboard';</script>";
    exit;
}
$data = mysqli_fetch_assoc($cek);

// 3. PROSES UPDATE DATA (Saat tombol Simpan ditekan)
if (isset($_POST['update'])) {
    $long_url   = mysqli_real_escape_string($koneksi, $_POST['url']);
    $password   = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Logika Folder (Jika NULL, set NULL sql, jika ada ID, set angka ID)
    $folder_val = $_POST['folder_id'];
    $folder_sql = ($folder_val == 'NULL') ? "NULL" : "'$folder_val'";

    // Jalankan Query Update
    $query_update = "UPDATE url SET 
                        long_url = '$long_url', 
                        link_password = '$password', 
                        folder_id = $folder_sql 
                     WHERE id = '$id_url'";

    if (mysqli_query($koneksi, $query_update)) {
        echo "<script>alert('Link berhasil diperbarui!'); window.location='index.php?page=dashboard';</script>";
    } else {
        echo "<script>alert('Gagal update!');</script>";
    }
}
?>

<div class="container mt-4">
    <div class="card shadow p-4 mx-auto" style="max-width: 600px; background: rgba(255,255,255,0.98); border-radius: 15px;">
        <h3 class="fw-bold mb-4 text-success text-center">Edit Link</h3>

        <form method="POST">
            <div class="mb-3">
                <label class="fw-bold small text-muted">Kode Pendek (Short Code)</label>
                <input type="text" class="form-control bg-light text-success fw-bold" value="/<?= $data['short_code'] ?>" readonly>
                <small class="text-muted" style="font-size: 0.7rem;">*Kode pendek tidak dapat diubah setelah dibuat.</small>
            </div>

            <div class="mb-3">
                <label class="fw-bold small text-muted">Link Panjang (Tujuan)</label>
                <input type="url" name="url" class="form-control" value="<?= $data['long_url'] ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-muted">Password Link</label>
                    <input type="text" name="password" class="form-control" value="<?= $data['link_password'] ?>" placeholder="Kosongkan jika publik">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="fw-bold small text-muted">Pindah Folder</label>
                    <select name="folder_id" class="form-select">
                        <option value="NULL" <?= ($data['folder_id'] == NULL) ? 'selected' : '' ?>>-- Tanpa Folder --</option>
                        <?php
                        // Ambil ulang data folder untuk dropdown
                        $q_folder = mysqli_query($koneksi, "SELECT * FROM folders WHERE user_id='$uid'");
                        while ($f = mysqli_fetch_assoc($q_folder)) {
                            // Cek apakah folder ini yang sedang dipilih
                            $selected = ($f['id'] == $data['folder_id']) ? 'selected' : '';
                            echo "<option value='" . $f['id'] . "' $selected>" . $f['nama_folder'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <button type="submit" name="update" class="btn btn-success fw-bold flex-grow-1">Simpan Perubahan</button>
                <a href="index.php?page=dashboard" class="btn btn-outline-secondary fw-bold">Batal</a>
            </div>
        </form>
    </div>
</div>