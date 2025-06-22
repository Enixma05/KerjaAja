<?php
include '../auth/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE lamaran SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $id);

    if ($stmt->execute()) {
        header("Location: perusahaan-pelamar.php?status=updated");
    } else {
        echo "Gagal memperbarui status.";
    }

    $stmt->close();
    $conn->close();
} else {
    header("Location: perusahaan-pelamar.php");
    exit();
}
?>
