<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = $_POST['nama'];
    $tanggal = $_POST['tanggal'];
    $lokasi = $_POST['lokasi'];
    $kuota = $_POST['kuota'];
    $deskripsi = $_POST['deskripsi'];

    $query = "INSERT INTO pelatihan (nama, tanggal, lokasi, kuota, deskripsi) 
              VALUES ('$nama', '$tanggal', '$lokasi', '$kuota', '$deskripsi')";
    
    if (mysqli_query($conn, $query)) {
        header("Location: admin-pelatihan.php?status=sukses");
    } else {
        echo "Gagal: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
