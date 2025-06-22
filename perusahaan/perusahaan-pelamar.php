<?php session_start(); ?>
<?php
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$dataPelamar = [];
$queryPelamar = "SELECT l.*, u.name AS nama_user, u.email, lwn.judul AS nama_lowongan
                 FROM lamaran l
                 JOIN users u ON l.user_id = u.user_id
                 JOIN lowongan lwn ON l.lowongan_id = lwn.lowongan_id
                 WHERE lwn.created_by = $user_id
                 ORDER BY l.tanggal_lamar DESC";

$resultPelamar = mysqli_query($conn, $queryPelamar);

if ($resultPelamar && mysqli_num_rows($resultPelamar) > 0) {
    while ($row = mysqli_fetch_assoc($resultPelamar)) {
        $dataPelamar[] = $row;
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Pendaftar - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/notifications.css" />
    <link rel="stylesheet" href="../css/admin-notifications.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="perusahaan-dashboard.php">KerjaAja Perusahaan</a>
            </div>
            <nav class="nav">
                <ul>
                    <li>
                        <a href="../index.php" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="perusahaan-dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="perusahaan-lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="perusahaan-pelamar.php" class="active"><i class="fas fa-users"></i> Data Pelamar</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Data Pelamar</h1>
                <p>Kelola data lamaran kerja</p>
            </div>

            <div class="data-table-container">
                <table class="data-table" id="jobApplicantTable">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Posisi</th>
                            <th>Tanggal Lamar</th>
                            <th>CV</th>
                            <th>Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataPelamar) > 0): ?>
                            <?php foreach ($dataPelamar as $pelamar): ?>
                                <tr>
                                    <td><?= htmlspecialchars($pelamar['nama_user']) ?></td>
                                    <td><?= htmlspecialchars($pelamar['nama_lowongan']) ?></td>
                                    <td><?= date("d/m/Y", strtotime($pelamar['tanggal_lamar'])) ?></td>
                                    <td>
                                        <?php if (!empty($pelamar['path_cv'])): ?>
                                            <a href="<?= htmlspecialchars($pelamar['path_cv']) ?>" target="_blank">Lihat CV</a>
                                        <?php else: ?>
                                            Tidak ada
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($pelamar['status']) ?></td>
                                    <td class="text-right">
                                        <form action="update_status_lamaran.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $pelamar['id'] ?>">
                                            <input type="hidden" name="status" value="Diterima">
                                            <button type="submit" class="btn btn-success btn-sm">Terima</button>
                                        </form>
                                        <form action="update_status_lamaran.php" method="post" style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $pelamar['id'] ?>">
                                            <input type="hidden" name="status" value="Ditolak">
                                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" style="text-align:center;">Belum ada data pelamar.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 KerjaAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById("logoutBtn").addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = "../auth/logout.php";
        });
    </script>
</body>
</html>
