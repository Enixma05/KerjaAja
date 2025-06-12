<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Kelola Notifikasi - Admin KerjaAja</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/notifications.css" />
    <link rel="stylesheet" href="css/notifications-page.css" />
    <link rel="stylesheet" href="css/admin-notifications.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="admin-dashboard.php">KerjaAja Admin</a>
            </div>
            <nav class="nav">
                <ul>
                    <!-- Admin Notifications -->
                    <li class="notification-dropdown">
                        <div class="notification-menu" id="adminNotificationMenu">
                            <div class="notification-header">
                                <h4>Notifikasi Admin</h4>
                                <button class="mark-all-read" id="adminMarkAllRead">
                                    <i class="fas fa-check-double"></i>
                                    Tandai Semua Dibaca
                                </button>
                            </div>
                            <div class="notification-list" id="adminNotificationList">
                                <!-- Admin notifications will be populated by JavaScript -->
                            </div>
                            <div class="notification-footer">
                                <a href="admin-notifications.html" id="viewAllAdminNotifications">Lihat Semua
                                    Notifikasi</a>
                            </div>
                        </div>
                    </li>

                    <li>
                        <button class="btn btn-primary" id="sendNotificationBtn"><i class="fas fa-paper-plane"></i>
                            Kirim Notifikasi</button>
                    </li>

                    <li>
                        <a href="logout.php" id="logoutBtn" class="btn-logout"> <i class="fas fa-sign-out-alt"></i>
                            Logout </a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Notification Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li>
                        <a href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a>
                    </li>
                    <li>
                        <a href="admin-lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a>
                    </li>
                    <li>
                        <a href="admin-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a>
                    </li>
                    <li>
                        <a href="admin-notifications.php" class="active"><i class="fas fa-bell"></i> Kelola
                            Notifikasi</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <div class="page-header">
                <div class="header-content">
                    <div class="header-text">
                        <h1>Kelola Notifikasi</h1>
                        <p>Kelola dan kirim notifikasi ke pengguna platform</p>
                    </div>
                </div>
            </div>

            <!-- Notification History -->
            <section class="notification-filters">
                <h2>Riwayat Notifikasi</h2>
                <div class="section-header">
                    <div class="filter-actions">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" id="searchNotifications" placeholder="Cari notifikasi..." />
                        </div>
                        <select id="filterType" class="sort-select">
                            <option value="">Semua Jenis</option>
                            <option value="info">Informasi</option>
                            <option value="success">Berhasil</option>
                            <option value="warning">Peringatan</option>
                            <option value="error">Error</option>
                        </select>
                        <select id="sortBy" class="sort-select">
                            <option value="newest">Terbaru</option>
                            <option value="oldest">Terlama</option>
                            <option value="type">Jenis</option>
                        </select>
                    </div>
                </div>

                <div class="notification-list-container">
                    <div class="notification-actions">
                        <button class="btn btn-outline" id="markAllReadHistory"><i class="fas fa-check-double"></i>
                            Tandai Semua Dibaca</button>
                        <button class="btn btn-outline" id="clearAllNotifications"><i class="fas fa-trash"></i> Hapus
                            Semua</button>
                    </div>

                    <div class="notifications-list" id="notificationHistoryList">
                        <!-- Notification history will be populated by JavaScript -->
                    </div>

                    <!-- Pagination -->
                    <div class="pagination" id="notificationPagination">
                        <!-- Pagination will be populated by JavaScript -->
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Send Notification Modal -->
    <div id="sendNotificationModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" id="closeSendModal">&times;</span>
            <h2>Kirim Notifikasi</h2>
            <p>Kirim notifikasi ke pengguna platform</p>

            <form id="sendNotificationForm">
                <div class="form-group">
                    <label for="notificationType">Jenis Notifikasi</label>
                    <select id="notificationType" required>
                        <option value="">Pilih Jenis</option>
                        <option value="info">Informasi</option>
                        <option value="success">Berhasil</option>
                        <option value="warning">Peringatan</option>
                        <option value="error">Error</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notificationTarget">Target Penerima</label>
                    <select id="notificationTarget" required>
                        <option value="">Pilih Target</option>
                        <option value="all">Semua Pengguna</option>
                        <option value="active">Pengguna Aktif</option>
                        <option value="training">Peserta Pelatihan</option>
                        <option value="job_seekers">Pencari Kerja</option>
                        <option value="specific">Pengguna Tertentu</option>
                    </select>
                </div>

                <div class="form-group" id="specificUsersGroup" style="display: none">
                    <label for="specificUsers">Email Pengguna (pisahkan dengan koma)</label>
                    <textarea id="specificUsers" rows="3" placeholder="user1@email.com, user2@email.com"></textarea>
                </div>

                <div class="form-group">
                    <label for="notificationTitle">Judul Notifikasi</label>
                    <input type="text" id="notificationTitle" required placeholder="Masukkan judul notifikasi" />
                </div>

                <div class="form-group">
                    <label for="notificationMessage">Pesan</label>
                    <textarea id="notificationMessage" rows="4" required
                        placeholder="Masukkan pesan notifikasi"></textarea>
                </div>

                <div class="form-group">
                    <label for="notificationAction">Action URL (opsional)</label>
                    <input type="url" id="notificationAction" placeholder="https://example.com" />
                </div>

                <div class="form-group">
                    <label for="scheduleNotification"> <input type="checkbox" id="scheduleNotification" /> Jadwalkan
                        Notifikasi </label>
                </div>

                <div class="form-group" id="scheduleGroup" style="display: none">
                    <label for="scheduleDateTime">Tanggal & Waktu</label>
                    <input type="datetime-local" id="scheduleDateTime" />
                </div>

                <div class="modal-buttons">
                    <button type="button" id="cancelSendNotification" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane"></i> Kirim
                        Notifikasi</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 KerjaAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
    <script src="js/admin-notifications-page.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Setup logout button
        document.getElementById("logoutBtn").addEventListener("click", function(e) {
            e.preventDefault();
            if (confirm("Apakah Anda yakin ingin logout?")) {
                window.location.href = "login.php";
            }
        });
    });
    </script>
</body>

</html>