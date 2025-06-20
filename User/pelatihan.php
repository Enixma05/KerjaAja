<?php session_start();?>
<?php
include '../auth/koneksi.php';

// Cek sesi login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Query SQL dasar
$query_sql = "SELECT * FROM pelatihan";
$search_term = ''; 

if (isset($_GET['search']) && !empty(trim($_GET['search']))) {

    $search_term = trim($_GET['search']);
    
    $query_sql .= " WHERE nama LIKE ? OR deskripsi LIKE ?";
}

$query_sql .= " ORDER BY tanggal DESC";

$stmt = $conn->prepare($query_sql);

if (!empty($search_term)) {
    $search_like = "%" . $search_term . "%";
    $stmt->bind_param("ss", $search_like, $search_like);
}

$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelatihan - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .search-form {
        display: flex;
        gap: 10px;
    }
        
    .training-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    }

    .training-item {
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        height: 100%;
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    .register-btn {
        align-self: stretch;
        margin-top: auto;
        padding: 10px;
    }

    .register-btn:disabled {
        background-color: #28a745;
        border-color: #28a745;
        cursor: not-allowed;
        opacity: 0.8;
    }


        
    </style>
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="dashboard.php">KerjaAja</a>
            </div>
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
                    <li><a href="pelatihan.php" class="active"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="lowongan.php"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Daftar Pelatihan</h1>
                <p>Temukan dan daftar pelatihan yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="search-container">
                <form action="pelatihan.php" method="GET" class="search-form">
                    <div class="search-input">
                        <i class="fas fa-search"></i>
                        <input type="text" name="search" placeholder="Cari pelatihan..."
                            value="<?php echo htmlspecialchars($search_term); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
            </div>

            <div class="training-grid" id="trainingGrid">
                <?php
                if ($result->num_rows > 0):
                    while ($row = $result->fetch_assoc()):
                        $nama = htmlspecialchars($row['nama']);
                        $kuota = htmlspecialchars($row['kuota']);
                        $lokasi = htmlspecialchars($row['lokasi']);
                        $deskripsi_singkat = htmlspecialchars(substr($row['deskripsi'], 0, 200)) . '...';
                        $tanggal_formatted = date('d F Y', strtotime($row['tanggal']));
                ?>
                <div class="training-item">
                    <div class="training-image"><i class="fas fa-book-open"></i></div>
                    <div class="training-content">
                        <h3><?php echo $nama; ?></h3>
                    </div>
                    <div class="training-details">
                        <p><i class="fas fa-users"></i><span class="kuota-display"><?php echo $kuota; ?></span> Kuota
                        </p>
                        <p><i class="fas fa-calendar"></i> <?php echo $tanggal_formatted; ?></p>
                        <p><i class="fas fa-map-marker-alt"></i> <?php echo $lokasi; ?></p>
                    </div>
                    <p class="training-description"><?php echo $deskripsi_singkat; ?></p>
                    <button class="btn btn-primary btn-block register-btn"
                        data-id="<?php echo $row['pelatihan_id']; ?>">Daftar</button>
                </div>
                <?php
                    endwhile; 
                else:
                    if (!empty($search_term)) {
                        echo "<p>Tidak ada pelatihan yang cocok dengan kata kunci '<strong>" . htmlspecialchars($search_term) . "</strong>'. Coba cari dengan kata kunci lain atau <a href='pelatihan.php'>lihat semua pelatihan</a>.</p>";
                    } else {
                        echo "<p>Belum ada pelatihan yang tersedia saat ini.</p>";
                    }
                endif;
                $stmt->close();
                $conn->close();
                ?>
            </div>
        </main>
    </div>

    <footer class="footer">
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const daftarButtons = document.querySelectorAll('.register-btn');
        daftarButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (confirm('Apakah Anda yakin ingin mendaftar untuk pelatihan ini?')) {
                    const pelatihanId = this.dataset.id;
                    const thisButton = this;
                    fetch('proses_pendaftaran.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({
                                pelatihan_id: pelatihanId
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            alert(data.message);
                            if (data.status === 'success') {
                                thisButton.textContent = 'Terdaftar';
                                thisButton.disabled = true;
                                const parentItem = thisButton.closest('.training-item');
                                const kuotaSpan = parentItem.querySelector(
                                    '.kuota-display');
                                if (kuotaSpan) {
                                    let currentKuota = parseInt(kuotaSpan.textContent);
                                    if (currentKuota > 0) {
                                        kuotaSpan.textContent = currentKuota - 1;
                                    }
                                }
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses permintaan Anda.');
                        });
                }
            });
        });
    });
    </script>
</body>

</html>