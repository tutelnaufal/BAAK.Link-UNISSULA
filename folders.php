<?php
// Pastikan admin sudah login
if (!isset($_SESSION['user_id'])) exit;
$uid = $_SESSION['user_id'];

// --- LOGIKA 1: TAMBAH FOLDER BARU ---
if (isset($_POST['add_folder'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['folder_name']);
    $query = "INSERT INTO folders (user_id, nama_folder) VALUES ('$uid', '$nama')";
    mysqli_query($koneksi, $query);
    echo "<script>window.location.href='index.php?page=folders';</script>";
}

// --- LOGIKA 2: HAPUS FOLDER ---
if (isset($_GET['delete_f'])) {
    $fid = $_GET['delete_f'];
    // Link di dalam folder ini tidak dihapus, hanya diubah statusnya menjadi NULL (Tanpa Folder)
    mysqli_query($koneksi, "UPDATE url SET folder_id = NULL WHERE folder_id = '$fid' AND user_id = '$uid'");
    mysqli_query($koneksi, "DELETE FROM folders WHERE id = '$fid' AND user_id = '$uid'");
    echo "<script>window.location.href='index.php?page=folders';</script>";
}

// Ambil semua daftar folder milik admin
$q_f = mysqli_query($koneksi, "SELECT f.*, (SELECT COUNT(*) FROM url WHERE folder_id = f.id) as total_link 
                               FROM folders f 
                               WHERE f.user_id = '$uid' 
                               ORDER BY f.nama_folder ASC");
?>

<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Folder</h2>
        <button class="btn btn-success fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#modalAddFolder">
            <i class="bi bi-folder-plus"></i> Buat Folder Baru
        </button>
    </div>

    <div class="row">
        <?php if (mysqli_num_rows($q_f) > 0): ?>
            <?php while ($f = mysqli_fetch_assoc($q_f)): ?>
                <div class="col-12 col-md-4 col-lg-3 mb-4">
                    <div class="bit-card p-4 shadow-sm border-0 text-center" style="border-radius: 15px; background: #fff;">
                        <div class="mb-3 text-success">
                            <i class="bi bi-folder-fill" style="font-size: 3rem; opacity: 0.8;"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1"><?= $f['nama_folder'] ?></h5>
                        <p class="text-muted small mb-3"><?= $f['total_link'] ?> Link tersimpan</p>
                        
                        <div class="d-flex justify-content-center gap-2 mt-2">
                            <a href="index.php?page=links&f_id=<?= $f['id'] ?>" class="btn btn-sm btn-outline-success rounded-pill px-3">Lihat Isi</a>
                            <a href="index.php?page=folders&delete_f=<?= $f['id'] ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Hapus folder ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5 bg-white rounded-4 border">
                <i class="bi bi-folder2-open display-1 text-muted"></i>
                <p class="text-muted mt-3">Belum ada folder. Silakan buat folder untuk mulai merapikan link akademik.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="modalAddFolder" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold">Buat Folder Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="small fw-bold text-muted mb-2 text-uppercase">Nama Folder</label>
                <input type="text" name="folder_name" class="form-control" placeholder="Contoh: Dokumen Yudisium" required>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" name="add_folder" class="btn btn-success fw-bold w-100 py-2">Simpan Folder</button>
            </div>
        </form>
    </div>
</div>