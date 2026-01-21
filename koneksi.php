<?php
$koneksi = mysqli_connect("localhost", "root", "", "short_url");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
