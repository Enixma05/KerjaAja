<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$name = trim($_POST['name']);
$email = trim($_POST['email']);
$telepon = trim($_POST['telepon']);
$tanggal_lahir = !empty($_POST['tanggal_lahir']) ? trim($_POST['tanggal_lahir']) : NULL;
$jenis_kelamin = trim($_POST['jenis_kelamin']);
$agama = trim($_POST['agama']);
$alamat = trim($_POST['alamat']);

$errors = [];
if (empty($name)) {
    $errors[] = "Nama lengkap wajib diisi.";
}
if (empty($email)) {
    $errors[] = "Email wajib diisi.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Format email tidak valid.";
}

$stmt_check_email = $conn->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
$stmt_check_email->bind_param("si", $email, $user_id);
$stmt_check_email->execute();
if ($stmt_check_email->get_result()->num_rows > 0) {
    $errors[] = "Email ini sudah digunakan oleh akun lain.";
}
$stmt_check_email->close();

if (!empty($errors)) {
    $_SESSION['form_errors'] = $errors;
    header("Location: profile.php");
    exit();
}

$conn->begin_transaction();

try {
    $stmt_users = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE user_id = ?");
    $stmt_users->bind_param("ssi", $name, $email, $user_id);
    if (!$stmt_users->execute()) {
        throw new Exception("Gagal memperbarui data pengguna.");
    }
    $stmt_users->close();
    
    $query_profiles = "INSERT INTO user_profiles (user_id, telepon, tanggal_lahir, jenis_kelamin, agama, alamat) 
                       VALUES (?, ?, ?, ?, ?, ?)
                       ON DUPLICATE KEY UPDATE 
                       telepon = VALUES(telepon), 
                       tanggal_lahir = VALUES(tanggal_lahir), 
                       jenis_kelamin = VALUES(jenis_kelamin),
                       agama = VALUES(agama),
                       alamat = VALUES(alamat)";
                       
    $stmt_profiles = $conn->prepare($query_profiles);
    $stmt_profiles->bind_param("isssss", $user_id, $telepon, $tanggal_lahir, $jenis_kelamin, $agama, $alamat);

    if (!$stmt_profiles->execute()) {
        throw new Exception("Gagal memperbarui detail profil.");
    }
    $stmt_profiles->close();

    $conn->commit();

    header("Location: profile.php?status=update_sukses");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    
    header("Location: profile.php?error=" . urlencode($e->getMessage()));
    exit();
}

?>