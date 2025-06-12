<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
    
    $upload_dir = 'uploads/avatars/';
    $max_file_size = 2 * 1024 * 1024; 
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    
    $file_info = $_FILES['avatar'];
    $file_size = $file_info['size'];
    $file_type = $file_info['type'];
    
    if ($file_size > $max_file_size) {
        header("Location: profile.php?error=filesize");
        exit();
    }
    
    if (!in_array($file_type, $allowed_types)) {
        header("Location: profile.php?error=filetype");
        exit();
    }

    $stmt_old = $conn->prepare("SELECT path_avatar FROM user_profiles WHERE user_id = ?");
    $stmt_old->bind_param("i", $user_id);
    $stmt_old->execute();
    $result_old = $stmt_old->get_result();
    if ($result_old->num_rows > 0) {
        $old_avatar_path = $result_old->fetch_assoc()['path_avatar'];
        if (!empty($old_avatar_path) && $old_avatar_path !== 'img/default-avatar.png' && file_exists($old_avatar_path)) {
            unlink($old_avatar_path);
        }
    }
    $stmt_old->close();


    $file_extension = pathinfo($file_info['name'], PATHINFO_EXTENSION);
    $new_filename = "avatar_" . $user_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $new_filename;
    
    if (move_uploaded_file($file_info['tmp_name'], $target_file)) {
        
        $stmt_update = $conn->prepare("INSERT INTO user_profiles (user_id, path_avatar) VALUES (?, ?) ON DUPLICATE KEY UPDATE path_avatar = VALUES(path_avatar)");
        $stmt_update->bind_param("is", $user_id, $target_file);
        
        if ($stmt_update->execute()) {
            header("Location: profile.php?status=avatar_updated");
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
    header("Location: profile.php?error=no_file");
    exit();
}
?>