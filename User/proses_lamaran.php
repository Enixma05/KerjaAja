<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id']) || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$lowongan_id = (int)$_POST['lowongan_id'];
$cover_letter = trim($_POST['coverLetter']);

if (empty($lowongan_id)) {
    die("Error: Lowongan tidak valid.");
}

$cv_path = '';
if (isset($_FILES['cv']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/cv/';
    $file_extension = pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION);
    $unique_filename = "cv_" . $user_id . "_" . $lowongan_id . "_" . time() . "." . $file_extension;
    $target_file = $upload_dir . $unique_filename;

    $file_type = $_FILES['cv']['type'];
    $file_size = $_FILES['cv']['size'];

    if ($file_type !== 'application/pdf') {
        die("Error: Hanya file PDF yang diizinkan.");
    }
    if ($file_size > 2 * 1024 * 1024) { 
        die("Error: Ukuran file CV tidak boleh lebih dari 2MB.");
    }
    
    if (move_uploaded_file($_FILES['cv']['tmp_name'], $target_file)) {
        $cv_path = $target_file;
    } else {
        die("Error: Gagal mengunggah file CV.");
    }
} else {
    die("Error: CV wajib diunggah.");
}

try {
    $stmt = $conn->prepare("INSERT INTO lamaran (lowongan_id, user_id, path_cv, surat_lamaran) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $lowongan_id, $user_id, $cv_path, $cover_letter);
    
    if ($stmt->execute()) {
        header("Location: riwayat.php?status=lamaran_sukses");
        exit();
    } else {
        throw new Exception("Gagal menyimpan data lamaran.");
    }

} catch (mysqli_sql_exception $e) {
    if ($e->getCode() == 1062) { 
        die("Anda sudah pernah melamar untuk lowongan ini.");
    } else {
        die("Terjadi kesalahan database: " . $e->getMessage());
    }
}

$stmt->close();
$conn->close();
?>