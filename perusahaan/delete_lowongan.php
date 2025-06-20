<?php
include '../auth/koneksi.php'; 

if (isset($_GET['id'])) {
    $lowongan_id = $_GET['id'];

    $deleteLamaran = "DELETE FROM lamaran WHERE lowongan_id = ?";
    $stmtLamaran = $conn->prepare($deleteLamaran);
    $stmtLamaran->bind_param("i", $lowongan_id);
    $stmtLamaran->execute();
    $stmtLamaran->close();

    $deleteLowongan = "DELETE FROM lowongan WHERE lowongan_id = ?";
    $stmtLowongan = $conn->prepare($deleteLowongan);
    $stmtLowongan->bind_param("i", $lowongan_id);

    if ($stmtLowongan->execute()) {
        $stmtLowongan->close();
        header("Location: perusahaan-lowongan.php?msg=Lowongan berhasil dihapus");
        exit();
    } else {
        echo "Gagal menghapus lowongan: " . $stmtLowongan->error;
    }
} else {
    echo "ID lowongan tidak ditemukan.";
}
?>