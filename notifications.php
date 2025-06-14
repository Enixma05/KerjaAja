<?php session_start();?>
<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
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
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/notifications-page.css">
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
                    <li class="notification-dropdown">
                        <div class="notification-menu" id="notificationMenu">
                            <div class="notification-header">
                                <h4>Notifikasi</h4>
                                <button class="mark-all-read" id="markAllRead">
                                    <i class="fas fa-check-double"></i>
                                    Tandai Semua Dibaca
                                </button>
                            </div>
                            <div class="notification-list" id="notificationList">
                            </div>
                            <div class="notification-footer">
                                <a href="notifications.php" id="viewAllNotifications">Lihat Semua Notifikasi</a>
                            </div>
                        </div>
                    </li>

                    <li><a href="profile.php" class="btn-logout">
                            <i class="fas fa-user"></i> Profile
                        </a></li>
                    <li><a href="logout.php" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div id="toastContainer" class="toast-container"></div>

    <div class="dashboard-container">
        <!-- Sidebar -->
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
                <div class="page-actions">
                    <button class="btn btn-outline" id="markAllReadBtn">
                        <i class="fas fa-check-double"></i>
                        Tandai Semua Dibaca
                    </button>
                    <button class="btn btn-outline" id="clearAllBtn">
                        <i class="fas fa-trash"></i>
                        Hapus Semua
                    </button>
                </div>
            </div>

            <div class="notification-stats">
                <div class="stat-card">
                    <div class="stat-icon unread">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="unreadCount">0</h3>
                        <p>Belum Dibaca</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon total">
                        <i class="fas fa-bell"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="totalCount">0</h3>
                        <p>Total Notifikasi</p>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon today">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div class="stat-info">
                        <h3 id="todayCount">0</h3>
                        <p>Hari Ini</p>
                    </div>
                </div>
            </div>

            <div class="notification-filters">
                <div class="filter-tabs">
                    <button class="filter-tab active" data-filter="all">
                        <i class="fas fa-list"></i>
                        Semua
                    </button>
                    <button class="filter-tab" data-filter="unread">
                        <i class="fas fa-envelope"></i>
                        Belum Dibaca
                    </button>
                    <button class="filter-tab" data-filter="success">
                        <i class="fas fa-check-circle"></i>
                        Berhasil
                    </button>
                    <button class="filter-tab" data-filter="info">
                        <i class="fas fa-info-circle"></i>
                        Informasi
                    </button>
                    <button class="filter-tab" data-filter="warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        Peringatan
                    </button>
                    <button class="filter-tab" data-filter="error">
                        <i class="fas fa-times-circle"></i>
                        Error
                    </button>
                </div>
                <div class="filter-actions">
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" id="searchNotifications" placeholder="Cari notifikasi...">
                    </div>
                    <select id="sortNotifications" class="sort-select">
                        <option value="newest">Terbaru</option>
                        <option value="oldest">Terlama</option>
                        <option value="unread">Belum Dibaca</option>
                        <option value="type">Jenis</option>
                    </select>
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

    <div class="modal-overlay" id="settingsModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-cog"></i> Pengaturan Notifikasi</h3>
                <button class="modal-close" id="closeSettingsModal">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="settings-section">
                    <h4>Jenis Notifikasi</h4>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableJobNotifications" checked>
                            <span class="checkmark"></span>
                            Notifikasi Lamaran Kerja
                        </label>
                        <p class="setting-description">Terima notifikasi tentang status lamaran kerja Anda</p>
                    </div>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableTrainingNotifications" checked>
                            <span class="checkmark"></span>
                            Notifikasi Pelatihan
                        </label>
                        <p class="setting-description">Terima pengingat tentang pelatihan dan jadwal</p>
                    </div>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableSystemNotifications" checked>
                            <span class="checkmark"></span>
                            Notifikasi Sistem
                        </label>
                        <p class="setting-description">Terima notifikasi tentang update sistem dan maintenance</p>
                    </div>
                </div>

                <div class="settings-section">
                    <h4>Metode Notifikasi</h4>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableBrowserNotifications" checked>
                            <span class="checkmark"></span>
                            Notifikasi Browser
                        </label>
                        <p class="setting-description">Tampilkan notifikasi di browser</p>
                    </div>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableEmailNotifications">
                            <span class="checkmark"></span>
                            Notifikasi Email
                        </label>
                        <p class="setting-description">Kirim notifikasi ke email Anda</p>
                    </div>
                    <div class="setting-item">
                        <label class="setting-label">
                            <input type="checkbox" id="enableSoundNotifications" checked>
                            <span class="checkmark"></span>
                            Suara Notifikasi
                        </label>
                        <p class="setting-description">Putar suara saat menerima notifikasi</p>
                    </div>
                </div>

                <div class="settings-section">
                    <h4>Frekuensi</h4>
                    <div class="setting-item">
                        <label for="notificationFrequency">Periksa Notifikasi Baru</label>
                        <select id="notificationFrequency" class="setting-select">
                            <option value="realtime">Real-time</option>
                            <option value="5min">Setiap 5 menit</option>
                            <option value="15min">Setiap 15 menit</option>
                            <option value="30min" selected>Setiap 30 menit</option>
                            <option value="1hour">Setiap 1 jam</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-outline" id="cancelSettings">Batal</button>
                <button class="btn btn-primary" id="saveSettings">Simpan Pengaturan</button>
            </div>
        </div>
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