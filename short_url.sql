-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 20 Jan 2026 pada 22.42
-- Versi server: 10.4.32-MariaDB
-- Versi PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `short_url`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `click_logs`
--

CREATE TABLE `click_logs` (
  `id` int(11) NOT NULL,
  `url_id` int(11) NOT NULL,
  `clicked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `click_logs`
--

INSERT INTO `click_logs` (`id`, `url_id`, `clicked_at`, `ip_address`, `user_agent`) VALUES
(1, 1, '2026-01-20 20:02:26', NULL, NULL),
(2, 2, '2026-01-20 20:05:49', NULL, NULL),
(3, 1, '2026-01-20 20:51:17', NULL, NULL),
(4, 1, '2026-01-20 20:51:35', NULL, NULL),
(5, 1, '2026-01-20 20:54:46', NULL, NULL),
(6, 2, '2026-01-20 20:56:11', NULL, NULL),
(7, 2, '2026-01-20 20:56:39', NULL, NULL),
(8, 1, '2026-01-20 20:58:24', NULL, NULL),
(9, 1, '2026-01-20 21:01:43', NULL, NULL),
(10, 4, '2026-01-20 21:37:30', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `folders`
--

CREATE TABLE `folders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_folder` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `folders`
--

INSERT INTO `folders` (`id`, `user_id`, `nama_folder`, `created_at`) VALUES
(1, 1, 'WISUDA', '2026-01-20 19:59:59'),
(3, 1, 'BEASISWA', '2026-01-20 21:30:50');

-- --------------------------------------------------------

--
-- Struktur dari tabel `url`
--

CREATE TABLE `url` (
  `id` int(11) NOT NULL,
  `long_url` text NOT NULL,
  `short_code` varchar(50) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `click_count` int(11) DEFAULT 0,
  `link_password` varchar(100) DEFAULT NULL,
  `is_custom` tinyint(1) DEFAULT 0,
  `folder_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `url`
--

INSERT INTO `url` (`id`, `long_url`, `short_code`, `title`, `created_at`, `user_id`, `click_count`, `link_password`, `is_custom`, `folder_id`) VALUES
(1, 'https://drive.google.com/drive/folders/1L0zrV1IGsAV7L3_KEPXBPGxcMgOygE8g?usp=drive_link', 'wisuda', 'Link Pendukung Wisuda', '2026-01-20 20:01:42', 1, 6, 'wisuda26', 0, 1),
(4, 'https://drive.google.com/drive/folders/12bViw04SixZIEep6BPAMxybo8ztFyron?usp=drive_link', 'beasiswa', 'Data BEASISWA', '2026-01-20 21:37:14', 1, 1, '', 0, 3);

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'admin1', 'admin@baak.unissula.ac.id', 'admin123', '2026-01-20 18:34:54');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `click_logs`
--
ALTER TABLE `click_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `url_id` (`url_id`);

--
-- Indeks untuk tabel `folders`
--
ALTER TABLE `folders`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `url`
--
ALTER TABLE `url`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `short_code` (`short_code`),
  ADD UNIQUE KEY `short_code_2` (`short_code`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `click_logs`
--
ALTER TABLE `click_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `folders`
--
ALTER TABLE `folders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `url`
--
ALTER TABLE `url`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
