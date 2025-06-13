<?php session_start();?>
<?php
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Total lowongan
$q1 = $conn->query("SELECT COUNT(*) as total_lowongan FROM lowongan");
$total_lowongan = $q1->fetch_assoc()['total_lowongan'];

// Total lamaran
$q2 = $conn->query("SELECT COUNT(*) as total_lamaran FROM lamaran");
$total_lamaran = $q2->fetch_assoc()['total_lamaran'];

// Total pelatihan
$q3 = $conn->query("SELECT COUNT(*) as total_pelatihan FROM pelatihan");
$total_pelatihan = $q3->fetch_assoc()['total_pelatihan'];

// Total peserta pelatihan
$q4 = $conn->query("SELECT COUNT(*) as total_peserta FROM pendaftaran_pelatihan");
$total_peserta = $q4->fetch_assoc()['total_peserta'];

$trainingCounts = array_fill_keys(['Jan','Feb','Mar','Apr','Mei','Jun','Jul'], 0);

$sql = "SELECT MONTH(tanggal) AS bulan, COUNT(*) AS jumlah FROM pelatihan GROUP BY bulan";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $monthIndex = (int)$row['bulan'];
    $monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun', 'Jul'];
    if ($monthIndex >= 1 && $monthIndex <= 7) {
        $trainingCounts[$monthNames[$monthIndex - 1]] = (int)$row['jumlah'];
    }
}

$statusCounts = ['Diterima' => 0, 'Ditolak' => 0, 'Menunggu review' => 0];

$sql = "SELECT status, COUNT(*) AS jumlah FROM lamaran GROUP BY status";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (array_key_exists($status, $statusCounts)) {
        $statusCounts[$status] = (int)$row['jumlah'];
    }
}

?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - KerjaAja</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/notifications.css">
    <link rel="stylesheet" href="css/admin-notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                                <a href="admin-notifications.php" id="viewAllAdminNotifications">Lihat Semua
                                    Notifikasi</a>
                            </div>
                        </div>
                    </li>
                    <!-- Send Notification Button -->
                    <li>
                        <button class="btn btn-primary" id="sendNotificationBtn">
                            <i class="fas fa-paper-plane"></i> Kirim Notifikasi
                        </button>
                    </li>
                    <li><a href="#" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Notification Toast Container -->
    <div id="toastContainer" class="toast-container"></div>

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

                <div class="form-group" id="specificUsersGroup" style="display: none;">
                    <label for="specificUsers">Email Pengguna (pisahkan dengan koma)</label>
                    <textarea id="specificUsers" rows="3" placeholder="user1@email.com, user2@email.com"></textarea>
                </div>

                <div class="form-group">
                    <label for="notificationTitle">Judul Notifikasi</label>
                    <input type="text" id="notificationTitle" required placeholder="Masukkan judul notifikasi">
                </div>

                <div class="form-group">
                    <label for="notificationMessage">Pesan</label>
                    <textarea id="notificationMessage" rows="4" required
                        placeholder="Masukkan pesan notifikasi"></textarea>
                </div>

                <div class="form-group">
                    <label for="notificationAction">Action URL (opsional)</label>
                    <input type="url" id="notificationAction" placeholder="https://example.com">
                </div>

                <div class="form-group">
                    <label for="scheduleNotification">
                        <input type="checkbox" id="scheduleNotification"> Jadwalkan Notifikasi
                    </label>
                </div>

                <div class="form-group" id="scheduleGroup" style="display: none;">
                    <label for="scheduleDateTime">Tanggal & Waktu</label>
                    <input type="datetime-local" id="scheduleDateTime">
                </div>

                <div class="modal-buttons">
                    <button type="button" id="cancelSendNotification" class="btn btn-outline">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Kirim Notifikasi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Dashboard Content -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="admin-lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="admin-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a></li>
                    <li><a href="admin-notifications.php"><i class="fas fa-bell"></i> Kelola Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Dashboard Admin</h1>
                <p>Pantau statistik dan data KerjaAja</p>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Total Pelatihan</h3>
                        <div class="stat-icon blue">
                            <i class="fas fa-book"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_pelatihan ?></div>
                    <div class="stat-description">Pelatihan aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Total Peserta</h3>
                        <div class="stat-icon green">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_peserta ?></div>
                    <div class="stat-description">Peserta terdaftar</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Total Lowongan</h3>
                        <div class="stat-icon amber">
                            <i class="fas fa-briefcase"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_lowongan ?></div>
                    <div class="stat-description">Lowongan aktif</div>
                </div>
                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Total Pelamar</h3>
                        <div class="stat-icon purple">
                            <i class="fas fa-user-check"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_lamaran ?></div>
                    <div class="stat-description">Pelamar kerja</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Pelatihan per Bulan</h3>
                    <p>Jumlah pelatihan yang diadakan per bulan</p>
                    <div class="chart-container">
                        <canvas id="trainingChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Status Lamaran</h3>
                    <p>Distribusi status lamaran kerja</p>
                    <div class="chart-container">
                        <canvas id="applicationChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Quick Notification Actions -->
            <section class="quick-notification-actions">
                <h2>Aksi Cepat Notifikasi</h2>
                <div class="quick-actions-grid">
                    <button class="quick-action-btn"
                        onclick="adminNotificationManager.sendQuickNotification('training_reminder')">
                        <i class="fas fa-graduation-cap"></i>
                        <span>Reminder Pelatihan</span>
                    </button>
                    <button class="quick-action-btn"
                        onclick="adminNotificationManager.sendQuickNotification('job_alert')">
                        <i class="fas fa-briefcase"></i>
                        <span>Alert Lowongan Baru</span>
                    </button>
                    <button class="quick-action-btn"
                        onclick="adminNotificationManager.sendQuickNotification('system_maintenance')">
                        <i class="fas fa-tools"></i>
                        <span>Maintenance System</span>
                    </button>
                    <button class="quick-action-btn"
                        onclick="adminNotificationManager.sendQuickNotification('announcement')">
                        <i class="fas fa-bullhorn"></i>
                        <span>Pengumuman</span>
                    </button>
                </div>
            </section>

            <div class="recent-activities">
                <h2>Aktivitas Terbaru</h2>
                <div class="activities-list">
                    <div class="activity-item">
                        <div class="activity-icon blue">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pendaftaran Pelatihan Baru</h4>
                            <p>Fadlullah Hasan mendaftar pelatihan Desain Grafis</p>
                        </div>
                        <div class="activity-time">5 menit yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon amber">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Lamaran Kerja Baru</h4>
                            <p>Ani Wijaya melamar posisi Customer Service Representative di PT Maju Bersama</p>
                        </div>
                        <div class="activity-time">30 menit yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon green">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Notifikasi Terkirim</h4>
                            <p>Notifikasi reminder pelatihan terkirim ke 45 peserta</p>
                        </div>
                        <div class="activity-time">1 jam yang lalu</div>
                    </div>
                    <div class="activity-item">
                        <div class="activity-icon blue">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="activity-content">
                            <h4>Pelatihan Selesai</h4>
                            <p>Pelatihan Digital Marketing telah selesai dengan 15 peserta</p>
                        </div>
                        <div class="activity-time">2 jam yang lalu</div>
                    </div>
                </div>
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

    <!-- <script src="js/data.js"></script> -->
    <script src="js/main.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/admin-notifications.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup charts
        const trainingCtx = document.getElementById('trainingChart').getContext('2d');
        const applicationCtx = document.getElementById('applicationChart').getContext('2d');

        const trainingData = {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul'],
            datasets: [{
                label: 'Jumlah Pelatihan',
                data: <?= json_encode(array_values($trainingCounts)); ?>,
                backgroundColor: '#3b82f6',
                borderRadius: 4
            }]
        };

        const applicationData = {
            labels: ['Diterima', 'Ditolak', 'Menunggu'],
            datasets: [{
                label: 'Status Lamaran',
                data: <?= json_encode(array_values($statusCounts)); ?>,
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                borderWidth: 0
            }]
        };

        // Create training chart
        new Chart(trainingCtx, {
            type: 'bar',
            data: trainingData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });

        // Create application status chart
        new Chart(applicationCtx, {
            type: 'pie',
            data: applicationData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Setup logout button
        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = 'logout.php';
            }
        });
    });
    </script>
</body>

</html>