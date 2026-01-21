<?php
session_start(); // Wajib paling atas

function base_url($link = "")
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $path = str_replace("index.php", "", $_SERVER['PHP_SELF']);
    return $protocol . $host . $path . $link;
}

function redirect($url)
{
    echo "<script>window.location.href='" . base_url($url) . "';</script>";
    exit;
}

function generate_code($length = 5)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
