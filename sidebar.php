<?php
// Tentukan menu aktif berdasarkan parameter URL 'page'
$p = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<div class="sidebar">
    <a href="index.php" class="brand">
        <i class="bi bi-link-45deg"></i> BAAk.link
    </a>

    <div class="create-btn-wrapper">
        <a href="index.php?page=home" class="btn btn-primary w-100 fw-bold py-2 shadow-sm">
            <i class="bi bi-plus-lg"></i> Buat Link Baru
        </a>
    </div>

    <div class="nav flex-column sidebar-menu">
        <a href="index.php?page=home" class="nav-link <?= ($p == 'home' || $p == '') ? 'active' : '' ?>">
            <i class="bi bi-house-door"></i> Home
        </a>

        <a href="index.php?page=links" class="nav-link <?= ($p == 'links' || $p == 'edit') ? 'active' : '' ?>">
            <i class="bi bi-list-ul"></i> Links
        </a>

        <a href="index.php?page=folders" class="nav-link <?= ($p == 'folders') ? 'active' : '' ?>">
            <i class="bi bi-folder"></i> Folder Manajemen
        </a>

        <a href="index.php?page=analytics" class="nav-link <?= ($p == 'analytics') ? 'active' : '' ?>">
            <i class="bi bi-graph-up"></i> Analytics
        </a>

        <div class="mt-4 px-4 text-muted small fw-bold text-uppercase">Akun Admin</div>

        <a href="index.php?page=settings" class="nav-link <?= ($p == 'settings') ? 'active' : '' ?>">
            <i class="bi bi-gear"></i> Settings
        </a>

        <a href="logout.php" class="nav-link text-danger">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>
</div>