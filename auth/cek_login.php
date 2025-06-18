<?php
session_start();
include ('../auth/koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $email = $_POST['email'];

    $cek_user = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user_valid = mysqli_fetch_array($cek_user);

    if ($user_valid) {
        if ($password == $user_valid['password']) {
            $_SESSION['user_id'] = $user_valid['user_id'];
            $_SESSION['name'] = $user_valid['name'];
            $_SESSION['role'] = $user_valid['role'];

            $user_id = $user_valid['id'];
            if ($user_id) {
                $query_profile = mysqli_query($conn, "SELECT * FROM profile WHERE id = $user_id");
                $profile = mysqli_fetch_array($query_profile);
                $_SESSION['profile'] = $profile;
            } else {
                $_SESSION['profile'] = null;
            }
            $role = $user_valid['role'];
            if ($role == 'admin') {
                header("location: ../admin/admin-dashboard.php");
            } else if ($role == 'perusahaan') {
                header("location: ../perusahaan/perusahaan-dashboard.php");
            } else {
                header("location: ../user/dashboard.php");
            }

            exit;
        }
    } else {
        echo "<script>alert('Email atau Password Salah!'); window.location='login.php';</script>";
    }
}
?>