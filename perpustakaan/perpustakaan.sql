-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 09, 2025 at 08:57 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `pengarang` varchar(255) NOT NULL,
  `penerbit` varchar(255) DEFAULT NULL,
  `tahun_terbit` year(4) DEFAULT NULL,
  `isbn` varchar(20) DEFAULT NULL,
  `jumlah_halaman` int(11) DEFAULT NULL,
  `kategori` enum('Fiksi','Non-Fiksi','Sains','Teknologi','Sejarah','Biografi','Pendidikan') DEFAULT 'Fiksi',
  `status` enum('Tersedia','Dipinjam','Rusak') DEFAULT 'Tersedia',
  `tanggal_masuk` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_update` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id`, `judul`, `pengarang`, `penerbit`, `tahun_terbit`, `isbn`, `jumlah_halaman`, `kategori`, `status`, `tanggal_masuk`, `tanggal_update`) VALUES
(1, 'Bumi Manusia', 'Pramoedya Ananta Toer', 'Hasta Mitra', '1980', '978-979-416-044-7', 535, 'Fiksi', 'Tersedia', '2025-06-09 06:22:25', '2025-06-09 06:50:48'),
(2, 'Sapiens', 'Yuval Noah Harari', 'Pustaka Alvabet', '2014', '978-602-291-150-2', 512, 'Sejarah', 'Dipinjam', '2025-06-09 06:22:25', '2025-06-09 06:50:55'),
(3, 'Clean Code', 'Robert C. Martin', 'Prentice Hall', '2008', '978-0132350884', 464, 'Teknologi', 'Tersedia', '2025-06-09 06:22:25', '2025-06-09 06:50:59'),
(4, 'Atomic Habits', 'James Clear', 'Avery', '2018', '978-0735211292', 320, 'Non-Fiksi', 'Dipinjam', '2025-06-09 06:22:25', '2025-06-09 06:51:03'),
(5, 'Lada Hitam', 'Charloss', 'Hytham', '2025', '978-123456789', 21, 'Biografi', 'Dipinjam', '2025-06-09 06:38:44', '2025-06-09 06:51:06'),
(6, 'Pedang Kuda Hitam', 'Charlie Toulous', 'Hythem', '2025', '978-2233554466', 21, 'Biografi', 'Dipinjam', '2025-06-09 06:52:30', '2025-06-09 06:54:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
