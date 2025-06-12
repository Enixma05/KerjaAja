<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query_sql = "SELECT lowongan_id, judul, perusahaan, lokasi, deskripsi, kualifikasi, batas_lamaran FROM lowongan";
$params = [];
$types = '';

$stmt = $conn->prepare($query_sql . " ORDER BY batas_lamaran ASC");

$stmt->execute();
$result = $stmt->get_result();

$lowongan_data = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $lowongan_data[] = $row;
    }
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lowongan Kerja - kerjaAja</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo"><a href="dashboard.php">KerjaAja</a></div>
            <nav class="nav">
                <ul>
                    <li><a href="profile.php" class="btn-logout"><i class="fas fa-user"></i> Profile</a></li>
                    <li><a href="logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                    <li><a href="lowongan.php" class="active"><i class="fas fa-briefcase"></i> Lowongan Kerja</a></li>
                    <li><a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a></li>
                    <li><a href="notifications.php"><i class="fas fa-bell"></i> Notifikasi</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header">
                <h1>Daftar Lowongan Kerja</h1>
                <p>Temukan dan lamar pekerjaan yang sesuai dengan keahlian Anda</p>
            </div>

            <div class="search-container">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchJob" placeholder="Cari berdasarkan judul, perusahaan, atau lokasi...">
                </div>
            </div>

            <div class="job-grid" id="jobGrid">
            </div>

            <div id="noResults" class="no-results hidden">
                <h3>Tidak ada lowongan yang ditemukan</h3>
                <p>Coba cari dengan kata kunci lain.</p>
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
        const allJobs = <?php echo json_encode($lowongan_data); ?>;

        const jobGrid = document.getElementById('jobGrid');
        const searchInput = document.getElementById('searchJob');
        const noResults = document.getElementById('noResults');

        function displayJobs(jobs) {
            jobGrid.innerHTML = '';

            if (jobs.length === 0) {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }

            jobs.forEach(job => {
                const deadline = new Date(job.batas_lamaran).toLocaleDateString('id-ID', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                });

                const jobTypeClass = job.deskripsi.toLowerCase().replace(' ',
                    '-');

                const card = document.createElement('div');
                card.className = 'job-card';
                card.innerHTML = `
                    <div class="job-header">
                        <h3>${job.judul}</h3>
                        <span class="badge ${jobTypeClass}">${job.deskripsi}</span>
                    </div>
                    <div class="job-card-details">
                        <p><i class="fas fa-building"></i> ${job.perusahaan}</p>
                        <p><i class="fas fa-map-marker-alt"></i> ${job.lokasi}</p>
                        <p><i class="fas fa-calendar-times"></i> Deadline: ${deadline}</p>
                    </div>
                    <p class="job-card-description">${job.kualifikasi.substring(0, 100)}...</p>
                    <a href="lamaran.php?id=${job.lowongan_id}" class="btn btn-primary btn-block">Lamar</a>
                `;
                jobGrid.appendChild(card);
            });
        }

        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();

            const filteredJobs = allJobs.filter(job => {
                return job.judul.toLowerCase().includes(searchTerm) ||
                    job.perusahaan.toLowerCase().includes(searchTerm) ||
                    job.lokasi.toLowerCase().includes(searchTerm);
            });

            displayJobs(filteredJobs);
        });

        displayJobs(allJobs);
    });
    </script>
</body>

</html>