<?php
session_start(); // Pastikan session dimulai
include '../auth/koneksi.php';

// Cek apakah user adalah perusahaan yang login
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'perusahaan') {
    // Redirect jika bukan perusahaan atau tidak login
    header("Location: ../auth/login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dan lakukan casting tipe untuk keamanan
    $lamaran_id = (int)$_POST['id'];
    
    // Gunakan mysqli_real_escape_string untuk data string
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    // Query untuk update status lamaran
    $query_update = "UPDATE lamaran SET status = '$status' WHERE id = $lamaran_id";

    if (mysqli_query($conn, $query_update)) {
        // Jika update berhasil, lanjutkan membuat notifikasi

        // 1. Ambil data yang diperlukan untuk notifikasi (user_id pelamar & judul lowongan)
        $query_info = "SELECT l.user_id, lwn.judul 
                       FROM lamaran l 
                       JOIN lowongan lwn ON l.lowongan_id = lwn.lowongan_id 
                       WHERE l.id = $lamaran_id";
        
        $result_info = mysqli_query($conn, $query_info);
        if ($data = mysqli_fetch_assoc($result_info)) {
            $pelamar_user_id = (int)$data['user_id'];
            $judul_lowongan = mysqli_real_escape_string($conn, $data['judul']);

            // 2. Buat pesan notifikasi
            $pesan = "";
            if ($status === 'Diterima') {
                $pesan = "Selamat! Lamaran Anda untuk posisi '{$judul_lowongan}' telah Diterima. Silakan tunggu informasi selanjutnya dari perusahaan.";
            } else {
                $pesan = "Mohon maaf, setelah melalui pertimbangan, lamaran Anda untuk posisi '{$judul_lowongan}' dinyatakan Belum Lolos. Tetap semangat!";
            }
            $pesan_escaped = mysqli_real_escape_string($conn, $pesan);

            // 3. Masukkan notifikasi ke database
            $query_insert_notif = "INSERT INTO notifikasi (user_id, pesan) VALUES ($pelamar_user_id, '$pesan_escaped')";
            mysqli_query($conn, $query_insert_notif);
        }
        
        // Redirect kembali ke halaman pelamar
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