<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query_sql = "SELECT pelatihan_id, nama, kuota, lokasi, deskripsi, tanggal FROM pelatihan ORDER BY tanggal DESC";
$stmt = $conn->prepare($query_sql);
$stmt->execute();
$result = $stmt->get_result();

$pelatihan_data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $pelatihan_data[] = $row;
    }
}
$stmt->close();
$conn->close();
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

    .no-results.hidden {
        display: none;
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
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchTraining" placeholder="Cari berdasarkan nama atau deskripsi...">
                </div>
            </div>

            <div class="training-grid" id="trainingGrid"></div>
            <div id="noResults" class="no-results hidden">
                <p>Tidak ada pelatihan yang cocok dengan kata kunci yang Anda cari.</p>
            </div>
        </main>
    </div>

    <footer class="footer">
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const allTrainings = <?php echo json_encode($pelatihan_data); ?>;

        const trainingGrid = document.getElementById('trainingGrid');
        const searchInput = document.getElementById('searchTraining');
        const noResults = document.getElementById('noResults');

        function displayTrainings(trainings) {
            trainingGrid.innerHTML = ''; 
            if (trainings.length === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }

            trainings.forEach(training => {
                const tanggal_formatted = new Date(training.tanggal).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                const deskripsi_singkat = training.deskripsi.substring(0, 200) + '...';

                const card = document.createElement('div');
                card.className = 'training-item';
                card.innerHTML = `
                    <div class="training-image"><i class="fas fa-book-open"></i></div>
                    <div class="training-content">
                        <h3>${training.nama}</h3>
                    </div>
                    <div class="training-details">
                        <p><i class="fas fa-users"></i><span class="kuota-display">${training.kuota}</span> Kuota</p>
                        <p><i class="fas fa-calendar"></i> ${tanggal_formatted}</p>
                        <p><i class="fas fa-map-marker-alt"></i> ${training.lokasi}</p>
                    </div>
                    <p class="training-description">${deskripsi_singkat}</p>
                    <button class="btn btn-primary btn-block register-btn" data-id="${training.pelatihan_id}">Daftar</button>
                `;
                trainingGrid.appendChild(card);
            });
        }

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const filteredTrainings = allTrainings.filter(training => {
                return training.nama.toLowerCase().includes(searchTerm) ||
                    training.deskripsi.toLowerCase().includes(searchTerm);
            });
            displayTrainings(filteredTrainings);
        });

        trainingGrid.addEventListener('click', function(e) {
            if (e.target.classList.contains('register-btn')) {
                const button = e.target;
                if (confirm('Apakah Anda yakin ingin mendaftar untuk pelatihan ini?')) {
                    const pelatihanId = button.dataset.id;
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
                                button.textContent = 'Terdaftar';
                                button.disabled = true;
                                const kuotaSpan = button.closest('.training-item').querySelector(
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
            }
        });

        displayTrainings(allTrainings);
    });
    </script>
</body>

</html>