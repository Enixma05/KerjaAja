<?php
include 'koneksi.php';    

$email = $_POST['email'];
$name = $_POST['name'];
$password = $_POST['password'];


$check = "SELECT * FROM users WHERE email='$email' OR name='$name'";
$result = mysqli_query($conn, $check);

if (mysqli_num_rows($result) > 0) {
    echo "<script>alert('Email atau Username sudah terdaftar!'); window.location.href='register.php';</script>";
} else {

    $insert = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$password')";
    if (mysqli_query($conn, $insert)) {
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } else {
        echo "<script>alert('Error: " . mysqli_error($conn) . "'); window.location.href='register.php';</script>";
    }
}
?>