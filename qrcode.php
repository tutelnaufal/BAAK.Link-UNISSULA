<script src="https://cdn.jsdelivr.net/gh/ushelp/EasyQRCodeJS/dist/easy.qrcode.min.js"></script>

<div class="container-fluid">
    <div class="row">

        <div class="col-lg-7">
            <h2 class="fw-bold mb-4">Create QR Code</h2>

            <div class="bit-card p-4">
                <form action="" method="POST" id="qrForm">

                    <div class="mb-4">
                        <label class="fw-bold small text-muted mb-2">Destination URL</label>
                        <input type="url" name="long_url" id="inputUrl" class="form-control form-control-lg p-3"
                            placeholder="https://example.com/..." required oninput="generateQR()">
                        <div class="form-text text-end">Preview akan muncul otomatis saat mengetik</div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold small text-muted mb-2">Title (optional)</label>
                        <input type="text" name="title" class="form-control p-3" placeholder="Ex: Absensi Rapat">
                    </div>

                    <div class="card bg-light border-0 p-3 mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-link-45deg fs-4 text-success"></i>
                                <div>
                                    <h6 class="fw-bold mb-0">Short Link</h6>
                                    <small class="text-muted">Buat link pendek juga?</small>
                                </div>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="make_short" value="1" checked style="width: 3em; height: 1.5em; cursor: pointer;">
                            </div>
                        </div>

                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <span class="fw-bold text-muted">baak.link /</span>
                            </div>
                            <div class="col">
                                <input type="text" name="custom_code" class="form-control" placeholder="kode-custom">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-2">
                        <a href="index.php?page=home" class="btn btn-outline-secondary fw-bold px-4">Cancel</a>
                        <button type="submit" name="submit_qr" class="btn btn-primary fw-bold px-4 flex-grow-1">Create QR Code</button>
                    </div>

                </form>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="d-flex justify-content-center">
                <div class="text-center sticky-top" style="top: 100px;">
                    <h5 class="fw-bold text-muted mb-4">Preview</h5>

                    <div class="bg-white p-4 shadow-sm border rounded mb-3 d-inline-block">
                        <div id="qrcode_container"></div>
                    </div>

                    <p class="small text-muted mb-3" style="max-width: 300px; margin: 0 auto;">
                        <i class="bi bi-qr-code"></i> Standard QR Code (Clean)
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
    let qrcodeObj = null;

    function generateQR() {
        const url = document.getElementById('inputUrl').value;
        const container = document.getElementById('qrcode_container');

        container.innerHTML = "";

        if (url.trim() === "") {
            container.innerHTML = "<div class='text-muted py-5 d-flex align-items-center justify-content-center' style='width:250px; height:250px; background:#f8f9fa; border:2px dashed #ddd; border-radius:10px;'>Ketik URL di kiri...</div>";
            return;
        }

        const options = {
            text: url,
            width: 250,
            height: 250,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H

            // SAYA TELAH MENGHAPUS BAGIAN 'onRenderingEnd' dan 'logo'
            // SEHINGGA QR CODE AKAN MUNCUL BERSIH TANPA WATERMARK BAAK
        };

        try {
            qrcodeObj = new QRCode(container, options);
        } catch (e) {
            console.error("Gagal render QR:", e);
        }
    }

    window.addEventListener('load', function() {
        generateQR();
    });
</script>

<?php
// --- LOGIKA PHP: SIMPAN KE DATABASE ---
if (isset($_POST['submit_qr'])) {
    $long_url   = mysqli_real_escape_string($koneksi, $_POST['long_url']);
    $title      = mysqli_real_escape_string($koneksi, $_POST['title']);
    $user_id    = $_SESSION['user_id'];

    $short_code = "";
    if (isset($_POST['custom_code']) && !empty($_POST['custom_code'])) {
        $short_code = mysqli_real_escape_string($koneksi, $_POST['custom_code']);
    } else {
        $short_code = generate_code(5);
    }

    $query = "INSERT INTO url (user_id, long_url, short_code, title) VALUES ('$user_id', '$long_url', '$short_code', '$title')";

    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('QR Code & Link Berhasil Dibuat!'); window.location.href='index.php?page=links';</script>";
    } else {
        echo "<script>alert('Gagal menyimpan!');</script>";
    }
}
?>