<?php
include 'koneksi.php'; // Pastikan koneksi database

if (isset($_GET['id'])) {
    $lowongan_id = $_GET['id'];

    // 1. Hapus data dari tabel lamaran yang terkait dengan lowongan_id
    $deleteLamaran = "DELETE FROM lamaran WHERE lowongan_id = ?";
    $stmtLamaran = $conn->prepare($deleteLamaran);
    $stmtLamaran->bind_param("i", $lowongan_id);
    $stmtLamaran->execute();
    $stmtLamaran->close();

    // 2. Hapus data dari tabel lowongan
    $deleteLowongan = "DELETE FROM lowongan WHERE lowongan_id = ?";
    $stmtLowongan = $conn->prepare($deleteLowongan);
    $stmtLowongan->bind_param("i", $lowongan_id);

    if ($stmtLowongan->execute()) {
        $stmtLowongan->close();
        header("Location: admin-lowongan.php?msg=Lowongan berhasil dihapus");
        exit();
    } else {
        echo "Gagal menghapus lowongan: " . $stmtLowongan->error;
    }
} else {
    echo "ID lowongan tidak ditemukan.";
}
?>
