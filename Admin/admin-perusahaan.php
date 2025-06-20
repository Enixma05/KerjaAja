<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// $perusahaan_data = [];
// $query = "SELECT * FROM perusahaan ORDER BY nama_perusahaan ASC";
// $result = mysqli_query($conn, $query);

// if ($result && mysqli_num_rows($result) > 0) {
//     while ($row = mysqli_fetch_assoc($result)) {
//         $perusahaan_data[] = $row;
//     }
// }
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tambah Akun Perusahaan - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link rel="stylesheet" href="../css/notifications.css" />
    <link rel="stylesheet" href="../css/admin-notifications.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="admin-dashboard.php">KerjaAja Admin</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="../index.php" id="logoutBtn" class="btn-logout"> <i class="fas fa-sign-out-alt"></i> Logout </a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="admin-perusahaan.php" class="active"><i class="fas fa-building"></i>Perusahaan</a></li>
                    <li><a href="admin-pendaftar.php"><i class="fas fa-users"></i> Data Pendaftar</a></li>
                </ul>
            </nav>
        </aside>

        <main class="main-content">
            <div class="page-header-actions">
                <div>
                    <h1>Tambah Akun Perusahaan</h1>
                    <p>Daftarkan akun perusahaan untuk login ke platform KerjaAja</p>
                </div>
                <button class="btn btn-primary" id="addCompanyBtn"><i class="fas fa-plus"></i> Tambah Perusahaan</button>
            </div>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <th>Email</th>
                            <th>Alamat</th>
                            <th>Telepon</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- <?php if (count($perusahaan_data) > 0): ?>
                            <?php foreach ($perusahaan_data as $perusahaan): ?> -->buat query
                                <tr>
                                    <td><?= htmlspecialchars($perusahaan['nama_perusahaan']) ?></td>
                                    <td><?= htmlspecialchars($perusahaan['email']) ?></td>
                                    <td><?= htmlspecialchars($perusahaan['alamat']) ?></td>
                                    <td><?= htmlspecialchars($perusahaan['telepon']) ?></td>
                                    <td class="text-right">
                                        <div class="action-buttons">
                                            <a href="edit_perusahaan.php?id=<?= $perusahaan['id'] ?>" class="edit-btn">Edit</a>
                                            <a href="delete_perusahaan.php?id=<?= $perusahaan['id'] ?>" onclick="return confirm('Hapus data ini?')">Hapus</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" style="text-align:center;">Belum ada akun perusahaan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <div id="modalTambahPerusahaan" class="modal">
        <div class="modal-content-job">
            <span class="close-modal">&times;</span>
            <h2>Tambah Akun Perusahaan</h2>
            <form id="formTambahPerusahaan" method="POST" action="tambah_perusahaan.php">
                <div class="form-group">
                    <label for="namaPerusahaan">Nama Perusahaan</label>
                    <input type="text" id="namaPerusahaan" name="nama_perusahaan" required />
                </div>
                <div class="form-group">
                    <label for="emailPerusahaan">Email</label>
                    <input type="email" id="emailPerusahaan" name="email" required />
                </div>
                <div class="form-group">
                    <label for="alamatPerusahaan">Alamat</label>
                    <textarea id="alamatPerusahaan" name="alamat" rows="2"></textarea>
                </div>
                <div class="form-group">
                    <label for="teleponPerusahaan">Telepon</label>
                    <input type="text" id="teleponPerusahaan" name="telepon" />
                </div>
                <div class="form-group">
                    <label for="passwordPerusahaan">Password</label>
                    <input type="password" id="passwordPerusahaan" name="password" required />
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-outline" id="batalTambahPerusahaan">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 KerjaAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById("addCompanyBtn").addEventListener("click", function () {
            document.getElementById("modalTambahPerusahaan").style.display = "block";
        });

        document.querySelector(".close-modal").addEventListener("click", function () {
            document.getElementById("modalTambahPerusahaan").style.display = "none";
        });

        document.getElementById("batalTambahPerusahaan").addEventListener("click", function () {
            document.getElementById("modalTambahPerusahaan").style.display = "none";
        });

        window.addEventListener("click", function (event) {
            const modal = document.getElementById("modalTambahPerusahaan");
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    </script>
</body>

</html>
