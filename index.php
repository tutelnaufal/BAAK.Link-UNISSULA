<?php
include "config/koneksi.php";
include "config/defaultFunc.php";

$page = isset($_GET['page']) ? $_GET['page'] : "";

// 1. PROTEKSI ADMIN
if (!isset($_SESSION['user_id']) && !in_array($page, ['short', 'password_check'])) {
    if ($page != "" || !isset($_GET['code'])) {
        header("Location: login.php");
        exit;
    }
}

// 2. LOGIKA PENYIMPANAN DATA (POST)
if (isset($_POST['submit'])) {
  $long_url = mysqli_real_escape_string($koneksi, $_POST['url']);
  $user_id  = $_SESSION['user_id'];
  $title    = mysqli_real_escape_string($koneksi, $_POST['title'] ?? '');
  $f_id     = (isset($_POST['folder_id']) && $_POST['folder_id'] != 'NULL') ? $_POST['folder_id'] : 'NULL';
  $pass     = mysqli_real_escape_string($koneksi, $_POST['password_link'] ?? '');
  $custom   = mysqli_real_escape_string($koneksi, $_POST['custom_code'] ?? '');

  $short_code = !empty($custom) ? $custom : generate_code(5);
  
  $cek = mysqli_query($koneksi, "SELECT * FROM url WHERE short_code = '$short_code'");
  if (mysqli_num_rows($cek) > 0) {
      echo "<script>alert('Kode sudah ada!'); window.history.back();</script>";
      exit;
  }

  $query = "INSERT INTO url (long_url, short_code, user_id, title, link_password, folder_id) 
            VALUES ('$long_url', '$short_code', $user_id, '$title', '$pass', $f_id)";
  
  if (mysqli_query($koneksi, $query)) {
    header("Location: index.php?page=short&code=$short_code");
    exit;
  }
}

// 3. LOGIKA REDIRECT & CEK PASSWORD
$halaman_sistem = ['home', 'links', 'analytics', 'edit', 'short', 'folders', 'password_check', ''];
if (!in_array($page, $halaman_sistem)) {
  $cek = mysqli_query($koneksi, "SELECT * FROM url WHERE short_code = '$page'");
  
  if (mysqli_num_rows($cek) > 0) {
    $data = mysqli_fetch_assoc($cek);
    $u_id = $data['id'];

    if (!empty($data['link_password'])) {
        $_SESSION['temp_url_id'] = $u_id;
        header("Location: index.php?page=password_check");
        exit;
    } else {
        mysqli_query($koneksi, "UPDATE url SET click_count = click_count + 1 WHERE id = '$u_id'");
        mysqli_query($koneksi, "INSERT INTO click_logs (url_id) VALUES ('$u_id')");
        header("Location: " . $data['long_url']);
        exit;
    }
  }
}

include "template/head.php";
?>

<div class="top-header">
  <div class="header-left">
    <img src="assets/css/unissula.png" class="logo">
    <div class="title-text">
      <div class="title-main">SISTEM PEMENDEK LINK</div>
      <div class="title-sub">Biro Administrasi Akademik Universitas Islam Sultan Agung</div>
    </div>
  </div>
  
  <div class="header-right pe-4 text-white">
    <?php if (isset($_SESSION['user_id']) && $page != 'password_check'): ?>
        <b>Admin: <?= $_SESSION['username'] ?></b>
    <?php endif; ?>
  </div>
</div>

<?php if (isset($_SESSION['user_id']) && $page != 'password_check'): ?>
<div class="app-container">
    <?php include "template/sidebar.php"; ?>
    <div class="main-content">
      <?php
      if ($page == "home" || $page == "") include "pages/home.php";
      else if ($page == "links") include "pages/links.php";
      else if ($page == "folders") include "pages/folders.php";
      else if ($page == "analytics") include "pages/analytics.php";
      else if ($page == "edit") include "pages/edit.php";
      else if ($page == "short") include "pages/short.php";
      else if ($page == "settings") include "pages/settings.php";
      else include "pages/home.php";
      ?>
    </div>
</div>
<?php else: ?>
  <main>
    <?php
    if ($page == "short") include "pages/short.php";
    else if ($page == "password_check") {
      if (isset($_POST['check_pass'])) {
        $input_pass = mysqli_real_escape_string($koneksi, $_POST['pass_input']);
        $id_url = $_SESSION['temp_url_id'];

        $q = mysqli_query($koneksi, "SELECT * FROM url WHERE id='$id_url'");
        $d = mysqli_fetch_assoc($q);

        if ($input_pass == $d['link_password']) {
            mysqli_query($koneksi, "UPDATE url SET click_count = click_count + 1 WHERE id = '$id_url'");
            mysqli_query($koneksi, "INSERT INTO click_logs (url_id) VALUES ('$id_url')");
            header("Location: " . $d['long_url']);
            exit;
        } else {
          echo "<script>alert('Password Salah!');</script>";
        }
      }
    ?>
      <div class="container mt-5">
        <div class="card shadow p-4 mx-auto text-center border-0" style="max-width:400px; border-radius:15px; margin-top: 100px;">
          <h3 class="fw-bold text-success mb-3"><i class="bi bi-shield-lock"></i> Link Terkunci</h3>
          <p class="text-muted small">Silakan masukkan password untuk mengakses link.</p>
          <form method="POST">
            <input type="password" name="pass_input" class="form-control mb-3 text-center" placeholder="Password..." required>
            <button type="submit" name="check_pass" class="btn btn-success w-100 fw-bold">BUKA LINK</button>
          </form>
        </div>
      </div>
    <?php } ?>
  </main>
<?php endif; ?>

<?php include "template/footer.php"; ?>