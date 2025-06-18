<?php session_start();?>
<?php
include '../auth/koneksi.php';

// Cek sesi login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$datalowongan = [];
$query = "SELECT * FROM lowongan ORDER BY batas_lamaran DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $datalowongan[] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Manajemen Lowongan Kerja - KerjaAja</title>
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
          <a href="perusahaan-dashboard.php">KerjaAja Perusahaan</a>
        </div>
        <nav class="nav">
          <ul>

            <li>
              <a href="#" id="logoutBtn" class="btn-logout"> <i class="fas fa-sign-out-alt"></i> Logout </a>
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
              <a href="perusahaan-dashboard.php"><i class="fas fa-home"></i> Dashboard</a>
            </li>
            <li>
              <a href="perusahaan-lowongan.php" class="active"><i class="fas fa-briefcase"></i> Lowongan Kerja</a>
            </li>
            <li>
              <a href="perusahaan-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a>
            </li>
          </ul>
        </nav>
      </aside>

      <!-- Main Content -->
      <main class="main-content">
        <div class="page-header-actions">
          <div>
            <h1>Manajemen Lowongan Kerja</h1>
            <p>Kelola data lowongan kerja</p>
          </div>
          <button class="btn btn-primary" id="addJobBtn"><i class="fas fa-plus"></i> Tambah Lowongan</button>
        </div>

        <div class="search-container">
          <div class="search-input">
            <i class="fas fa-search"></i>
            <input type="text" id="searchJob" placeholder="Cari lowongan kerja..." />
          </div>
        </div>

        <div class="data-table-container">
          <table class="data-table" id="jobTable">
            <thead>
              <tr>
                <th>Posisi</th>
                <th>Perusahaan</th>
                <th>Jenis</th>
                <th>Lokasi</th>
                <th>Batas Lamaran</th>
                <th>Kualifikasi</th>
                <th class="text-right">Aksi</th>
              </tr>
            </thead>
            <tbody>
                <?php if (count($datalowongan) > 0): ?>
                    <?php foreach ($datalowongan as $lowongan): ?>
                        <tr>
                            <td><?= htmlspecialchars($lowongan['judul']) ?></td>
                            <td><?= htmlspecialchars($lowongan['perusahaan']) ?></td>
                            <td><?= htmlspecialchars($lowongan['deskripsi']) ?></td>
                            <td><?= htmlspecialchars($lowongan['lokasi']) ?></td>
                            <td><?= date("d/m/Y", strtotime($lowongan['batas_lamaran'])) ?></td>
                            <td><?= htmlspecialchars($lowongan['kualifikasi']) ?></td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    <a href="edit_lowongan.php?id=<?= $lowongan['lowongan_id'] ?>" class="edit-btn">Edit</a>
                                    <a href="delete_lowongan.php?id=<?= $lowongan['lowongan_id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
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

    <!-- Modal Tambah Lowongan Kerja -->
    <div id="modalTambahLowongan" class="modal">
      <div class="modal-content-job">
        <span class="close-modal">&times;</span>
        <h2>Tambah Lowongan Kerja</h2>
        <form id="formTambahLowongan" method="POST" action="tambah_lowongan.php">
          <input type="hidden" id="jobId" name="id"/>
          <div class="form-group">
            <label for="lowonganPosisi">Posisi / Jabatan</label>
            <input type="text" id="lowonganPosisi" name= "judul" placeholder="Contoh: Web Developer" required />
          </div>
          <div class="form-group">
            <label for="lowonganPerusahaan">Nama Perusahaan</label>
            <input type="text" id="lowonganPerusahaan" name= "perusahaan" placeholder="Contoh: PT Teknologi Hebat" required />
          </div>
          <div class="form-group">
            <label for="lowonganJenis">Jenis Pekerjaan</label>
            <select id="lowonganJenis" name= "deskripsi"required>
              <option value="">-- Pilih Jenis --</option>
              <option value="Full Time">Full Time</option>
              <option value="Part Time">Part Time</option>
              <option value="Freelance">Freelance</option>
              <option value="Kontrak">Kontrak</option>
            </select>
          </div>
          <div class="form-group">
            <label for="lowonganLokasi">Lokasi</label>
            <input type="text" id="lowonganLokasi" name= "lokasi" placeholder="Contoh: Jakarta, Surabaya, Remote" required />
          </div>
          <div class="form-group">
            <label for="lowonganDeadline">Batas Lamaran</label>
            <input type="date" id="lowonganDeadline" name= "batas_lamaran" required />
          </div>
          <div class="form-group">
            <label for="lowonganDeskripsi">Kualifikasi</label>
            <textarea id="lowonganDeskripsi" name= "kualifikasi" rows="4" placeholder="Deskripsikan tanggung jawab dan kualifikasi yang dibutuhkan" required></textarea>
          </div>
          <div class="modal-buttons">
            <button type="button" class="btn btn-outline" id="batalTambahLowongan">Batal</button>
            <button type="submit" class="btn btn-primary">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
      <div class="modal-content">
        <span class="close-modal">&times;</span>
        <h2>Hapus Lowongan Kerja</h2>
        <p>Apakah Anda yakin ingin menghapus lowongan kerja ini? Tindakan ini tidak dapat dibatalkan.</p>
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

    <script src="js/data.js"></script>
    <script src="js/main.js"></script>
    <script src="js/notifications.js"></script>
    <script src="js/admin-notifications.js"></script>

    <script>
      document.addEventListener("DOMContentLoaded", function () {
        const jobTable = document.getElementById("jobTable").querySelector("tbody");
        const searchInput = document.getElementById("searchJob");
        const addJobBtn = document.getElementById("addJobBtn");
        const jobModal = document.getElementById("jobModal");
        const deleteModal = document.getElementById("deleteModal");
        const modalTitle = document.getElementById("modalTitle");
        const modalDescription = document.getElementById("modalDescription");
        const jobForm = document.getElementById("jobForm");
        const jobIdInput = document.getElementById("jobId");
        const lowonganPosisi = document.getElementById("lowonganPosisi");
        const lowonganPerusahaan = document.getElementById("lowonganPerusahaan");
        const lowonganJenis = document.getElementById("lowonganJenis");
        const lowonganLokasi = document.getElementById("lowonganLokasi");
        const lowonganDeadline = document.getElementById("lowonganDeadline");
        const lowonganDeskripsi = document.getElementById("lowonganDeskripsi");

        let selectedJobId = null;

        // Search functionality
        searchInput.addEventListener("input", function () {
          const searchTerm = this.value.toLowerCase();
          const filteredJobs = mockAdminJobs.filter((job) => job.title.toLowerCase().includes(searchTerm) || job.company.toLowerCase().includes(searchTerm) || job.location.toLowerCase().includes(searchTerm));
        });


        // Open add job modal
        addJobBtn.addEventListener("click", function () {
          modalTitle.textContent = "Tambah Lowongan Kerja Baru";
          modalDescription.textContent = "Isi form berikut untuk menambahkan lowongan kerja baru";
          jobForm.reset();
          jobIdInput.value = "";
          jobModal.style.display = "block";
        });

        // Open edit job modal
        function openEditModal(jobId) {
          const job = mockAdminJobs.find((j) => j.id == jobId);

          if (job) {
            modalTitle.textContent = "Edit Lowongan Kerja";
            modalDescription.textContent = "Edit informasi lowongan kerja";

            jobIdInput.value = job.id;
            titleInput.value = job.title;
            companyInput.value = job.company;
            typeInput.value = job.type;
            locationInput.value = job.location;
            deadlineInput.value = job.deadline;
            descriptionInput.value = job.description;

            jobModal.style.display = "block";
          }
        }

        // Handle form submission
        jobForm.addEventListener("submit", function (e) {

          // Validate form
          if (!titleInput.value || !companyInput.value || !locationInput.value || !deadlineInput.value || !descriptionInput.value) {
            alert("Semua field harus diisi");
            return;
          }

          // In a real app, this would send data to a server
          if (jobIdInput.value) {
            // Editing existing job
            alert("Lowongan kerja berhasil diperbarui!");
          } else {
            // Adding new job
            alert("Lowongan kerja baru berhasil ditambahkan!");
          }

          jobModal.style.display = "none";
        });

        // Handle delete confirmation
        document.getElementById("confirmDelete").addEventListener("click", function () {
          // In a real app, this would send a delete request to a server
          alert("Lowongan kerja berhasil dihapus!");
          deleteModal.style.display = "none";
        });

        // Close modals
        document.querySelectorAll(".close-modal").forEach((closeBtn) => {
          closeBtn.addEventListener("click", function () {
            jobModal.style.display = "none";
            deleteModal.style.display = "none";
          });
        });

        document.getElementById("batalTambahLowongan").addEventListener("click", function () {
          jobModal.style.display = "none";
        });

        document.getElementById("cancelDelete").addEventListener("click", function () {
          deleteModal.style.display = "none";
        });

        // Close modals when clicking outside
        window.addEventListener("click", function (event) {
          if (event.target === jobModal) {
            jobModal.style.display = "none";
          }
          if (event.target === deleteModal) {
            deleteModal.style.display = "none";
          }
        });

        // Setup logout button
        document.getElementById("logoutBtn").addEventListener("click", function (e) {
          e.preventDefault();
          window.location.href = "logout.php";
        });
      });
    </script>
  </body>
</html>