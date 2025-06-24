<?php session_start();?>
<?php
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$q1 = $conn->query("SELECT COUNT(*) as total_lowongan FROM lowongan WHERE created_by = $user_id");
$total_lowongan = $q1->fetch_assoc()['total_lowongan'];

$q2 = $conn->query("SELECT COUNT(*) as total_lamaran 
                    FROM lamaran l
                    JOIN lowongan lw ON l.lowongan_id = lw.lowongan_id
                    WHERE lw.created_by = $user_id");
$total_lamaran = $q2->fetch_assoc()['total_lamaran'];

$statusCounts = ['Diterima' => 0, 'Ditolak' => 0, 'Menunggu review' => 0];

$sql = "
    SELECT l.status, COUNT(*) AS jumlah
    FROM lamaran l
    JOIN lowongan lw ON l.lowongan_id = lw.lowongan_id
    WHERE lw.created_by = $user_id
    GROUP BY l.status";

$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (array_key_exists($status, $statusCounts)) {
        $statusCounts[$status] = (int)$row['jumlah'];
    }
}

$aktivitasTerbaru = null;
$queryAktivitas = "
    SELECT u.name AS nama_pelamar, lwn.judul AS posisi, lwn.perusahaan, l.tanggal_lamar
    FROM lamaran l
    JOIN users u ON l.user_id = u.user_id
    JOIN lowongan lwn ON l.lowongan_id = lwn.lowongan_id
    WHERE lwn.created_by = $user_id
    ORDER BY l.tanggal_lamar DESC
    LIMIT 1";

$resultAktivitas = $conn->query($queryAktivitas);

if ($row = $resultAktivitas->fetch_assoc()) {
    $aktivitasTerbaru = $row;
}

function waktuRelatif($timestamp) {
    $selisih = time() - strtotime($timestamp);
    if ($selisih < 60) return $selisih . ' detik yang lalu';
    if ($selisih < 3600) return floor($selisih / 60) . ' menit yang lalu';
    if ($selisih < 86400) return floor($selisih / 3600) . ' jam yang lalu';
    return date('d M Y, H:i', strtotime($timestamp));
}
?>


<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perusahaan Dashboard - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/notifications.css">
    <link rel="stylesheet" href="../css/admin-notifications.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                    <li><a href="../index.php" id="logoutBtn" class="btn-logout">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a></li>
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
                    <li><a href="perusahaan-dashboard.php" class="active"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="perusahaan-lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="perusahaan-pelamar.php"><i class="fas fa-users"></i> Data Pelamar</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Dashboard Perusahaan <span id="name"><?php echo htmlspecialchars($_SESSION['name']); ?></span></h1>
                <p>Pantau statistik dan data KerjaAja</p>
            </div>

            <div class="stats-grid">
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
                    <h3>Statistik Lamaran Kerja</h3>
                    <p>Status lamaran yang masuk</p>
                    <div class="chart-container" style="height:300px;">
                        <canvas id="lamaranStatusChart"></canvas>
                    </div>
                </div>
            </div>
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                const lamaranStatusCtx = document.getElementById('lamaranStatusChart').getContext('2d');

                const lamaranStatusChart = new Chart(lamaranStatusCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Diterima', 'Ditolak', 'Menunggu review'],
                        datasets: [{
                            label: 'Jumlah Lamaran',
                            data: <?= json_encode(array_values($statusCounts)); ?>,
                            backgroundColor: ['#10b981', '#ef4444', '#f59e0b'],
                            borderRadius: 6
                        }]
                    },
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
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            });
            </script>



            <div class="recent-activities">
                <h2>Aktivitas Terbaru</h2>
                <div class="activities-list">
                    <div class="activity-item">
                        <div class="activity-icon amber">
                            <i class="fas fa-briefcase"></i>
                        </div>
                        <?php if ($aktivitasTerbaru): ?>
                        <div class="activity-content">
                            <h4>Lamaran Kerja Baru</h4>
                            <p><?= htmlspecialchars($aktivitasTerbaru['nama_pelamar']) ?> melamar posisi
                                <?= htmlspecialchars($aktivitasTerbaru['posisi']) ?> di
                                <?= htmlspecialchars($aktivitasTerbaru['perusahaan']) ?></p>
                        </div>
                        <div class="activity-time"><?= waktuRelatif($aktivitasTerbaru['tanggal_lamar']) ?></div>
                        <?php else: ?>
                        <p style="padding: 10px;">Belum ada aktivitas terbaru.</p>
                        <?php endif; ?>
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
                window.location.href = 'logout.php';
            }
        });
    });
    </script>
</body>

</html>