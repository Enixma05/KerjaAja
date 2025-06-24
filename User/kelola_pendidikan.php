<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$pendidikan_list = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM pendidikan WHERE user_id = '$user_id' ORDER BY tahun_lulus DESC"), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Pendidikan</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body>

        <main class="main-content">
            <div class="profile-content">
                <div class="profile-tabs">
                <h1>Kelola Riwayat Pendidikan</h1>
                </div>

                <a href="profile.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Kembali ke Profil</a>

                <div id="personalTab" class="tab-pane active">
                    <form action="proses_pendidikan.php" method="POST" class="profile-form">
                        <div class="form-section">
                        <h3>Form Tambah Pendidikan</h3>
                        <input type="hidden" name="action" value="tambah">
                        <div class="form-group">
                            <label for="jenjang">Jenjang</label>
                            <input type="text" name="jenjang" id="jenjang" required>
                        </div>
                        <div class="form-group">
                            <label for="institusi">Nama Institusi</label>
                            <input type="text" name="institusi" id="institusi" required>
                        </div>
                        <div class="form-group">
                            <label for="jurusan">Jurusan</label>
                            <input type="text" name="jurusan" id="jurusan">
                        </div>
                        <div class="form-group">
                            <label for="tahun_lulus">Tahun Lulus</label>
                            <input type="number" name="tahun_lulus" id="tahun_lulus" required>
                        </div>
                        </div>  
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        
                    </form>
                </div>
            </div>
        <h2>Daftar Riwayat Pendidikan</h2>
        <?php if (count($pendidikan_list) > 0): ?>
            <ul class="education-list">
                <?php foreach ($pendidikan_list as $edu): ?>
                    <li>
                        <strong><?php echo htmlspecialchars($edu['jenjang']); ?></strong> di <?php echo htmlspecialchars($edu['institusi']); ?>,
                        Jurusan <?php echo htmlspecialchars($edu['jurusan']); ?> (<?php echo $edu['tahun_lulus']; ?>)
                        <form action="proses_pendidikan.php" method="POST" style="display:inline">
                            <input type="hidden" name="action" value="hapus">
                            <input type="hidden" name="id" value="<?php echo $edu['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus riwayat ini?')">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Belum ada data pendidikan.</p>
        <?php endif; ?>
     </main>
</body>

</html>
