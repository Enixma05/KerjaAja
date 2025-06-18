<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = (int)$_SESSION['user_id'];

$query_pelatihan = "SELECT 
                        p.nama, p.lokasi, p.tanggal, p.deskripsi,
                        pp.tanggal_daftar
                    FROM pendaftaran_pelatihan pp
                    JOIN pelatihan p ON pp.pelatihan_id = p.pelatihan_id
                    WHERE pp.user_id = '$user_id'
                    ORDER BY p.tanggal DESC";
$result_pelatihan = mysqli_query($conn, $query_pelatihan);

$query_lamaran = "SELECT 
                    l.status, l.path_cv, l.tanggal_lamar,
                    lw.judul, lw.perusahaan, lw.lokasi
                FROM lamaran l
                JOIN lowongan lw ON l.lowongan_id = lw.lowongan_id
                WHERE l.user_id = '$user_id'
                ORDER BY l.tanggal_lamar DESC";
$result_lamaran = mysqli_query($conn, $query_lamaran);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Riwayat - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
    .history-item {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .history-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid #f1f1f1;
    }

    .history-item-header h3 {
        margin: 0;
        font-size: 1.2rem;
        color: #333;
    }

    .history-item-header p {
        margin: 5px 0 0;
        color: #6c757d;
        font-size: 0.9rem;
    }

    .history-item-description {
        color: #495057;
        margin: 0 0 1rem 0;
        font-size: 0.95rem;
    }

    .history-item-details {
        display: grid;
        grid-template-columns: 1fr;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    @media (min-width: 768px) {
        .history-item-details {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .detail-pair {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
    }

    .detail-pair span:first-child {
        color: #6c757d;
    }

    .detail-pair span:last-child {
        font-weight: 500;
        text-align: right;
    }

    .link-cv {
        text-decoration: none;
        color: rgb(255, 102, 0);
        font-weight: 500;
    }

    .link-cv:hover {
        text-decoration: underline;
    }
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo"><a href="dashboard.php">KerjaAja</a></div>
            <nav class="nav">
                <ul>
                    <li><a href="profile.php" class="btn-logout"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="../auth/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="riwayat.php" class="active"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Riwayat</h1>
                <p>Pantau riwayat pelatihan dan lamaran kerja Anda</p>
            </div>

            <div class="tabs">
                <div class="tab-header">
                    <button class="tab-btn active" data-tab="pelatihan">Pelatihan</button>
                    <button class="tab-btn" data-tab="lamaran">Lamaran Kerja</button>
                </div>
                <div class="tab-content">
                    <div class="tab-pane active" id="pelatihanTab">
                        <?php if (mysqli_num_rows($result_pelatihan) > 0):
                            mysqli_data_seek($result_pelatihan, 0); // Reset pointer
                            while ($pelatihan = mysqli_fetch_assoc($result_pelatihan)):
                                $statusText = "Menunggu";
                                $statusClass = "status-pending";
                                $today = new DateTime();
                                $trainingDate = new DateTime($pelatihan['tanggal']);
                                
                                if ($today->format('Y-m-d') > $trainingDate->format('Y-m-d')) {
                                    $statusText = "Selesai";
                                    $statusClass = "status-completed";
                                } elseif ($today->format('Y-m-d') == $trainingDate->format('Y-m-d')) {
                                    $statusText = "Sedang Berlangsung";
                                    $statusClass = "status-ongoing";
                                }
                        ?>
                        <div class="history-item">
                            <div class="history-item-header">
                                <div>
                                    <h3><?php echo htmlspecialchars($pelatihan['nama']); ?></h3>
                                    <p><?php echo date('d F Y', strtotime($pelatihan['tanggal'])); ?> •
                                        <?php echo htmlspecialchars($pelatihan['lokasi']); ?></p>
                                </div>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </div>
                            <p class="history-item-description"><?php echo htmlspecialchars($pelatihan['deskripsi']); ?>
                            </p>
                            <?php if ($statusText === 'Selesai'): ?>
                            <a href="#" class="btn btn-outline btn-sm">Lihat Sertifikat</a>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="no-results">
                            <p>Anda belum memiliki riwayat pendaftaran pelatihan.</p>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="tab-pane" id="lamaranTab">
                        <?php if (mysqli_num_rows($result_lamaran) > 0):
                            mysqli_data_seek($result_lamaran, 0); // Reset pointer
                            while ($lamaran = mysqli_fetch_assoc($result_lamaran)):
                                $statusText = "Menunggu";
                                $statusClass = "status-pending";
                                switch (strtolower($lamaran['status'])) {
                                    case "dilihat": $statusText = "Dilihat"; $statusClass = "status-ongoing"; break;
                                    case "diterima": $statusText = "Diterima"; $statusClass = "status-accepted"; break;
                                    case "ditolak": $statusText = "Ditolak"; $statusClass = "status-rejected"; break;
                                }
                        ?>
                        <div class="history-item">
                            <div class="history-item-header">
                                <div>
                                    <h3><?php echo htmlspecialchars($lamaran['judul']); ?></h3>
                                    <p><?php echo htmlspecialchars($lamaran['perusahaan']); ?> •
                                        <?php echo htmlspecialchars($lamaran['lokasi']); ?></p>
                                </div>
                                <span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                            </div>
                            <div class="history-item-details">
                                <div class="detail-pair">
                                    <span>Tanggal Lamaran:</span>
                                    <span><?php echo date('d F Y', strtotime($lamaran['tanggal_lamar'])); ?></span>
                                </div>
                                <div class="detail-pair">
                                    <span>CV:</span>
                                    <span><a href="<?php echo htmlspecialchars($lamaran['path_cv']); ?>" target="_blank"
                                            class="link-cv"><?php echo basename($lamaran['path_cv']); ?></a></span>
                                </div>
                            </div>
                            <?php if (strtolower($lamaran['status']) === 'diterima'): ?>
                            <a href="#" class="btn btn-primary btn-sm">Lihat Detail Penerimaan</a>
                            <?php endif; ?>
                        </div>
                        <?php endwhile; ?>
                        <?php else: ?>
                        <div class="no-results">
                            <p>Anda belum memiliki riwayat lamaran pekerjaan.</p>
                        </div>
                        <?php endif; ?>
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
    document.addEventListener("DOMContentLoaded", function() {
        const tabBtns = document.querySelectorAll(".tab-btn");
        const tabPanes = document.querySelectorAll(".tab-pane");

        tabBtns.forEach((btn) => {
            btn.addEventListener("click", function() {
                const tabName = this.getAttribute("data-tab");
                tabBtns.forEach((b) => b.classList.remove("active"));
                tabPanes.forEach((p) => p.classList.remove("active"));
                this.classList.add("active");
                document.getElementById(tabName + "Tab").classList.add("active");
            });
        });
    });
    </script>
</body>

</html>