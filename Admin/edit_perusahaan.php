<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "ID perusahaan tidak ditemukan.";
    exit();
}

$user_id = $_GET['id'];

$query = "SELECT * FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
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
    <title>Edit Perusahaan</title>
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <div class="form-container">
        <h2>Edit Data Perusahaan</h2>
        <form action="update_pelatihan.php" method="POST">
            <input type="hidden" name="id" value="<?= htmlspecialchars($data['user_id']) ?>">

            <div class="form-group">
                <label for="nama">Nama Perusahaan</label>
                <input type="text" id="name" name="name" value="<?= htmlspecialchars($data['name']) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= htmlspecialchars($data['email']) ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="text" id="password" name="password" value="<?= htmlspecialchars($data['password']) ?>"
                    required>
            </div>
            <div class="form-actions">
                <a href="admin-perusahaan.php" class="btn btn-outline">Batal</a>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</body>

</html>