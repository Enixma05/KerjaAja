<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$q3 = $conn->query("SELECT COUNT(*) as total_pelatihan FROM pelatihan");
$total_pelatihan = $q3->fetch_assoc()['total_pelatihan'];

$q4 = $conn->query("SELECT COUNT(*) as total_peserta FROM pendaftaran_pelatihan");
$total_peserta = $q4->fetch_assoc()['total_peserta'];

$monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des'];
$trainingCounts = array_fill_keys($monthNames, 0);
$participantCounts = array_fill_keys($monthNames, 0);

$sql_pelatihan = "SELECT MONTH(tanggal) AS bulan, COUNT(*) AS jumlah FROM pelatihan WHERE YEAR(tanggal) = YEAR(CURDATE()) GROUP BY bulan";
$result_pelatihan = $conn->query($sql_pelatihan);
while ($row = $result_pelatihan->fetch_assoc()) {
    $monthIndex = (int)$row['bulan'];
    if ($monthIndex >= 1 && $monthIndex <= 12) {
        $trainingCounts[$monthNames[$monthIndex - 1]] = (int)$row['jumlah'];
    }
}

$sql_peserta = "SELECT MONTH(p.tanggal) AS bulan, COUNT(*) AS jumlah_peserta 
                FROM pelatihan p
                JOIN pendaftaran_pelatihan pp ON p.pelatihan_id = pp.pelatihan_id
                WHERE YEAR(p.tanggal) = YEAR(CURDATE())
                GROUP BY MONTH(p.tanggal)";
$result_peserta = $conn->query($sql_peserta);

while ($row = $result_peserta->fetch_assoc()) {
    $monthIndex = (int)$row['bulan'];
    if ($monthIndex >= 1 && $monthIndex <= 12) {
        $participantCounts[$monthNames[$monthIndex - 1]] = (int)$row['jumlah_peserta'];
    }
}

$statusCounts = ['Diterima' => 0, 'Ditolak' => 0, 'Menunggu review' => 0];
$sql_status = "SELECT status, COUNT(*) AS jumlah FROM lamaran GROUP BY status";
$result_status = $conn->query($sql_status);
while ($row = $result_status->fetch_assoc()) {
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
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/notifications.css">
    <link rel="stylesheet" href="../css/admin-notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="admin-dashboard.php">KerjaAja Admin</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="../auth/logout.php" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="admin-perusahaan.php"><i class="fas fa-building"></i> Perusahaan</a></li>
                    <li><a href="admin-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a></li>
                </ul>
            </nav>
        </aside>

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
                        <div class="stat-icon orange">
                            <i class="fas fa-users"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= $total_peserta ?></div>
                    <div class="stat-description">Peserta terdaftar</div>
                </div>
            </div>

            <div class="charts-grid">
                <div class="chart-card">
                    <h3>Statistik Pelatihan & Peserta per Bulan</h3>
                    <p>Perbandingan jumlah pelatihan dengan pendaftar setiap bulan</p>
                    <div class="chart-container">
                        <canvas id="trainingChart"></canvas>
                    </div>
                </div>
                <div class="chart-card">
                    <h3>Status Lamaran Kerja</h3>
                    <p>Distribusi status lamaran kerja yang masuk</p>
                    <div class="chart-container">
                        <canvas id="applicationChart"></canvas>
                    </div>
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

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const trainingCtx = document.getElementById('trainingChart').getContext('2d');
        const trainingData = {
            labels: <?= json_encode($monthNames); ?>,
            datasets: [{
                label: 'Jumlah Pelatihan',
                data: <?= json_encode(array_values($trainingCounts)); ?>,
                backgroundColor: '#3b82f6',
                borderColor: '#3b82f6',
                type: 'bar',
                order: 2,
                borderRadius: 4
            }, {
                label: 'Jumlah Peserta',
                data: <?= json_encode(array_values($participantCounts)); ?>,
                borderColor: '#f97316',
                backgroundColor: '#f97316',
                tension: 0.3,
                type: 'line',
                order: 1
            }]
        };

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
                },
                plugins: {
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    },
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        const applicationCtx = document.getElementById('applicationChart').getContext('2d');
        const applicationData = {
            labels: ['Diterima', 'Ditolak', 'Menunggu'],
            datasets: [{
                label: 'Status Lamaran',
                data: <?= json_encode(array_values($statusCounts)); ?>,
                backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                borderWidth: 0
            }]
        };

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

        document.getElementById('logoutBtn').addEventListener('click', function(e) {
            e.preventDefault();
            if (confirm('Apakah Anda yakin ingin logout?')) {
                window.location.href = '../auth/logout.php';
            }
        });
    });
    </script>
</body>

</html>