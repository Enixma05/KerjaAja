<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$jenjang = trim($_POST['jenjang']);
$nama_institusi = trim($_POST['nama_institusi']);
$jurusan = trim($_POST['jurusan']);
$tahun_lulus = (int)$_POST['tahun_lulus'];
$nilai = !empty($_POST['nilai']) ? (float)$_POST['nilai'] : NULL;

if (empty($jenjang) || empty($nama_institusi) || empty($tahun_lulus)) {
    header("Location: profile.php?error=data_pendidikan_kosong#educationTab");
    exit();
}

$query = "INSERT INTO pendidikan (user_id, jenjang, nama_institusi, jurusan, tahun_lulus, nilai) VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($query);

$stmt->bind_param("issssd", $user_id, $jenjang, $nama_institusi, $jurusan, $tahun_lulus, $nilai);

if ($stmt->execute()) {
    header("Location: profile.php?status=pendidikan_sukses#educationTab");
    exit();
} else {
    header("Location: profile.php?error=gagal_simpan_pendidikan#educationTab");
    exit();
}

$stmt->close();
$conn->close();
?>