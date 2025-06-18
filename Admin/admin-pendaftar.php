<?php session_start();?>
<?php
include '../auth/koneksi.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$dataPendaftar = [];
$query = "SELECT p.*, u.name AS nama_user, plt.nama AS nama_pelatihan
          FROM pendaftaran_pelatihan p
          JOIN users u ON p.user_id = u.user_id
          JOIN pelatihan plt ON p.pelatihan_id = plt.pelatihan_id
          ORDER BY p.tanggal_daftar DESC";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataPendaftar[] = $row;
    }
}
mysqli_close($conn);


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Pendaftar - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/notifications.css" />
    <link rel="stylesheet" href="../css/admin-notifications.css" />
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
                    <li>
                        <a href="../index.php" id="logoutBtn" class="btn-logout"> <i class="fas fa-sign-out-alt"></i>
                            Logout </a>
                    </li>
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
                    <li>
                        <a href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
                    </li>
                    <li>
                        <a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a>
                    </li>
                    <li>
                        <a href="admin-pendaftar.php" class="active"><i class="fas fa-users"></i> Data Pendaftar</a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header">
                <h1>Data Pendaftar</h1>
                <p>Kelola data pendaftar pelatihan dan pelamar kerja</p>
            </div>

            <div class="tabs">
                <div class="tab-header">
                    <button class="tab-btn active" data-tab="pelatihan">Pendaftar Pelatihan</button>
                </div>
                <div class="tab-content-profile">
                    <class="tab-pane active" id="pelatihanTab">
                        <div class="search-container">
                            <div class="search-input">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchTrainingApplicant" placeholder="Cari pendaftar..." />
                            </div>
                        </div>
                        <div class="data-table-container">
                            <table class="data-table" id="trainingApplicantTable">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Pelatihan</th>
                                        <th>Tanggal Daftar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($dataPendaftar) > 0): ?>
                                    <?php foreach ($dataPendaftar as $pendaftar): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($pendaftar['nama_user']) ?></td>
                                        <td><?= htmlspecialchars($pendaftar['nama_pelatihan']) ?></td>
                                        <td><?= date("d/m/Y", strtotime($pendaftar['tanggal_daftar'])) ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <tr>
                                        <td colspan="6" style="text-align:center;">Belum ada data pelatihan.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
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

    <script src="js/data.js"></script>
    <script src="js/main.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/admin-notifications.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabBtns = document.querySelectorAll(".tab-btn");
        const tabPanes = document.querySelectorAll(".tab-pane");
        const trainingApplicantTable = document.getElementById("trainingApplicantTable").querySelector("tbody");
        const jobApplicantTable = document.getElementById("jobApplicantTable").querySelector("tbody");
        const searchTrainingApplicant = document.getElementById("searchTrainingApplicant");
        const searchJobApplicant = document.getElementById("searchJobApplicant");

        // Tab functionality
        tabBtns.forEach((btn) => {
            btn.addEventListener("click", function() {
                const tabName = this.getAttribute("data-tab");

                // Remove active class from all buttons and panes
                tabBtns.forEach((b) => b.classList.remove("active"));
                tabPanes.forEach((p) => p.classList.remove("active"));

                // Add active class to current button and pane
                this.classList.add("active");
                document.getElementById(tabName + "Tab").classList.add("active");
            });
        });

        // Display training applicants
        displayTrainingApplicants(mockTrainingApplicants);

        // Display job applicants
        displayJobApplicants(mockJobApplicants);

        // Search training applicants
        searchTrainingApplicant.addEventListener("input", function() {
            const searchTerm = this.value.toLowerCase();
            const filteredApplicants = mockTrainingApplicants.filter((applicant) => applicant.name
                .toLowerCase().includes(searchTerm) || applicant.training.toLowerCase().includes(
                    searchTerm));

            displayTrainingApplicants(filteredApplicants);
        });

        // Search job applicants
        searchJobApplicant.addEventListener("input", function() {
            const searchTerm = this.value.toLowerCase();
            const filteredApplicants = mockJobApplicants.filter(
                (applicant) => applicant.name.toLowerCase().includes(searchTerm) || applicant
                .position.toLowerCase().includes(searchTerm) || applicant.company.toLowerCase()
                .includes(searchTerm)
            );

            displayJobApplicants(filteredApplicants);
        });

        // Function to display training applicants

        // Function to display job applicants
        function displayJobApplicants(applicants) {
            jobApplicantTable.innerHTML = "";

            if (applicants.length === 0) {
                const emptyRow = document.createElement("tr");
                emptyRow.innerHTML = `
                        <td colspan="7" class="text-center">Tidak ada pelamar yang ditemukan</td>
                    `;
                jobApplicantTable.appendChild(emptyRow);
            } else {
                applicants.forEach((applicant) => {
                    const row = document.createElement("tr");

                    let statusClass = "";
                    let statusText = "";

                    switch (applicant.status) {
                        case "pending":
                            statusClass = "status-pending";
                            statusText = "Menunggu";
                            break;
                        case "accepted":
                            statusClass = "status-accepted";
                            statusText = "Diterima";
                            break;
                        case "rejected":
                            statusClass = "status-rejected";
                            statusText = "Ditolak";
                            break;
                    }

                    row.innerHTML = `
                            <td>${applicant.name}</td>
                            <td>${applicant.position}</td>
                            <td>${applicant.company}</td>
                            <td>${applicant.date}</td>
                            <td><a href="#" class="link">${applicant.cv}</a></td>
                            <td><span class="status-badge ${statusClass}">${statusText}</span></td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    ${
                                      applicant.status === "pending"
                                        ? `
                                        <button class="btn btn-sm btn-success accept-job-btn" data-id="${applicant.id}">
                                            <i class="fas fa-check"></i> Terima
                                        </button>
                                        <button class="btn btn-sm btn-danger reject-job-btn" data-id="${applicant.id}">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    `
                                        : ""
                                    }
                                </div>
                            </td>
                        `;

                    jobApplicantTable.appendChild(row);
                });

                // Add event listeners to action buttons
                document.querySelectorAll(".accept-job-btn").forEach((button) => {
                    button.addEventListener("click", function() {
                        const applicantId = this.getAttribute("data-id");
                        // In a real app, this would send data to a server
                        alert("Pelamar berhasil diterima untuk posisi yang dilamar!");
                    });
                });

                document.querySelectorAll(".reject-job-btn").forEach((button) => {
                    button.addEventListener("click", function() {
                        const applicantId = this.getAttribute("data-id");
                        // In a real app, this would send data to a server
                        alert("Pelamar telah ditolak untuk posisi yang dilamar!");
                    });
                });
            }
        }

        // Setup logout button
        document.getElementById("logoutBtn").addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = "../auth/logout.php";
        });
    });
    </script>
</body>

</html>