<?php
// pages/links.php
if (!isset($_SESSION['user_id'])) exit;
$uid = $_SESSION['user_id'];

// --- 1. LOGIKA HAPUS LINK (WAJIB ADA DI ATAS) ---
if (isset($_GET['delete_id'])) {
    $id_hapus = mysqli_real_escape_string($koneksi, $_GET['delete_id']);
    
    // Pastikan link yang dihapus adalah milik user yang sedang login
    $query_hapus = "DELETE FROM url WHERE id = '$id_hapus' AND user_id = '$uid'";
    
    if (mysqli_query($koneksi, $query_hapus)) {
        // Redirect kembali ke halaman links agar data terupdate (tanpa parameter delete_id)
        echo "<script>window.location.href='index.php?page=links';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal menghapus link!');</script>";
    }
}

// --- 2. LOGIKA FILTER FOLDER ---
$filter_f = isset($_GET['f_id']) ? mysqli_real_escape_string($koneksi, $_GET['f_id']) : '';

$sql = "SELECT url.*, folders.nama_folder 
        FROM url 
        LEFT JOIN folders ON url.folder_id = folders.id 
        WHERE url.user_id = '$uid'";

if (!empty($filter_f)) {
    $sql .= " AND url.folder_id = '$filter_f'";
}

$sql .= " ORDER BY url.id DESC";
$result = mysqli_query($koneksi, $sql);
$q_list_f = mysqli_query($koneksi, "SELECT * FROM folders WHERE user_id = '$uid'");
?>

<div class="container-fluid">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h2 class="fw-bold text-dark mb-0">Manajemen Link</h2>
        
        <div class="d-flex gap-2">
            <form action="index.php" method="GET" class="d-flex gap-2">
                <input type="hidden" name="page" value="links">
                <select name="f_id" class="form-select form-select-sm shadow-sm" style="width: 200px;" onchange="this.form.submit()">
                    <option value="">-- Semua Folder --</option>
                    <?php while($f = mysqli_fetch_assoc($q_list_f)): ?>
                        <option value="<?= $f['id'] ?>" <?= ($filter_f == $f['id']) ? 'selected' : '' ?>>
                            <?= $f['nama_folder'] ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </form>
            <a href="index.php?page=home" class="btn btn-primary btn-sm fw-bold shadow-sm"><i class="bi bi-plus-lg"></i> Baru</a>
        </div>
    </div>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <?php 
                    $short_url = base_url($row['short_code']); 
                    $judul = $row['title'] ?: 'Tanpa Judul';
                ?>
                <div class="col-12 mb-3">
                    <div class="bit-card p-3 d-flex align-items-center gap-3 shadow-sm border-0" style="border-radius: 12px; background: #fff;">
                        
                        <div class="rounded p-2 bg-light border d-flex align-items-center justify-content-center" style="width: 55px; height: 55px;">
                            <img src="https://www.google.com/s2/favicons?domain=<?= $row['long_url'] ?>&sz=64" alt="icon" width="30">
                        </div>

                        <div class="flex-grow-1 overflow-hidden">
                            <h5 class="fw-bold mb-1 text-dark text-truncate"><?= $judul ?></h5>
                            <a href="<?= $short_url ?>" target="_blank" class="short-link-text d-block mb-1 fw-bold text-success text-decoration-none">
                                <i class="bi bi-link-45deg"></i> /<?= $row['short_code'] ?>
                            </a>
                            
                            <div class="d-flex align-items-center gap-2 mt-2">
                                <?php if ($row['nama_folder']): ?>
                                    <span class="badge bg-success-light text-success border border-success px-2 py-1" style="font-size: 0.7rem; background: #e6f2ed;">
                                        <i class="bi bi-folder2-open me-1"></i> <?= $row['nama_folder'] ?>
                                    </span>
                                <?php endif; ?>

                                <span class="badge bg-light text-muted border px-2 py-1" style="font-size: 0.7rem;">
                                    <i class="bi bi-bar-chart-fill text-primary"></i> <?= $row['click_count'] ?> Klik
                                </span>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('<?= $short_url ?>')" title="Salin"><i class="bi bi-clipboard"></i></button>
                            <a href="index.php?page=edit&id=<?= $row['id'] ?>" class="btn btn-outline-primary btn-sm" title="Edit"><i class="bi bi-pencil"></i></a>
                            
                            <button class="btn btn-outline-warning btn-sm" onclick="openQrModal('<?= $short_url ?>', '<?= addslashes($judul) ?>')" title="QR Code">
                                <i class="bi bi-qr-code-scan"></i>
                            </button>
                            
                            <a href="index.php?page=links&delete_id=<?= $row['id'] ?><?= !empty($filter_f) ? '&f_id='.$filter_f : '' ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Yakin ingin menghapus link ini?')" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="text-center py-5 bg-white shadow-sm rounded-4 border">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <p class="mt-3 text-muted">Belum ada link di kategori ini.</p>
        </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="modalQrLink" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center border-0" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="fw-bold" id="qrTitleDisplay">QR Code Link</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="bg-light p-3 rounded d-inline-block border mb-3">
                    <img id="qrImageDisplay" src="" alt="QR Code" class="img-fluid" style="width: 250px; height: 250px;">
                </div>
                <p class="small text-muted mb-0">Scan atau download untuk kebutuhan offline.</p>
            </div>
            <div class="modal-footer border-0 justify-content-center pb-4">
                <a id="btnDownloadQr" href="" class="btn btn-success fw-bold px-4">
                    <i class="bi bi-download me-2"></i>Download PNG
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert("Link berhasil disalin!");
        });
    }

    function openQrModal(url, title) {
        const qrApi = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" + encodeURIComponent(url);
        document.getElementById('qrImageDisplay').src = qrApi;
        document.getElementById('qrTitleDisplay').innerText = title;
        document.getElementById('btnDownloadQr').href = "download_qr.php?content=" + encodeURIComponent(url) + "&filename=" + encodeURIComponent(title);
        const myModal = new bootstrap.Modal(document.getElementById('modalQrLink'));
        myModal.show();
    }
</script>