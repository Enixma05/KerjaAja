<?php session_start();?>
<?php
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ..login/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/notifications.css">
    <link rel="stylesheet" href="../css/notifications-page.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="dashboard.php">KerjaAja</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="profile.php" class="btn-logout">
                            <i class="fas fa-user"></i> Profile
                        </a></li>
                    <li><a href="../login/logout.php" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div id="toastContainer" class="toast-container"></div>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="notifications.php" class="active"><i class="fas fa-bell"></i> Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <div class="page-title">
                    <h1>Notifikasi</h1>
                    <p>Kelola semua notifikasi dan pengingat Anda</p>
                </div>
            </div>

            <div class="notification-filters">
                <div class="filter-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchNotifications" placeholder="Cari notifikasi...">
                    </div>
                </div>
            </div>

            <div class="notifications-container">
                <div class="notifications-list" id="notificationsList">
                </div>

                <div class="empty-state" id="emptyState" style="display: none;">
                    <div class="empty-icon">
                        <i class="fas fa-bell-slash"></i>
                    </div>
                    <h3>Tidak Ada Notifikasi</h3>
                    <p>Anda belum memiliki notifikasi yang sesuai dengan filter yang dipilih.</p>
                </div>

                <div class="loading-state" id="loadingState" style="display: none;">
                    <div class="loading-spinner">
                        <i class="fas fa-spinner fa-spin"></i>
                    </div>
                    <p>Memuat notifikasi...</p>
                </div>
            </div>

            <div class="pagination-container" id="paginationContainer">
                <div class="pagination-info">
                    <span id="paginationInfo">Menampilkan 1-10 dari 25 notifikasi</span>
                </div>
                <div class="pagination" id="pagination">
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
    <script src="js/main.js"></script>
    <script src="js/notifications-page.js"></script>
</body>

</html>