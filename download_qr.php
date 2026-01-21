<?php
// Matikan laporan error agar tidak merusak file gambar jika ada warning kecil
error_reporting(0);

if (isset($_GET['content'])) {
    $content = $_GET['content'];

    // 1. Ambil Nama File
    $nama_file = isset($_GET['filename']) && !empty($_GET['filename']) ? $_GET['filename'] : 'qrcode_baak';
    $nama_file = preg_replace('/[^A-Za-z0-9 _-]/', '', $nama_file);

    // 2. Siapkan URL API
    $api_url = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($content);

    // 3. (BARU) LOGIKA BYPASS SSL
    // Ini adalah "Obat" untuk error: Failed to enable crypto / failed loading cafile
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    // 4. Ambil Gambar dengan Opsi Bypass SSL tadi
    $gambar = file_get_contents($api_url, false, stream_context_create($arrContextOptions));

    if ($gambar) {
        // 5. Bersihkan Buffer (Pencegah file korup/rusak)
        if (ob_get_length()) ob_clean();

        // 6. Header Download
        header('Content-Type: image/png');
        header('Content-Disposition: attachment; filename="' . $nama_file . '.png"');

        echo $gambar;
        exit;
    } else {
        // Jika masih gagal, biasanya karena koneksi internet mati
        echo "Gagal mengambil gambar. Pastikan internet Anda lancar.";
    }
}
