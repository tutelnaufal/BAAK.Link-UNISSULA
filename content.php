<div class="card shadow p-4 mb-4 text-center" style="background: rgba(255, 255, 255, 0.98); border-radius: 15px;">
    <h2 class="fw-bold text-success mb-3">Buat Link Pendek</h2>
    <p class="text-muted mb-4">
        Layanan resmi BAAk Link. Masukkan tautan panjang Anda di bawah ini.
    </p>

    <form action="" method="POST">
        <div class="mb-3 text-start">
            <label class="fw-bold small text-muted">Link Panjang (Asli)</label>
            <input type="url" name="url" class="form-control form-control-lg" placeholder="Tempel link panjang di sini (https://...)" required>
        </div>

        <?php if (isset($_SESSION['user_id'])): ?>

            <div class="row g-2 mb-3">
                <div class="col-md-4 text-start">
                    <label class="fw-bold small text-muted">Custom Link (Opsional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted small">/</span>
                        <input type="text" name="custom_code" class="form-control" placeholder="kode-unik">
                    </div>
                    <small class="text-muted" style="font-size: 0.7rem;">*Hanya huruf & angka</small>
                </div>

                <div class="col-md-4 text-start">
                    <label class="fw-bold small text-muted">Password (Opsional)</label>
                    <input type="text" name="password_link" class="form-control" placeholder="rahasia">
                </div>

                <div class="col-md-4 text-start">
                    <label class="fw-bold small text-muted">Simpan di Folder</label>
                    <select name="folder_id" class="form-select">
                        <option value="NULL">-- Tanpa Folder --</option>
                        <?php
                        // Mengambil daftar folder milik user dari database
                        $uid = $_SESSION['user_id'];
                        $q_folder = mysqli_query($koneksi, "SELECT * FROM folders WHERE user_id = '$uid' ORDER BY id DESC");
                        while ($f = mysqli_fetch_assoc($q_folder)) {
                            echo "<option value='" . $f['id'] . "'>" . $f['nama_folder'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

        <?php else: ?>
            <div class="alert alert-info py-2 small border-0 bg-light text-muted">
                <i class="bi bi-lock-fill"></i>
                Ingin Custom Link, Password & Folder? <a href="login.php" class="fw-bold text-success text-decoration-none">Login sekarang</a>.
            </div>
        <?php endif; ?>

        <div class="d-grid mt-4">
            <button type="submit" name="submit" class="btn btn-warning btn-lg fw-bold text-success shadow-sm">
                <i class="bi bi-magic"></i> SINGKAT SEKARANG!
            </button>
        </div>
    </form>

    <?php if (isset($_SESSION['user_id'])): ?>
        <div class="mt-3">
            <a href="index.php?page=dashboard" class="text-decoration-none text-muted small">
                <i class="bi bi-speedometer2"></i> Kembali ke Dashboard
            </a>
        </div>
    <?php endif; ?>
</div>