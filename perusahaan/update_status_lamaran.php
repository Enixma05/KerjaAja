<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perusahaan') {
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lamaran_id = (int)$_POST['id'];
    
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $query_update = "UPDATE lamaran SET status = '$status' WHERE id = $lamaran_id";

    if (mysqli_query($conn, $query_update)) {

        $query_info = "SELECT l.user_id, lwn.judul 
                       FROM lamaran l 
                       JOIN lowongan lwn ON l.lowongan_id = lwn.lowongan_id 
                       WHERE l.id = $lamaran_id";
        
        $result_info = mysqli_query($conn, $query_info);
        if ($data = mysqli_fetch_assoc($result_info)) {
            $pelamar_user_id = (int)$data['user_id'];
            $judul_lowongan = mysqli_real_escape_string($conn, $data['judul']);

            $pesan = "";
            if ($status === 'Diterima') {
                $pesan = "Selamat! Lamaran Anda untuk posisi '{$judul_lowongan}' telah Diterima. Silakan tunggu informasi selanjutnya dari perusahaan.";
            } else {
                $pesan = "Mohon maaf, setelah melalui pertimbangan, lamaran Anda untuk posisi '{$judul_lowongan}' dinyatakan Belum Lolos. Tetap semangat!";
            }
            $pesan_escaped = mysqli_real_escape_string($conn, $pesan);

            $query_insert_notif = "INSERT INTO notifikasi (user_id, pesan) VALUES ($pelamar_user_id, '$pesan_escaped')";
            mysqli_query($conn, $query_insert_notif);
        }
        
        header("Location: perusahaan-pelamar.php?status=updated");
        exit();

    } else {
        echo "Gagal memperbarui status: " . mysqli_error($conn);
    }

    $conn->close();
} else {
    header("Location: perusahaan-pelamar.php");
    exit();
}
?>