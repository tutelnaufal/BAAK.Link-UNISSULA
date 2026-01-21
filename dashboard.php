<?php
// Pastikan User Login
if (!isset($_SESSION['user_id'])) {
    echo "<script>window.location.href='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// --- LOGIKA TAMBAH FOLDER ---
if (isset($_POST['buat_folder'])) {
    $nama_folder = mysqli_real_escape_string($koneksi, $_POST['nama_folder']);
    mysqli_query($koneksi, "INSERT INTO folders (user_id, nama_folder) VALUES ('$user_id', '$nama_folder')");
    echo "<script>alert('Folder Berhasil Dibuat!'); window.location.href='index.php?page=dashboard';</script>";
}

// --- LOGIKA HAPUS LINK ---
if (isset($_GET['delete_id'])) {
    $id_hapus = $_GET['delete_id'];
    mysqli_query($koneksi, "DELETE FROM url WHERE id = '$id_hapus' AND user_id = '$user_id'");
    echo "<script>alert('Link Dihapus!'); window.location.href='index.php?page=dashboard';</script>";
}

// --- AMBIL DATA LINK & FOLDER ---
// Ambil daftar link user + nama foldernya (Join Table)
$query_link = "SELECT url.*, folders.nama_folder 
               FROM url 
               LEFT JOIN folders ON url.folder_id = folders.id 
               WHERE url.user_id = '$user_id' 
               ORDER BY url.id DESC";
$data_link = mysqli_query($koneksi, $query_link);

// Ambil daftar folder untuk modal
$data_folder = mysqli_query($koneksi, "SELECT * FROM folders WHERE user_id = '$user_id'");
?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-white fw-bold">Dashboard Saya</h2>
        <div>
            <button class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalFolder">
                <i class="bi bi-folder-plus"></i> + Folder Baru
            </button>
            <a href="index.php" class="btn btn-light fw-bold ms-2">+ Singkat Link</a>
        </div>
    </div>

    <div class="card shadow border-0 rounded-4">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Short Link</th>
                            <th>Link Asli</th>
                            <th>Folder</th>
                            <th class="text-center">Klik</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($data_link)): ?>
                            <tr>
                                <td>
                                    <a href="<?= base_url($row['short_code']) ?>" target="_blank" class="fw-bold text-decoration-none">
                                        /<?= $row['short_code'] ?>
                                    </a>
                                    <br>
                                    <small class="text-muted"><?= date('d M Y', strtotime($row['created_at'])) ?></small>
                                </td>
                                <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                    <a href="<?= $row['long_url'] ?>" target="_blank" class="text-muted text-decoration-none small">
                                        <?= $row['long_url'] ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if ($row['nama_folder']): ?>
                                        <span class="badge bg-info text-dark"><?= $row['nama_folder'] ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Tanpa Folder</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center fw-bold fs-5 text-success">
                                    <?= $row['click_count'] ?>
                                </td>
                                <td>
                                    <a href="index.php?page=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                                    <a href="index.php?page=dashboard&delete_id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus link ini?')"><i class="bi bi-trash"></i></a>
                                    <button class="btn btn-sm btn-warning" onclick="showQr('<?= base_url($row['short_code']) ?>')"><i class="bi bi-qr-code"></i></button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
                <?php if (mysqli_num_rows($data_link) == 0): ?>
                    <p class="text-center text-muted py-3">Belum ada link yang dibuat.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFolder" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Buat Folder Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <input type="text" name="nama_folder" class="form-control" placeholder="Nama Folder (Misal: Materi Kuliah)" required>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="buat_folder" class="btn btn-success">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function showQr(url) {
        let qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(url);
        window.open(qrUrl, '_blank', 'width=400,height=400');
    }
</script>