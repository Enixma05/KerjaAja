<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID lowongan tidak ditemukan.";
    exit();
}

$lowongan_id = $_GET['id'];

$query = "SELECT * FROM lowongan WHERE lowongan_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $lowongan_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Data lowongan tidak ditemukan.";
    exit();
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Edit Lowongan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Edit Data Lowongan</h2>
        <form action="update_lowongan.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['lowongan_id']) ?>">

            <div class="form-group">
                <label for="judul">Posisi</label>
                <input type="text" id="judul" name="judul" value="<?= htmlspecialchars($data['judul']) ?>" required>
            </div>

            <div class="form-group">
                <label for="perusahaan">Perusahaan</label>
                <input type="text" id="perusahaan" name="perusahaan"
                    value="<?= htmlspecialchars($data['perusahaan']) ?>" required>
            </div>

            <div class="form-group">
                <label for="jenis">Jenis</label>
                <input type="text" id="deskripsi" name="deskripsi" value="<?= htmlspecialchars($data['deskripsi']) ?>"
                    required>
            </div>

            <div class="form-group">
                <label for="lokasi">Lokasi</label>
                <input type="text" id="lokasi" name="lokasi" value="<?= htmlspecialchars($data['lokasi']) ?>" required>
            </div>

            <div class="form-group">
                <label for="bataslamaran">Batas Lamaran</label>
                <input type="date" id="batas_lamaran" name="batas_lamaran"
                    value="<?= htmlspecialchars($data['batas_lamaran']) ?>" required>
            </div>

            <div class="form-group">
                <label for="kualifikasi">Kualifikasi</label>
                <textarea id="kualifikasi" name="kualifikasi" rows="4"
                    required><?= htmlspecialchars($data['kualifikasi']) ?></textarea>
            </div>

            <div class="form-actions">
                <a href="perusahaan-lowongan.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>

</html>