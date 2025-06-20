<?php
session_start();
include '../auth/koneksi.php';

// Cek sesi login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}
$user_id = (int)$_SESSION['user_id'];

$query_user = "SELECT u.name, u.email, up.* FROM users u LEFT JOIN user_profiles up ON u.user_id = up.user_id WHERE u.user_id = '$user_id'";
$result_user = mysqli_query($conn, $query_user);
$user_data = mysqli_fetch_assoc($result_user);
if (!$user_data) { die("Data pengguna tidak ditemukan."); }

// 2. Ambil data statistik
$query_pelatihan_count = "SELECT COUNT(*) as total FROM pendaftaran_pelatihan WHERE user_id = '$user_id'";
$total_pelatihan = mysqli_fetch_assoc(mysqli_query($conn, $query_pelatihan_count))['total'];

$query_lamaran_count = "SELECT COUNT(*) as total FROM lamaran WHERE user_id = '$user_id'";
$total_lamaran = mysqli_fetch_assoc(mysqli_query($conn, $query_lamaran_count))['total'];

// 3. Ambil data untuk setiap tab
$pendidikan_list = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM pendidikan WHERE user_id = '$user_id' ORDER BY tahun_lulus DESC"), MYSQLI_ASSOC);
$pengalaman_list = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM pengalaman_kerja WHERE user_id = '$user_id' ORDER BY tanggal_mulai DESC"), MYSQLI_ASSOC);
$keahlian_list = mysqli_fetch_all(mysqli_query($conn, "SELECT * FROM keahlian WHERE user_id = '$user_id'"), MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profil Saya - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo"><a href="dashboard.php">KerjaAja</a></div>
            <nav class="nav">
                <ul>
                    <li><a href="dashboard.php" class="btn-logout"><i class="fas fa-arrow-left"></i> Kembali ke
                            Dashboard</a></li>
                    <li><a href="../auth/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="main-content">
        <div class="container">
            <div class="profile-header">
                <h1>Profil Saya</h1>
                <p>Kelola informasi personal dan data pendukung untuk karir Anda.</p>
            </div>
            <div class="profile-layout">
                <aside class="profile-sidebar">
                    <div class="profile-card">
                        <div class="profile-avatar">
                            <img id="profileImage"
                                src="<?php echo htmlspecialchars($user_data['path_avatar'] ?? 'img/default-avatar.png'); ?>"
                                alt="Foto Profil">
                            <div class="avatar-upload">
                                <form id="avatarForm" action="proses_upload_avatar.php" method="POST"
                                    enctype="multipart/form-data" style="display: none;">
                                    <input type="file" name="avatar" id="avatarInput" accept="image/*"
                                        onchange="document.getElementById('avatarForm').submit();">
                                </form>
                                <button type="button" class="avatar-btn"
                                    onclick="document.getElementById('avatarInput').click()" title="Ubah Foto Profil">
                                    <i class="fas fa-camera"></i>
                                </button>
                            </div>
                        </div>
                        <div class="profile-info">
                            <h3><?php echo htmlspecialchars($user_data['name']); ?></h3>
                            <p><?php echo htmlspecialchars($user_data['email']); ?></p>
                            <div class="profile-stats">
                                <div class="stat">
                                    <span class="stat-number"><?php echo $total_pelatihan; ?></span>
                                    <span class="stat-label">Pelatihan Diikuti</span>
                                </div>
                                <div class="stat">
                                    <span class="stat-number"><?php echo $total_lamaran; ?></span>
                                    <span class="stat-label">Lamaran Terkirim</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="cv-section">
                        <h4>Curriculum Vitae</h4>
                        <div class="cv-container">
                            <?php if (!empty($user_data['path_cv'])): ?>
                            <div class="cv-preview">
                                <div class="cv-file">
                                    <i class="fas fa-file-pdf fa-lg"></i>
                                    <div class="cv-info">
                                        <span><?php echo basename($user_data['path_cv']); ?></span>
                                        <small>CV telah terunggah</small>
                                    </div>
                                    <div class="cv-actions">
                                        <a href="<?php echo htmlspecialchars($user_data['path_cv']); ?>" download
                                            class="btn-icon" title="Download"><i class="fas fa-download"></i></a>
                                        <a href="proses_hapus_cv.php" class="btn-icon" title="Hapus"
                                            onclick="return confirm('Anda yakin ingin menghapus CV ini?');"><i
                                                class="fas fa-trash"></i></a>
                                    </div>
                                </div>
                            </div>
                            <?php else: ?>
                            <div class="cv-upload">
                                <i class="fas fa-file-upload fa-2x"></i>
                                <p>Anda belum mengunggah CV</p>
                                <small>Format: PDF, Maks. 2MB</small>
                                <form action="proses_upload_cv.php" method="POST" enctype="multipart/form-data">
                                    <input type="file" name="cv" required accept=".pdf" id="cvInput"
                                        onchange="this.form.submit();" hidden>
                                    <button type="button" class="btn btn-primary btn-sm"
                                        onclick="document.getElementById('cvInput').click()">Upload CV</button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </aside>

                <div class="profile-content">
                    <div class="profile-tabs">
                        <button class="tab-btn active" data-tab="personal"><i class="fas fa-user-edit"></i> Data
                            Personal</button>

                    </div>

                    <div id="personalTab" class="tab-pane active">
                        <form action="proses_update_profil.php" method="POST" class="profile-form">
                            <div class="form-section">
                                <h3>Informasi Personal</h3>
                                <div class="form-grid">
                                    <div class="form-group"><label for="fullName">Nama Lengkap *</label><input
                                            type="text" id="fullName" name="name" required
                                            value="<?php echo htmlspecialchars($user_data['name'] ?? ''); ?>"></div>
                                    <div class="form-group"><label for="email">Email *</label><input type="email"
                                            id="email" name="email" required
                                            value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"></div>
                                    <div class="form-group"><label for="phone">Nomor Telepon *</label><input type="tel"
                                            id="phone" name="telepon" required
                                            value="<?php echo htmlspecialchars($user_data['telepon'] ?? ''); ?>"></div>
                                    <div class="form-group"><label for="birthDate">Tanggal Lahir</label><input
                                            type="date" id="birthDate" name="tanggal_lahir"
                                            value="<?php echo htmlspecialchars($user_data['tanggal_lahir'] ?? ''); ?>">
                                    </div>
                                    <div class="form-group"><label for="gender">Jenis Kelamin</label><select id="gender"
                                            name="jenis_kelamin">
                                            <option value="">Pilih</option>
                                            <option value="Laki-laki"
                                                <?php if (($user_data['jenis_kelamin'] ?? '') == 'Laki-laki') echo 'selected'; ?>>
                                                Laki-laki</option>
                                            <option value="Perempuan"
                                                <?php if (($user_data['jenis_kelamin'] ?? '') == 'Perempuan') echo 'selected'; ?>>
                                                Perempuan</option>
                                        </select></div>
                                    <div class="form-group"><label for="religion">Agama</label><input type="text"
                                            id="religion" name="agama"
                                            value="<?php echo htmlspecialchars($user_data['agama'] ?? ''); ?>"></div>
                                </div>
                            </div>
                            <div class="form-section">
                                <h3>Alamat</h3>
                                <div class="form-grid">
                                    <div class="form-group full-width"><label for="address">Alamat Lengkap
                                            *</label><textarea id="address" name="alamat" rows="3"
                                            required><?php echo htmlspecialchars($user_data['alamat'] ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="form-section">
                                <h3>Riwayat Pendidikan</h3>
                                <?php if (count($pendidikan_list) > 0): ?>
                                    <ul class="education-list">
                                        <?php foreach ($pendidikan_list as $edu): ?>
                                            <li>
                                                <strong><?php echo htmlspecialchars($edu['jenjang']); ?></strong> di 
                                                <?php echo htmlspecialchars($edu['institusi']); ?>, 
                                                Jurusan <?php echo htmlspecialchars($edu['jurusan']); ?> 
                                                (Lulus: <?php echo htmlspecialchars($edu['tahun_lulus']); ?>)
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php else: ?>
                                    <p>Belum ada riwayat pendidikan yang ditambahkan.</p>
                                <?php endif; ?>
                                <a href="kelola_pendidikan.php" class="btn btn-secondary btn-sm"><i class="fas fa-plus"></i> Kelola Pendidikan</a>
                            </div>
                                                            
                            <div class="form-actions"><button type="submit" class="btn btn-primary"><i
                                        class="fas fa-save"></i> Simpan Perubahan</button></div>
                        </form>
                    </div>
    </main>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        const tabBtns = document.querySelectorAll(".tab-btn");
        const tabPanes = document.querySelectorAll(".tab-pane");
        tabBtns.forEach(btn => {
            btn.addEventListener("click", function() {
                const tabId = this.dataset.tab;
                tabBtns.forEach(b => b.classList.remove("active"));
                tabPanes.forEach(p => p.classList.remove("active"));
                this.classList.add("active");
                document.getElementById(tabId + "Tab").classList.add("active");
            });
        });

        const educationModal = document.getElementById('educationModal');
        const addEducationBtn = document.getElementById('addEducationBtn');

        if (educationModal && addEducationBtn) { // Cek dulu apakah elemennya ada
            const closeModalBtns = educationModal.querySelectorAll('.close-modal, .close-modal-btn');
            const educationForm = document.getElementById('educationForm');
            const modalTitle = document.getElementById('educationModalTitle');
            const actionInput = document.getElementById('educationAction');
            const idInput = document.getElementById('educationId');

            addEducationBtn.addEventListener('click', function() {
                const modalTarget = document.getElementById('educationModal');
                if (modalTarget) {
                    alert('SUKSES: Modal ditemukan! Mencoba menampilkan...');
                    modalTarget.style.display = 'flex';
                } else {
                    alert('ERROR: Modal TIDAK DITEMUKAN! Periksa ID pada div modal di HTML.');
                }
                educationForm.reset();
                modalTitle.textContent = 'Tambah Riwayat Pendidikan';
                actionInput.value = 'tambah';
                idInput.value = '';
                educationModal.style.display = 'flex';
            });

            document.querySelectorAll('.edit-pendidikan-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const data = this.dataset;
                    document.getElementById('educationLevel').value = data.jenjang;
                    document.getElementById('schoolName').value = data.institusi;
                    document.getElementById('major').value = data.jurusan;
                    document.getElementById('graduationYear').value = data.lulus;
                    modalTitle.textContent = 'Edit Riwayat Pendidikan';
                    actionInput.value = 'edit';
                    idInput.value = data.id;
                    educationModal.style.display = 'flex';
                });
            });

            function closeModal() {
                educationModal.style.display = 'none';
            }
            closeModalBtns.forEach(btn => btn.addEventListener('click', closeModal));
            window.addEventListener('click', function(event) {
                if (event.target == educationModal) {
                    closeModal();
                }
            });
        }

    });
    </script>
</body>

</html>