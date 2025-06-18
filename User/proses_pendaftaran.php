<?php
session_start();
header('Content-Type: application/json');
include '../auth/koneksi.php';

$response = ['status' => 'error', 'message' => 'Terjadi kesalahan.'];

if (!isset($_SESSION['user_id'])) {
    $response['message'] = 'Sesi tidak valid. Silakan login terlebih dahulu.';
    echo json_encode($response);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);
if (!isset($input['pelatihan_id']) || !is_numeric($input['pelatihan_id'])) {
    $response['message'] = 'Permintaan tidak valid. ID Pelatihan tidak ada.';
    echo json_encode($response);
    exit();
}

$user_id = $_SESSION['user_id'];
$pelatihan_id = (int)$input['pelatihan_id'];

$conn->begin_transaction();

try {
    $stmt_check = $conn->prepare("SELECT id FROM pendaftaran_pelatihan WHERE user_id = ? AND pelatihan_id = ?");
    $stmt_check->bind_param("ii", $user_id, $pelatihan_id);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        throw new Exception('Anda sudah terdaftar pada pelatihan ini.');
    }
    $stmt_check->close();

    $stmt_quota = $conn->prepare("SELECT kuota FROM pelatihan WHERE pelatihan_id = ? FOR UPDATE");
    $stmt_quota->bind_param("i", $pelatihan_id);
    $stmt_quota->execute();
    $pelatihan = $stmt_quota->get_result()->fetch_assoc();
    
    if (!$pelatihan || $pelatihan['kuota'] <= 0) {
        throw new Exception('Maaf, kuota untuk pelatihan ini telah habis.');
    }
    $stmt_quota->close();

    $stmt_update = $conn->prepare("UPDATE pelatihan SET kuota = kuota - 1 WHERE pelatihan_id = ?");
    $stmt_update->bind_param("i", $pelatihan_id);
    $stmt_update->execute();
    $stmt_update->close();

    $stmt_insert = $conn->prepare("INSERT INTO pendaftaran_pelatihan (user_id, pelatihan_id, tanggal_daftar) VALUES (?, ?, NOW())");
    $stmt_insert->bind_param("ii", $user_id, $pelatihan_id);
    $stmt_insert->execute();
    $stmt_insert->close();

    $conn->commit();
    $response = ['status' => 'success', 'message' => 'Selamat, Anda berhasil terdaftar!'];

} catch (Exception $e) {
    $conn->rollback();
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
?>