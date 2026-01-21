<div class="container-fluid">
    <h2 class="fw-bold mb-4">Buat Link Baru</h2>

    <div class="bit-card p-4 shadow-sm border-0" style="border-radius: 15px; background: #fff;">
        <h4 class="fw-bold mb-4 text-success"><i class="bi bi-lightning-charge-fill"></i> Quick Create</h4>

        <form action="index.php" method="POST">
            <div class="mb-4">
                <label class="fw-bold text-muted small mb-2 text-uppercase">Link Panjang (Tujuan)</label>
                <input type="url" name="url" class="form-control form-control-lg p-3" placeholder="https://example.com/sangat-panjang" style="font-size: 1rem; border-radius: 10px;" required>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="fw-bold text-muted small mb-2 text-uppercase">Judul (Opsional)</label>
                    <input type="text" name="title" class="form-control p-2" placeholder="Contoh: Link Pendaftaran Wisuda" style="border-radius: 8px;">
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small mb-2 text-uppercase">Custom Link (Opsional)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light text-muted">baak.link/</span>
                        <input type="text" name="custom_code" class="form-control" placeholder="kode-unik" style="border-radius: 0 8px 8px 0;">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="fw-bold text-muted small mb-2 text-uppercase">Simpan di Folder</label>
                    <select name="folder_id" class="form-select p-2" style="border-radius: 8px;">
                        <option value="NULL">-- Pilih Folder (Tanpa Folder) --</option>
                        <?php
                        $uid = $_SESSION['user_id'];
                        $q_folder = mysqli_query($koneksi, "SELECT * FROM folders WHERE user_id = '$uid' ORDER BY nama_folder ASC");
                        while ($f = mysqli_fetch_assoc($q_folder)) {
                            echo "<option value='" . $f['id'] . "'>" . $f['nama_folder'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="mb-4 col-md-4">
                <label class="fw-bold text-muted small mb-2 text-uppercase">Password Link (Opsional)</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                    <input type="text" name="password_link" class="form-control" placeholder="Kosongkan jika publik" style="border-radius: 0 8px 8px 0;">
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-success btn-lg fw-bold w-100 shadow-sm" style="border-radius: 12px; padding: 15px;">
                <i class="bi bi-magic"></i> SINGKATKAN SEKARANG!
            </button>
        </form>
    </div>

    <div class="alert text-white d-flex align-items-center p-4 shadow-sm mt-4" style="background-color: #006341; border-radius: 15px; border: none;">
        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center me-3" style="min-width: 50px; height: 50px;">
            <i class="bi bi-info-circle-fill fs-3" style="color: #006341;"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1">Tips Manajemen</h5>
            <p class="mb-0 small" style="opacity: 0.9;">Gunakan fitur <b>Folder</b> untuk mengelompokkan link akademik seperti Yudisium, KRS, atau Wisuda agar lebih mudah dicari nantinya.</p>
        </div>
    </div>
</div>