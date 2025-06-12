<?php session_start();?>
<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];


$query = "SELECT * FROM pelatihan limit 3 ";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard - KerjaAja</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="dashboard.php">KerjaAja</a>
            </div>
            <nav class="nav">
                <ul>
                    <li>
                        <a href="profile.php" id="logoutBtn" class="btn-logout"> <i class="fas fa-user"></i> Profile
                        </a>
                    </li>
                    <li>
                        <a href="logout.php" id="logoutBtn" class="btn-logout"> <i class="fas fa-sign-out-alt"></i>
                            Logout </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a>
                    </li>
                    <li>
                        <a href="lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a>
                    </li>
                    <li>
                        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
                    </li>
                    <li>
                        <a href="notifications.php"><i class="fas fa-bell"></i> Notifikasi</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="dashboard-welcome">
                <h1>Selamat Datang, <span id="name"><?php echo htmlspecialchars($_SESSION['name']); ?></span></h1>
                <p>Akses semua fitur KerjaAja dari dashboard Anda</p>
            </div>

            <div class="dashboard-cards">
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Pelatihan</h3>
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="card-body">
                        <p>Akses berbagai pelatihan kerja untuk meningkatkan keterampilan Anda</p>
                        <a href="pelatihan.php" class="btn btn-primary btn-block">Lihat Pelatihan</a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Lowongan Kerja</h3>
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-body">
                        <p>Temukan dan lamar pekerjaan dari perusahaan lokal</p>
                        <a href="lowongan.php" class="btn btn-primary btn-block">Lihat Lowongan</a>
                    </div>
                </div>

                <div class="dashboard-card">
                    <div class="card-header">
                        <h3>Riwayat</h3>
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="card-body">
                        <p>Pantau riwayat pelatihan dan lamaran kerja Anda</p>
                        <a href="riwayat.php" class="btn btn-primary btn-block">Lihat Riwayat</a>
                    </div>
                </div>
            </div>

            <div class="recent-trainings">
                <h2>Pelatihan Terbaru</h2>
                <div class="training-list" id="recentTrainings">

                    <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $nama = htmlspecialchars($row['nama']);
                $kuota = htmlspecialchars($row['kuota']);
                $lokasi = htmlspecialchars($row['lokasi']);
                
                $deskripsi_singkat = htmlspecialchars(substr($row['deskripsi'], 0, 1000)) ;
                
                $tanggal_formatted = date('d F Y', strtotime($row['tanggal']));
        ?>

                    <div class="training-item">
                        <div class="training-header">
                            <h3><?php echo $nama; ?></h3>
                            <span class="badge"><?php echo $kuota; ?> Kuota</span>
                        </div>
                        <div class="training-details">
                            <p><i class="fas fa-calendar"></i> <?php echo $tanggal_formatted; ?> • <i
                                    class="fas fa-map-marker-alt"></i> <?php echo $lokasi; ?></p>
                        </div>
                        <p class="training-description"><?php echo $deskripsi_singkat; ?></p>
                    </div>

                    <?php
            } 
        } else {

            echo "<p>Belum ada pelatihan terbaru yang tersedia.</p>";
        }
        ?>

                </div>
            </div>
        </main>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 KerjaAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="js/data.js"></script>
    <script src="js/main.js"></script>
</body>

</html>