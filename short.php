<?php
// Ambil kode dari URL
$kode = isset($_GET['code']) ? $_GET['code'] : "";
$link_asli = base_url($kode);

// Buat Link Tampilan (Membuang http/localhost agar cantik)
$link_tampilan = str_replace(["http://", "https://", "localhost/"], "", $link_asli);

// Link API QR Code (Otomatis generate gambar berdasarkan link asli)
$qr_link = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($link_asli);
?>

<div class="container text-center mt-5">
    <div class="card shadow p-4" style="max-width: 600px; margin: 0 auto; border-radius: 15px;">
        <h2 class="text-success fw-bold mb-3">Berhasil Disingkat!</h2>

        <p>Salin link pendek Anda di bawah ini:</p>

        <div class="input-group mb-3">
            <input type="text" class="form-control form-control-lg text-center fw-bold" value="<?= $link_tampilan ?>" readonly>

            <button class="btn btn-primary" onclick="copyLink()">Salin</button>

            <?php if (isset($_SESSION['user_id'])): ?>
                <button type="button" class="btn btn-warning fw-bold" data-bs-toggle="modal" data-bs-target="#modalQR">
                    QR Code
                </button>
            <?php else: ?>
                <button type="button" class="btn btn-warning fw-bold" onclick="alert('Fitur Terkunci! Silakan Register/Login terlebih dahulu untuk melihat QR Code.')">
                    QR Code
                </button>
            <?php endif; ?>
        </div>

        <input type="hidden" id="linkAsli" value="<?= $link_asli ?>">

        <div class="mt-4">
            <a href="index.php" class="btn btn-outline-success btn-sm">Singkat Link Lain</a>
            <?php if (!isset($_SESSION['user_id'])): ?>
                <p class="text-muted small mt-2">Ingin QR Code & Link Custom? <a href="register.php">Daftar Sekarang</a></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="modal fade" id="modalQR" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Download QR Code</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <img src="<?= $qr_link ?>" alt="QR Code" class="img-fluid border p-2 mb-3" style="max-width: 200px;">

                <p class="mb-2">Beri nama file sebelum download:</p>

                <div class="input-group px-4">
                    <input type="text" id="filenameInput" class="form-control" placeholder="Contoh: Link Rapat" required>
                    <span class="input-group-text">.png</span>
                </div>
            </div>

            <div class="modal-footer justify-content-center">
                <button type="button" onclick="prosesDownload()" class="btn btn-primary">
                    <i class="bi bi-download"></i> Download Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function copyLink() {
        var copyText = document.getElementById("linkAsli");
        navigator.clipboard.writeText(copyText.value);
        alert("Link berhasil disalin!");
    }

    function prosesDownload() {
        // 1. Ambil link asli (isi QR)
        var content = document.getElementById("linkAsli").value;

        // 2. Ambil nama file yang diketik user
        var filename = document.getElementById("filenameInput").value;

        // Jika kosong, kasih peringatan
        if (filename.trim() === "") {
            alert("Mohon isi nama file terlebih dahulu!");
            return;
        }

        // 3. Arahkan ke file download_qr.php dengan membawa data
        var downloadUrl = "download_qr.php?content=" + encodeURIComponent(content) + "&filename=" + encodeURIComponent(filename);

        // Eksekusi download
        window.location.href = downloadUrl;
    }
</script>