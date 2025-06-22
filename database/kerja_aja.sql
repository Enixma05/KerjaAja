-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 22, 2025 at 06:46 PM
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
-- Database: `kerja_aja`
--

-- --------------------------------------------------------

--
-- Table structure for table `keahlian`
--

CREATE TABLE `keahlian` (
  `keahlian_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nama_keahlian` varchar(100) NOT NULL,
  `tingkat` enum('Pemula','Menengah','Mahir','Ahli') NOT NULL,
  `kategori` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lamaran`
--

CREATE TABLE `lamaran` (
  `id` int(11) NOT NULL,
  `lowongan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `path_cv` varchar(255) NOT NULL,
  `surat_lamaran` text DEFAULT NULL,
  `tanggal_lamar` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) DEFAULT 'Menunggu review'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lamaran`
--

INSERT INTO `lamaran` (`id`, `lowongan_id`, `user_id`, `path_cv`, `surat_lamaran`, `tanggal_lamar`, `status`) VALUES
(1, 2, 3, '../uploads/cv/cv_3_2_1749644007.pdf', '', '2025-06-11 12:13:27', 'Ditolak'),
(3, 3, 3, '../uploads/cv/cv_3_3_1749645756.pdf', '', '2025-06-11 12:42:36', 'Ditolak'),
(5, 4, 3, '../uploads/cv/cv_3_4_1749655146.pdf', '', '2025-06-11 15:19:06', 'Menunggu review'),
(8, 2, 6, '../uploads/cv/cv_6_2_1749656908.pdf', '', '2025-06-11 15:48:28', 'Diterima');

-- --------------------------------------------------------

--
-- Table structure for table `lowongan`
--

CREATE TABLE `lowongan` (
  `lowongan_id` int(11) NOT NULL,
  `judul` varchar(150) NOT NULL,
  `perusahaan` varchar(100) DEFAULT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `kualifikasi` text DEFAULT NULL,
  `batas_lamaran` date DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `minimal_pendidikan` enum('SMA/SMK','Diploma','Sarjana','Magister','Doktor') DEFAULT 'SMA/SMK'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lowongan`
--

INSERT INTO `lowongan` (`lowongan_id`, `judul`, `perusahaan`, `lokasi`, `deskripsi`, `kualifikasi`, `batas_lamaran`, `created_by`, `minimal_pendidikan`) VALUES
(1, 'Customer Service', 'PT Maju Bersama', 'Kecamatan Maju Jaya', 'Full Time', 'Kami mencari Customer Service Representative yang berpengalaman untuk melayani pelanggan kami.', '2025-06-30', 8, 'SMA/SMK'),
(2, 'Admin Kantor', 'CV Sukses Mandiri', 'Desa Sukamaju', 'Full Time', 'Dibutuhkan admin kantor untuk mengelola administrasi perusahaan. Kandidat harus teliti, rapi, dan bertanggung jawab', '2025-06-25', 9, 'Diploma'),
(3, 'Barista', 'Kopi Kita', 'Kecamatan Maju Jaya', 'Full Time', 'Kopi Kita membuka lowongan untuk posisi barista. Pengalaman tidak diutamakan, akan dilatih.', '2025-06-20', 10, 'SMA/SMK'),
(4, 'Digital Marketing Staff', 'PT Digital Kreatif', 'Desa Sukamaju', 'Full Time', 'PT Digital Kreatif mencari Digital Marketing Staff untuk mengelola kampanye digital perusahaan.', '2025-07-10', 11, 'Sarjana'),
(7, 'Web Developer', 'PT Teknologi Hebat', 'Mataram', 'Full Time', 'Dibutuhkan pelamar yang cekatan dan mampu bekerja sama dengan baik', '2025-07-15', 12, 'Sarjana'),
(8, 'IT Support', 'PT Teknologi Hebat', 'Mataram', 'Full Time', 'Ngerti IT', '2025-06-30', 12, 'Sarjana'),
(9, 'Network Support', 'PT Teknologi Hebat', 'Mataram', 'Kontrak', 'Ngerti jaringan', '2025-06-29', 12, 'SMA/SMK');

-- --------------------------------------------------------

--
-- Table structure for table `pelatihan`
--

CREATE TABLE `pelatihan` (
  `pelatihan_id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `lokasi` varchar(100) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `kuota` int(10) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pelatihan`
--

INSERT INTO `pelatihan` (`pelatihan_id`, `nama`, `lokasi`, `tanggal`, `kuota`, `deskripsi`, `created_by`) VALUES
(2, 'Pelatihan Desain Grafis', 'Aula Kecamatan Maju Jaya', '2025-06-20', 14, 'Pelatihan desain grafis menggunakan Adobe Photoshop dan Illustrator. Cocok untuk pemula yang tertarik dengan desain grafis dan ingin belajar lebih dalam tentang desain grafis.', NULL),
(4, 'Pelatihan Keterampilan Komputer Dasar', 'Balai Desa Sukamaju', '2025-06-29', 28, 'Pelatihan dasar penggunaan komputer, Microsoft Office, dan internet. Cocok untuk pemula yang ingin belajar lebih dalam tentang komputer dan berbagai aplikasi yang sering digunakan di perusahaan.', NULL),
(5, 'Pelatihan Barista', 'Aula Kecamatan Maju Jaya', '2025-07-05', 10, 'Pelatihan dasar menjadi barista. Peserta akan belajar tentang jenis kopi, teknik brewing, dan latte. Selain itu peserta juga diberikan materi dasar mengenai service.', NULL),
(7, 'Pelatihan Design UI/UX', 'Fakultas Teknik Prodi Teknik Informatika', '2025-06-30', 30, 'Pelatihan dasar UI/UX design', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pendaftaran_pelatihan`
--

CREATE TABLE `pendaftaran_pelatihan` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pelatihan_id` int(11) NOT NULL,
  `tanggal_daftar` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendaftaran_pelatihan`
--

INSERT INTO `pendaftaran_pelatihan` (`id`, `user_id`, `pelatihan_id`, `tanggal_daftar`) VALUES
(3, 3, 2, '2025-06-11 11:24:19'),
(4, 3, 5, '2025-06-11 12:40:36'),
(6, 3, 4, '2025-06-11 14:58:39'),
(8, 6, 5, '2025-06-11 15:48:13'),
(9, 6, 4, '2025-06-11 15:48:17');

-- --------------------------------------------------------

--
-- Table structure for table `pendidikan`
--

CREATE TABLE `pendidikan` (
  `pendidikan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jenjang` enum('SMA/SMK','Diploma','Sarjana','Magister','Doktor') NOT NULL,
  `nama_institusi` varchar(150) NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `tahun_lulus` int(4) NOT NULL,
  `nilai` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pendidikan`
--

INSERT INTO `pendidikan` (`pendidikan_id`, `user_id`, `jenjang`, `nama_institusi`, `jurusan`, `tahun_lulus`, `nilai`) VALUES
(1, 3, 'SMA/SMK', 'SMA Negeri 1 Jakarta', 'IPA', 2022, 3.10),
(2, 5, 'Sarjana', 'Institut Teknologi Bandung', 'Teknik Informatika', 2020, 3.90),
(3, 6, 'Diploma', 'Politeknik Negeri Bandung', 'Teknik Aeornautika', 2019, 3.50);

-- --------------------------------------------------------

--
-- Table structure for table `pengalaman_kerja`
--

CREATE TABLE `pengalaman_kerja` (
  `pengalaman_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `posisi` varchar(100) NOT NULL,
  `nama_perusahaan` varchar(150) NOT NULL,
  `tanggal_mulai` date NOT NULL,
  `tanggal_selesai` date DEFAULT NULL,
  `masih_bekerja` tinyint(1) DEFAULT 0,
  `deskripsi` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','perusahaan','user') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'admin', 'admin@gmail.com', 'admin', 'admin', '2025-06-11 08:12:42'),
(3, 'andrew nathaniel', 'andrewkoylal7@gmail.com', '091004', 'user', '2025-06-11 08:08:41'),
(5, 'Fadlullah Hasan', 'fad.mok123@gmail.com', '12345', 'user', '2025-06-11 14:24:51'),
(6, 'bayu', 'bayuaji@gmail.com', 'bayu123', 'user', '2025-06-11 15:47:56'),
(7, 'Rockstar', 'rockstar@gmail.com', 'bintangbatu', 'perusahaan', '2025-06-18 15:00:19'),
(8, 'PT Maju Bersama', 'majubersama@gmail.com', 'mb123', 'perusahaan', '2025-06-22 08:00:07'),
(9, 'CV Sukses Mandiri', 'suksesmandiri@gmail.com', 'sm123', 'perusahaan', '2025-06-22 08:00:54'),
(10, 'Kopi Kita', 'kopikita@gmail.com', 'kk123', 'perusahaan', '2025-06-22 08:02:34'),
(11, 'PT Digital Kreatif', 'digitalkreatif@gmail.com', 'dk123', 'perusahaan', '2025-06-22 08:02:34'),
(12, 'PT Teknologi Hebat', 'teknologihebat@gmail.com', 'th123', 'perusahaan', '2025-06-22 08:03:55');

-- --------------------------------------------------------

--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') DEFAULT NULL,
  `agama` varchar(50) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `path_avatar` varchar(255) DEFAULT 'img/default-avatar.png',
  `path_cv` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `telepon`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `alamat`, `desa`, `kecamatan`, `kota`, `provinsi`, `kode_pos`, `path_avatar`, `path_cv`) VALUES
(1, 3, '082236537436', NULL, 'Laki-laki', 'Kristen', 'ampenan', NULL, NULL, NULL, NULL, NULL, 'uploads/avatars/avatar_3_1749668024.jpg', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `keahlian`
--
ALTER TABLE `keahlian`
  ADD PRIMARY KEY (`keahlian_id`),
  ADD KEY `keahlian_ibfk_1` (`user_id`);

--
-- Indexes for table `lamaran`
--
ALTER TABLE `lamaran`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_application` (`lowongan_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD PRIMARY KEY (`lowongan_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `pelatihan`
--
ALTER TABLE `pelatihan`
  ADD PRIMARY KEY (`pelatihan_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pelatihan_id` (`pelatihan_id`);

--
-- Indexes for table `pendidikan`
--
ALTER TABLE `pendidikan`
  ADD PRIMARY KEY (`pendidikan_id`),
  ADD KEY `pendidikan_ibfk_1` (`user_id`);

--
-- Indexes for table `pengalaman_kerja`
--
ALTER TABLE `pengalaman_kerja`
  ADD PRIMARY KEY (`pengalaman_id`),
  ADD KEY `pengalaman_kerja_ibfk_1` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `keahlian`
--
ALTER TABLE `keahlian`
  MODIFY `keahlian_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lamaran`
--
ALTER TABLE `lamaran`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lowongan`
--
ALTER TABLE `lowongan`
  MODIFY `lowongan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `pelatihan`
--
ALTER TABLE `pelatihan`
  MODIFY `pelatihan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `pendidikan`
--
ALTER TABLE `pendidikan`
  MODIFY `pendidikan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `pengalaman_kerja`
--
ALTER TABLE `pengalaman_kerja`
  MODIFY `pengalaman_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `keahlian`
--
ALTER TABLE `keahlian`
  ADD CONSTRAINT `keahlian_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `lamaran`
--
ALTER TABLE `lamaran`
  ADD CONSTRAINT `lamaran_ibfk_1` FOREIGN KEY (`lowongan_id`) REFERENCES `lowongan` (`lowongan_id`),
  ADD CONSTRAINT `lamaran_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `lowongan`
--
ALTER TABLE `lowongan`
  ADD CONSTRAINT `fk_lowongan_created_by` FOREIGN KEY (`created_by`) REFERENCES `users` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pendaftaran_pelatihan`
--
ALTER TABLE `pendaftaran_pelatihan`
  ADD CONSTRAINT `pendaftaran_pelatihan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pendaftaran_pelatihan_ibfk_2` FOREIGN KEY (`pelatihan_id`) REFERENCES `pelatihan` (`pelatihan_id`) ON DELETE CASCADE;

--
-- Constraints for table `pendidikan`
--
ALTER TABLE `pendidikan`
  ADD CONSTRAINT `pendidikan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `pengalaman_kerja`
--
ALTER TABLE `pengalaman_kerja`
  ADD CONSTRAINT `pengalaman_kerja_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
