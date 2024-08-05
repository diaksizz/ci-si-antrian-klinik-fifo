-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Waktu pembuatan: 27 Bulan Mei 2024 pada 14.46
-- Versi server: 10.4.17-MariaDB
-- Versi PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_antrian`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `antrian_poli`
--

CREATE TABLE `antrian_poli` (
  `id_antrian_poli` int(4) NOT NULL,
  `id_antrian` int(4) NOT NULL,
  `id_pasien` int(4) NOT NULL,
  `id_poli` int(2) NOT NULL,
  `no_antrian_poli` varchar(10) NOT NULL,
  `tgl_antrian_poli` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `antrian_poli`
--

INSERT INTO `antrian_poli` (`id_antrian_poli`, `id_antrian`, `id_pasien`, `id_poli`, `no_antrian_poli`, `tgl_antrian_poli`) VALUES
(113, 0, 19, 2, '1', '2024-05-27 19:03:35'),
(114, 0, 19, 1, '1', '2024-05-27 19:11:49'),
(115, 0, 19, 6, '1', '2024-05-27 19:12:08'),
(116, 0, 19, 5, '1', '2024-05-27 19:38:04'),
(117, 0, 19, 3, '1', '2024-05-27 19:38:08'),
(118, 0, 19, 3, '1', '2024-05-27 19:38:18'),
(119, 0, 19, 2, '1', '2024-05-27 19:38:22'),
(120, 0, 19, 6, '1', '2024-05-27 19:38:35'),
(121, 0, 19, 1, '1', '2024-05-27 19:38:44'),
(122, 0, 19, 4, '1', '2024-05-27 19:40:11'),
(123, 0, 19, 2, '1', '2024-05-27 19:40:15'),
(124, 0, 19, 4, '1', '2024-05-27 19:40:20'),
(125, 0, 19, 6, '1', '2024-05-27 19:40:35'),
(126, 0, 19, 3, '2', '2024-05-27 19:42:10'),
(127, 0, 19, 3, '2', '2024-05-27 19:42:11'),
(128, 0, 19, 3, '2', '2024-05-27 19:42:28'),
(129, 0, 19, 3, '2', '2024-05-27 19:42:29'),
(130, 0, 19, 3, '2', '2024-05-27 19:42:39'),
(131, 0, 19, 5, '1', '2024-05-27 19:42:44'),
(132, 0, 19, 1, '2', '2024-05-27 19:43:10'),
(133, 0, 19, 5, '2', '2024-05-27 19:43:14'),
(134, 0, 19, 5, '3', '2024-05-27 19:43:18'),
(135, 0, 19, 1, '3', '2024-05-27 19:43:40'),
(136, 0, 19, 1, '4', '2024-05-27 19:43:43');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `antrian_poli`
--
ALTER TABLE `antrian_poli`
  ADD PRIMARY KEY (`id_antrian_poli`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `antrian_poli`
--
ALTER TABLE `antrian_poli`
  MODIFY `id_antrian_poli` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
