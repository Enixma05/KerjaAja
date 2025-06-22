<?php
session_start();
include '../auth/koneksi.php';

// Pastikan user masih login dan memiliki akses admin
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

// Optional: Tambahkan pengecekan role admin jika ada
// if ($_SESSION['role'] !== 'admin') {
//     header("Location: ../index.php");
//     exit();
// }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    // Validasi input
    if (empty($name) || empty($email) || empty($password)) {
        $_SESSION['error_message'] = "Semua field harus diisi!";
        header("Location: admin-perusahaan.php");
        exit();
    }
    
    if (strlen($password) < 6) {
        $_SESSION['error_message'] = "Password minimal 6 karakter!";
        header("Location: admin-perusahaan.php");
        exit();
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error_message'] = "Format email tidak valid!";
        header("Location: admin-perusahaan.php");
        exit();
    }
    
    // Cek apakah email sudah terdaftar
    $check_query = "SELECT user_id FROM users WHERE email = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    
    if ($check_stmt) {
        mysqli_stmt_bind_param($check_stmt, "s", $email);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $_SESSION['error_message'] = "Email sudah terdaftar!";
            mysqli_stmt_close($check_stmt);
            mysqli_close($conn);
            header("Location: admin-perusahaan.php");
            exit();
        }
        mysqli_stmt_close($check_stmt);
    }
    
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert data perusahaan
    $insert_query = "INSERT INTO users (name, email, password, role, created_at) VALUES (?, ?, ?, 'perusahaan', NOW())";
    $insert_stmt = mysqli_prepare($conn, $insert_query);
    
    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, "sss", $name, $email, $hashed_password);
        
        if (mysqli_stmt_execute($insert_stmt)) {
            $_SESSION['success_message'] = "Akun perusahaan berhasil ditambahkan!";
            mysqli_stmt_close($insert_stmt);
            mysqli_close($conn);
            
            // Redirect kembali ke halaman admin-perusahaan.php
            header("Location: admin-perusahaan.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Gagal menambahkan akun perusahaan: " . mysqli_error($conn);
            mysqli_stmt_close($insert_stmt);
            mysqli_close($conn);
            header("Location: admin-perusahaan.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan pada database!";
        mysqli_close($conn);
        header("Location: admin-perusahaan.php");
        exit();
    }
} else {
    // Jika bukan POST request, redirect ke halaman admin
    header("Location: admin-perusahaan.php");
    exit();
}
?>