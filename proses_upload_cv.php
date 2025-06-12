<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {

    $upload_dir = 'uploads/cv/';
    $max_file_size = 5 * 1024 * 1024; 
    $allowed_type = 'application/pdf';
    
    $file_info = $_FILES['cv'];
    $file_size = $file_info['size'];
    $file_type = $file_info['type'];

    if ($file_size > $max_file_size) {

        header("Location: profile.php?error=cv_filesize");
        exit();
    }

    if ($file_type !== $allowed_type) {
        header("Location: profile.php?error=cv_filetype");
        exit();
    }

    $stmt_old = $conn->prepare("SELECT path_cv FROM user_profiles WHERE user_id = ?");
    $stmt_old->bind_param("i", $user_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($result_old->num_rows > 0) {
        $old_cv_path = $result_old->fetch_assoc()['path_cv'];

        if (!empty($old_cv_path) && file_exists($old_cv_path)) {
            unlink($old_cv_path); 
        }
    }
    $stmt_old->close();

    $file_extension = pathinfo($file_info['name'], PATHINFO_EXTENSION);
    $new_filename = "cv_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file_info['tmp_name'], $target_file)) {

        $stmt_update = $conn->prepare("INSERT INTO user_profiles (user_id, path_cv) VALUES (?, ?) ON DUPLICATE KEY UPDATE path_cv = VALUES(path_cv)");
        $stmt_update->bind_param("is", $user_id, $target_file);
        
        if ($stmt_update->execute()) {

            header("Location: profile.php?status=cv_sukses");
            exit();
        } else {
 
            unlink($target_file);
            header("Location: profile.php?error=db_update");
            exit();
        }
        $stmt_update->close();

    } else {
        header("Location: profile.php?error=upload_failed");
        exit();
    }

} else {
    header("Location: profile.php?error=no_cv_file");
    exit();
}
?>