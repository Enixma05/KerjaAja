<?php
include '../auth/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM pelatihan WHERE pelatihan_id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: admin-pelatihan.php");
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