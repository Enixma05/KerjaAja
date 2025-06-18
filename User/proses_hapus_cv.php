<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

try {
    $stmt_select = $conn->prepare("SELECT path_cv FROM user_profiles WHERE user_id = ?");
    $stmt_select->bind_param("i", $user_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_cv_path = $row['path_cv'];

        if (!empty($current_cv_path) && file_exists($current_cv_path)) {
            if (!unlink($current_cv_path)) {
                throw new Exception("Gagal menghapus file CV fisik dari server.");
            }
        }
    }
    $stmt_select->close();

    $stmt_update = $conn->prepare("UPDATE user_profiles SET path_cv = NULL WHERE user_id = ?");
    $stmt_update->bind_param("i", $user_id);

    if ($stmt_update->execute()) {
        header("Location: profile.php?status=cv_dihapus");
        exit();
    } else {
        throw new Exception("Gagal memperbarui data di database.");
    }
    $stmt_update->close();

} catch (Exception $e) {
    header("Location: profile.php?error=" . urlencode($e->getMessage()));
    exit();
}
?>