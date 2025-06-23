<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'];
    $perusahaan = $_POST['perusahaan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $batas_lamaran = $_POST['batas_lamaran'];
    $kualifikasi = $_POST['kualifikasi'];
    $pendidikan = $_POST['minimal_pendidikan']; // ✅ Tambahan field pendidikan
    $created_by = $_SESSION['user_id'];

    // Gunakan prepared statement agar lebih aman (opsional tapi direkomendasikan)
    $query = "INSERT INTO lowongan (judul, perusahaan, deskripsi, lokasi, batas_lamaran, kualifikasi, minimal_pendidikan, created_by) 
              VALUES ('$judul', '$perusahaan', '$deskripsi', '$lokasi', '$batas_lamaran', '$kualifikasi', '$pendidikan', '$created_by')";

    if (mysqli_query($conn, $query)) {
        header("Location: perusahaan-lowongan.php?status=sukses");
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>