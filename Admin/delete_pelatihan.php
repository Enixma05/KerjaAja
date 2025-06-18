<?php
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hindari SQL injection dengan prepare statement (opsional, tapi aman)
    $stmt = $conn->prepare("DELETE FROM pelatihan WHERE pelatihan_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin-pelatihan.php"); // Redirect ke halaman utama
        exit();
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID pelatihan tidak ditemukan di URL.";
}

$conn->close();
?>
