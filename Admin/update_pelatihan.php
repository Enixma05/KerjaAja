<?php
session_start();
include '../auth/koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $kuota = $_POST['kuota'];
    $deskripsi = $_POST['deskripsi'];

    $query = "UPDATE pelatihan SET nama=?, tanggal=?, lokasi=?, kuota=?, deskripsi=? WHERE pelatihan_id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssssi", $nama, $tanggal, $lokasi, $kuota, $deskripsi, $id);
    
    if (mysqli_stmt_execute($stmt)) {
        header("Location: admin-pelatihan.php?update=success");
    } else {
        echo "Gagal memperbarui data.";
    }

    mysqli_close($conn);
}
?>
