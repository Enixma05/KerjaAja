<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'];
    $perusahaan = $_POST['perusahaan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $batas_lamaran = $_POST['batas_lamaran'];
    $kualifikasi = $_POST['kualifikasi'];

    $query = "INSERT INTO lowongan (judul, perusahaan, deskripsi, lokasi, batas_lamaran, kualifikasi) 
              VALUES ('$judul', '$perusahaan', '$deskripsi', '$lokasi', '$batas_lamaran', '$kualifikasi')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: admin-lowongan.php?status=sukses");
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>