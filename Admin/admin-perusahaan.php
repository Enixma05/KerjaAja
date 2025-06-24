<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';

unset($_SESSION['success_message']);
unset($_SESSION['error_message']);

$perusahaan_data = [];
$query = "SELECT * FROM users WHERE role = 'perusahaan' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $perusahaan_data[] = $row;
    }
}
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Akun Perusahaan - KerjaAja</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="admin-dashboard.php">KerjaAja Admin</a>
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

    <div class="dashboard-container">
        <aside class="sidebar">
            <nav class="sidebar-nav">
                <ul>
                    <li><a href="admin-dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
                    <li><a href="admin-pelatihan.php"><i class="fas fa-book"></i> Pelatihan</a></li>
                    <li><a href="admin-perusahaan.php" class="active"><i class="fas fa-building"></i> Perusahaan</a>
                    </li>
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
                <button class="btn btn-primary" id="addCompanyBtn" type="button">
                    <i class="fas fa-plus"></i> Tambah Perusahaan
                </button>
            </div>

            <!-- Alert Container -->
            <div id="alertContainer"></div>

            <div class="data-table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Perusahaan</th>
                            <th>Email</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($perusahaan_data) > 0): ?>
                        <?php foreach ($perusahaan_data as $perusahaan): ?>
                        <tr>
                            <td><?= htmlspecialchars($perusahaan['name']) ?></td>
                            <td><?= htmlspecialchars($perusahaan['email']) ?></td>
                            <td class="text-right">
                                <div class="action-buttons">
                                    <a href="edit_perusahaan.php?id=<?= $perusahaan['user_id'] ?>"
                                        class="edit-btn">Edit</a>
                                    <a href="delete_perusahaan.php?id=<?= $perusahaan['user_id'] ?>"
                                        onclick="return confirm('Hapus data ini?')">Hapus</a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align:center;">Belum ada akun perusahaan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>

    <!-- Modal Tambah Perusahaan -->
    <div id="modalTambahPerusahaan" class="modal">
        <div class="modal-content-job">
            <span class="close-modal">&times;</span>
            <h2>Tambah Akun Perusahaan</h2>
            <form id="formTambahPerusahaan" method="POST" action="tambah_perusahaan.php">
                <div class="form-group">
                    <label for="name">Nama Perusahaan *</label>
                    <input type="text" id="name" name="name" required placeholder="Masukkan nama perusahaan">
                </div>
                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required placeholder="contoh@perusahaan.com">
                </div>
                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" required placeholder="Minimal 6 karakter"
                        minlength="6">
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn btn-outline" id="batalTambahPerusahaan">Batal</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan
                    </button>
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
    function showToast(message, type = 'success') {
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
                <div class="toast-content">
                    <i class="${type === 'success' ? 'fas fa-check-circle' : 'fas fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
                <button class="toast-close">&times;</button>
            `;

        document.body.appendChild(toast);

        setTimeout(() => {
            toast.classList.add('show');
        }, 100);

        setTimeout(() => {
            hideToast(toast);
        }, 3000);

        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                hideToast(toast);
            });
        }
    }

    function hideToast(toast) {
        if (toast && toast.parentNode) {
            toast.classList.remove('show');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
    }

    function openModal(modal) {
        if (!modal) {
            console.error('Modal element not found');
            return;
        }

        modal.style.display = "flex";
        setTimeout(() => {
            modal.classList.add("show");
        }, 10);
        document.body.style.overflow = "hidden";
    }

    function closeModal(modal) {
        if (!modal) return;

        modal.classList.remove("show");
        setTimeout(() => {
            modal.style.display = "none";
            document.body.style.overflow = "";
        }, 300);
    }

    document.addEventListener("DOMContentLoaded", function() {
        <?php if (!empty($success_message)): ?>
        showToast("<?= addslashes($success_message) ?>", "success");
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
        showToast("<?= addslashes($error_message) ?>", "error");
        <?php endif; ?>

        const addCompanyBtn = document.getElementById("addCompanyBtn");
        const modal = document.getElementById("modalTambahPerusahaan");
        const form = document.getElementById("formTambahPerusahaan");
        const closeModalBtn = document.querySelector(".close-modal");
        const cancelBtn = document.getElementById("batalTambahPerusahaan");
        const submitBtn = document.getElementById("submitBtn");

        const nameInput = document.getElementById("name");
        const emailInput = document.getElementById("email");
        const passwordInput = document.getElementById("password");

        if (addCompanyBtn) {
            addCompanyBtn.addEventListener("click", function(e) {
                e.preventDefault();

                if (form) form.reset();

                openModal(modal);

                if (nameInput) nameInput.focus();
            });
        }

        if (closeModalBtn) {
            closeModalBtn.addEventListener("click", function(e) {
                e.preventDefault();
                closeModal(modal);
            });
        }

        if (cancelBtn) {
            cancelBtn.addEventListener("click", function(e) {
                e.preventDefault();
                closeModal(modal);
            });
        }

        window.addEventListener("click", function(event) {
            if (event.target === modal) {
                closeModal(modal);
            }
        });

        document.addEventListener("keydown", function(event) {
            if (event.key === "Escape") {
                closeModal(modal);
            }
        });

        if (form) {
            form.addEventListener("submit", function(e) {
                const name = nameInput.value.trim();
                const email = emailInput.value.trim();
                const password = passwordInput.value.trim();

                if (!name || !email || !password) {
                    e.preventDefault();
                    showToast("Semua field harus diisi!", "error");
                    return;
                }

                if (password.length < 6) {
                    e.preventDefault();
                    showToast("Password minimal 6 karakter!", "error");
                    return;
                }

                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(email)) {
                    e.preventDefault();
                    showToast("Format email tidak valid!", "error");
                    return;
                }
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
            });
        }

        const logoutBtn = document.getElementById("logoutBtn");
        if (logoutBtn) {
            logoutBtn.addEventListener("click", function(e) {
                e.preventDefault();
                if (confirm("Yakin ingin logout?")) {
                    window.location.href = "../index.php";
                }
            });
        }
    });
    </script>
</body>

</html>