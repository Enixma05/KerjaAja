<?php
session_start();
include '../auth/koneksi.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$user_id = (int)$_SESSION['user_id'];


$notifikasi = [];
$query_select = "SELECT * FROM notifikasi WHERE user_id = $user_id ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query_select);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifikasi[] = $row;
    }
}

$query_update = "UPDATE notifikasi SET is_read = 1 WHERE user_id = $user_id AND is_read = 0";
mysqli_query($conn, $query_update);

echo json_encode($notifikasi);

$conn->close();
?>