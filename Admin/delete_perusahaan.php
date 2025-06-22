<?php
include '../auth/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Hindari SQL injection dengan prepare statement (opsional, tapi aman)
    $stmt = $conn->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin-perusahaan.php"); // Redirect ke halaman utama
        exit();
    } else {
        echo "Gagal menghapus data: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Tidak tidak ditemukan perusahaan untuk dihapus di URL.";
}

$conn->close();
?>
