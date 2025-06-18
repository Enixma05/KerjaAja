<?php
session_start();
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $judul = $_POST['judul'];
    $perusahaan = $_POST['perusahaan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $batas_lamaran = $_POST['batas_lamaran'];
    $kualifikasi = $_POST['kualifikasi'];

    $query = "UPDATE lowongan SET judul=?, perusahaan=?, deskripsi=?, lokasi=?, batas_lamaran=?, kualifikasi=? WHERE lowongan_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssssssi", $judul, $perusahaan, $deskripsi, $lokasi, $batas_lamaran, $kualifikasi, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin-lowongan.php?update=success");
    } else {
        echo "Gagal memperbarui data.";
    }

    mysqli_close($conn);
}
?>
