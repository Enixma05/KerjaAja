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

            $user_id = $user_valid['id'];
            if ($user_id) {
                $query_profile = mysqli_query($conn, "SELECT * FROM profile WHERE id = $user_id");
                $profile = mysqli_fetch_array($query_profile);
                $_SESSION['profile'] = $profile;
            } else {
                $_SESSION['profile'] = null;
            }

            if ($email == "admin@gmail.com" && $password== "admin") {
                header("location: ../admin/admin-dashboard.php");
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