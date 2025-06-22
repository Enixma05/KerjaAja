<?php
include '../auth/koneksi.php';    

$email = $_POST['email'];
$name = $_POST['name'];
$role = $_POST['role'];
$password = $_POST['password'];


$check = "SELECT * FROM users WHERE email='$email'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('Email atau Username sudah terdaftar!'); window.location.href='../auth/register.php';</script>";
} else {

    $insert = "INSERT INTO users (email, name, role, password) VALUES ('$email', '$name', 'perusahaan', '$password')";
    if (mysqli_query($conn, $insert)) {
        echo "<script>alert('Registration successful!'); window.location.href='../auth/login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='../auth/register.php';</script>";
    }
}
?>