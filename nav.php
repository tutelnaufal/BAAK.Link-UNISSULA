<header>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #004d32;">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">BAAk Link</a>
            <div class="ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <span class="text-white me-3 small">Admin: <?= $_SESSION['username'] ?></span>
                    <a class="btn btn-danger btn-sm" href="logout.php">Logout</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</header>