<?php
include '../auth/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $judul = $_POST['judul'];
    $perusahaan = $_POST['perusahaan'];
    $deskripsi = $_POST['deskripsi'];
    $lokasi = $_POST['lokasi'];
    $batas_lamaran = $_POST['batas_lamaran'];
    $kualifikasi = $_POST['kualifikasi'];
    $pendidikan = $_POST['pendidikan']; // ✅ Tambahan field pendidikan

    // Gunakan prepared statement agar lebih aman (opsional tapi direkomendasikan)
    $query = "INSERT INTO lowongan (judul, perusahaan, deskripsi, lokasi, batas_lamaran, kualifikasi, pendidikan) 
              VALUES ('$judul', '$perusahaan', '$deskripsi', '$lokasi', '$batas_lamaran', '$kualifikasi', '$pendidikan')";

    if (mysqli_query($conn, $query)) {
        header("Location: perusahaan-lowongan.php?status=sukses");
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
