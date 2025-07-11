<?php session_start();?>
<?php
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$dataPelatihan = [];
$query = "SELECT * FROM pelatihan ORDER BY tanggal DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $dataPelatihan[] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Pelatihan - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
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
                        <a href="../index.php" id="logoutBtn" class="btn-logout"> <i
                                class="fas fa-sign-out-alt"></i>Logout </a>
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
                    <li><a href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin-pelatihan.php" class="active"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="admin-perusahaan.php"><i class="fas fa-building"></i> Perusahaan</a></li>
                    <li><a href="admin-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a></li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <div class="page-header-actions">
                <div>
                    <h1>Manajemen Pelatihan</h1>
                    <p>Kelola data pelatihan</p>
                </div>
                <button class="btn btn-primary" id="addTrainingBtn"><i class="fas fa-plus"></i> Tambah
                    Pelatihan</button>
            </div>

            <div class="search-container">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchTraining" placeholder="Cari pelatihan..." />
                </div>
            </div>

            <div class="data-table-container">
                <table class="data-table" id="trainingTable">
                    <thead>
                        <tr>
                            <th>Nama Pelatihan</th>
                            <th>Tanggal</th>
                            <th>Lokasi</th>
                            <th>Kuota</th>
                            <th>Deskripsi</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($dataPelatihan) > 0): ?>
                        <?php foreach ($dataPelatihan as $pelatihan): ?>
                        <tr>
                            <td><?= htmlspecialchars($pelatihan['nama']) ?></td>
                            <td><?= date("d/m/Y", strtotime($pelatihan['tanggal'])) ?></td>
                            <td><?= htmlspecialchars($pelatihan['lokasi']) ?></td>
                            <td><?= htmlspecialchars($pelatihan['kuota']) ?></td>
                            <td><?= htmlspecialchars($pelatihan['deskripsi']) ?></td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    <a href="edit_pelatihan.php?id=<?= $pelatihan['pelatihan_id'] ?>"
                                        class="edit-btn">Edit</a>
                                    <a href="delete_pelatihan.php?id=<?= $pelatihan['pelatihan_id'] ?>"
                                        onclick="return confirm('Hapus data ini?')">Hapus</a>
                                </div>
                            </td>
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
        </main>
    </div>

    <!-- Add/Edit Training Modal -->
    <div id="trainingModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2 id="modalTitle">Tambah Pelatihan Baru</h2>
            <p id="modalDescription">Isi form berikut untuk menambahkan pelatihan baru</p>
            <form id="trainingForm" method="POST" action="tambah_pelatihan.php">
                <input type="hidden" id="trainingId" name="id" />
                <div class="form-group">
                    <label for="name">Nama Pelatihan</label>
                    <input type="text" id="name" name="nama" placeholder="Nama pelatihan" required />
                </div>
                <div class="form-group">
                    <label for="date">Tanggal</label>
                    <div class="input-icon">
                        <i class="fas fa-calendar"></i>
                        <input type="date" id="date" name="tanggal" placeholder="DD/MM/YYYY" required />
                    </div>
                </div>
                <div class="form-group">
                    <label for="location">Lokasi</label>
                    <input type="text" id="location" name="lokasi" placeholder="Lokasi pelatihan" required />
                </div>
                <div class="form-group">
                    <label for="quota">Kuota</label>
                    <input type="number" id="quota" name="kuota" placeholder="Jumlah kuota" required />
                </div>
                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="deskripsi" placeholder="Deskripsi pelatihan" rows="4"
                        required></textarea>
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-outline" id="cancelTraining">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <span class="close-modal">&times;</span>
            <h2>Hapus Pelatihan</h2>
            <p>Apakah Anda yakin ingin menghapus pelatihan ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="modal-buttons">
                <button class="btn btn-outline" id="cancelDelete">Batal</button>
                <button class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
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

    <script src="../js/main.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const addTrainingBtn = document.getElementById("addTrainingBtn");
        const trainingModal = document.getElementById("trainingModal");
        const deleteModal = document.getElementById("deleteModal");
        const modalTitle = document.getElementById("modalTitle");
        const modalDescription = document.getElementById("modalDescription");
        const trainingForm = document.getElementById("trainingForm");
        const trainingIdInput = document.getElementById("trainingId");
        const nameInput = document.getElementById("name");
        const dateInput = document.getElementById("date");
        const locationInput = document.getElementById("location");
        const quotaInput = document.getElementById("quota");
        const descriptionInput = document.getElementById("description");

        addTrainingBtn.addEventListener("click", function() {
            modalTitle.innerText = "Tambah Pelatihan Baru";
            modalDescription.innerText = "Isi form berikut untuk menambahkan pelatihan baru";
            trainingForm.action = "tambah_pelatihan.php";

            trainingIdInput.value = "";
            nameInput.value = "";
            dateInput.value = "";
            locationInput.value = "";
            quotaInput.value = "";
            descriptionInput.value = "";

            trainingModal.style.display = "block";
        });

        document.querySelectorAll(".close-modal").forEach((btn) => {
            btn.addEventListener("click", function() {
                trainingModal.style.display = "none";
                deleteModal.style.display = "none";
            });
        });

        document.getElementById("cancelTraining").addEventListener("click", function() {
            trainingModal.style.display = "none";
        });

        document.getElementById("cancelDelete").addEventListener("click", function() {
            deleteModal.style.display = "none";
        });

        window.addEventListener("click", function(event) {
            if (event.target === trainingModal) trainingModal.style.display = "none";
            if (event.target === deleteModal) deleteModal.style.display = "none";
        });

        document.getElementById("logoutBtn").addEventListener("click", function(e) {
            e.preventDefault();
            window.location.href = "../auth/logout.php";
        });
    });
    </script>
</body>

</html>