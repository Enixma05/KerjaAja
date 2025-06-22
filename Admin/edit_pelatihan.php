<?php
session_start();
include '../auth/koneksi.php';

// Cek sesi login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah ID pelatihan dikirim via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID pelatihan tidak ditemukan.";
    exit();
}

$pelatihan_id = $_GET['id'];

// Ambil data pelatihan dari database
$query = "SELECT * FROM pelatihan WHERE pelatihan_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $pelatihan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data pelatihan tidak ditemukan.";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Pelatihan</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="form-container">
        <h2>Edit Data Pelatihan</h2>
        <form action="update_pelatihan.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['pelatihan_id']) ?>">

            <div class="form-group">
                <label for="nama">Nama Pelatihan</label>
                <input type="text" id="nama" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal</label>
                <input type="date" id="tanggal" name="tanggal" value="<?= htmlspecialchars($data['tanggal']) ?>" required>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="kuota">Kuota</label>
                <input type="number" id="kuota" name="kuota" value="<?= htmlspecialchars($data['kuota']) ?>" required>
            </div>

            <div class="form-group">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
            </div>

            <div class="form-actions">
                <a href="admin-pelatihan.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>
</html>
