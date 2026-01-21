<?php
// pages/analytics.php

if (!isset($_SESSION['user_id'])) exit;
$uid = $_SESSION['user_id'];

// --- 1. DATA STATISTIK UTAMA ---
$q_link = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM url WHERE user_id='$uid'");
$total_link = mysqli_fetch_assoc($q_link)['total'];

$q_click = mysqli_query($koneksi, "SELECT SUM(click_count) as total FROM url WHERE user_id='$uid'");
$d_click = mysqli_fetch_assoc($q_click);
$total_click = $d_click['total'] ? $d_click['total'] : 0;

$avg_clicks = ($total_link > 0) ? round($total_click / $total_link, 1) : 0;

// --- 2. DATA GRAFIK BULANAN (REAL) ---
$chart_values = [];
$current_year = date('Y');
for ($m = 1; $m <= 12; $m++) {
    $sql_m = "SELECT COUNT(cl.id) as total 
              FROM click_logs cl 
              JOIN url u ON cl.url_id = u.id 
              WHERE u.user_id = '$uid' AND MONTH(cl.clicked_at) = '$m' AND YEAR(cl.clicked_at) = '$current_year'";
    $res_m = mysqli_query($koneksi, $sql_m);
    $chart_values[] = mysqli_fetch_assoc($res_m)['total'];
}
$data_klik_json = json_encode($chart_values);

// --- 3. DATA TOP PERFORMING LINKS (IDE NO. 2) ---
$q_top_links = mysqli_query($koneksi, "SELECT title, short_code, click_count 
                                      FROM url 
                                      WHERE user_id = '$uid' 
                                      ORDER BY click_count DESC 
                                      LIMIT 5");

// --- 4. DATA RECENT ACTIVITY (IDE NO. 3) ---
$q_recent = mysqli_query($koneksi, "SELECT u.title, u.short_code, cl.clicked_at 
                                   FROM click_logs cl 
                                   JOIN url u ON cl.url_id = u.id 
                                   WHERE u.user_id = '$uid' 
                                   ORDER BY cl.clicked_at DESC 
                                   LIMIT 5");
?>

<div class="container-fluid">
    <h2 class="fw-bold mb-4">Analytics Overview</h2>

    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="bit-card p-4 h-100 border-start border-4 border-primary shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-3 rounded-circle text-primary"><i class="bi bi-link-45deg fs-3"></i></div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Total Links</small>
                        <h3 class="fw-bold mb-0"><?= $total_link ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="bit-card p-4 h-100 border-start border-4 border-success shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-3 rounded-circle text-success"><i class="bi bi-bar-chart-fill fs-3"></i></div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Total Clicks</small>
                        <h3 class="fw-bold mb-0"><?= $total_click ?></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="bit-card p-4 h-100 border-start border-4 border-warning shadow-sm">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-light p-3 rounded-circle text-warning"><i class="bi bi-lightning-fill fs-3"></i></div>
                    <div>
                        <small class="text-muted fw-bold text-uppercase">Avg. Clicks/Link</small>
                        <h3 class="fw-bold mb-0"><?= $avg_clicks ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-12">
            <div class="bit-card p-4 shadow-sm">
                <h5 class="fw-bold mb-4"><i class="bi bi-graph-up-arrow me-2 text-success"></i>Performa Klik Bulanan (<?= $current_year ?>)</h5>
                <div style="height: 300px;"><canvas id="mainChart"></canvas></div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-7">
            <div class="bit-card p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-trophy me-2 text-warning"></i>Top 5 Performing Links</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Link Title</th>
                                <th class="text-center">Clicks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($top = mysqli_fetch_assoc($q_top_links)): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark"><?= $top['title'] ?: 'Tanpa Judul' ?></div>
                                    <small class="text-success">baak.link/<?= $top['short_code'] ?></small>
                                </td>
                                <td class="text-center"><span class="badge bg-success rounded-pill px-3"><?= $top['click_count'] ?></span></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="bit-card p-4 shadow-sm h-100">
                <h5 class="fw-bold mb-4"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Click Activity</h5>
                <div class="activity-list">
                    <?php if (mysqli_num_rows($q_recent) > 0): ?>
                        <?php while($rec = mysqli_fetch_assoc($q_recent)): ?>
                        <div class="d-flex align-items-center gap-3 mb-3 pb-3 border-bottom">
                            <div class="bg-light p-2 rounded text-primary"><i class="bi bi-cursor-fill"></i></div>
                            <div class="overflow-hidden">
                                <div class="fw-bold text-truncate small"><?= $rec['title'] ?: '/'.$rec['short_code'] ?></div>
                                <small class="text-muted" style="font-size: 0.7rem;"><?= date('d M Y, H:i', strtotime($rec['clicked_at'])) ?> WIB</small>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="text-center text-muted py-4">Belum ada aktivitas klik.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('mainChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar', // DIUBAH KE BAR AGAR LEBIH RAPI
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
            datasets: [{
                label: 'Jumlah Klik',
                data: <?= $data_klik_json ?>,
                backgroundColor: '#006341', // Hijau Unissula
                borderRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });
</script>