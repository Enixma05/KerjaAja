<?php
session_start();
include '../auth/koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?pesan=harus_login");
    exit();
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Error: ID Lowongan tidak valid.");
}

$lowongan_id = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT judul, perusahaan FROM lowongan WHERE lowongan_id = ?");
$stmt->bind_param("i", $lowongan_id);
$stmt->execute();
$result = $stmt->get_result();
$lowongan = $result->fetch_assoc();

if (!$lowongan) {
    die("Lowongan tidak ditemukan.");
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulir Lamaran - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
    .form-container {
        max-width: 700px;
        margin: 2rem auto;
        padding: 2rem;
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .form-header {
        text-align: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 1rem;
        margin-bottom: 2rem;
    }

    .file-input {
        border: 2px dashed #ddd;
        padding: 1rem;
        text-align: center;
        cursor: pointer;
        border-radius: 5px;
    }

    .file-input:hover {
        border-color: #007bff;
    }

    .file-input #fileName {
        margin-left: 10px;
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
                    <li><a href="../auth/logout.php" class="btn-logout"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="form-container">
            <div class="form-header">
                <h2>Formulir Lamaran Pekerjaan</h2>
                <p>Anda akan melamar untuk posisi <strong><?php echo htmlspecialchars($lowongan['judul']); ?></strong>
                    di <strong><?php echo htmlspecialchars($lowongan['perusahaan']); ?></strong>.</p>
            </div>

            <form id="jobApplicationForm" action="proses_lamaran.php" method="POST" enctype="multipart/form-data">

                <input type="hidden" name="lowongan_id" value="<?php echo $lowongan_id; ?>">

                <div class="form-group">
                    <label for="cv">Unggah CV (Hanya PDF, Maks. 2MB)</label>
                    <div class="file-input" id="fileInputContainer">
                        <i class="fas fa-upload"></i>
                        <span id="fileName">Pilih file</span>
                        <input type="file" id="cv" name="cv" accept=".pdf" style="display: none;" required>
                    </div>
                    <small id="fileError" style="color: red;"></small>
                </div>

                <div class="form-group">
                    <label for="coverLetter">Surat Lamaran (opsional)</label>
                    <textarea id="coverLetter" name="coverLetter"
                        placeholder="Tambahkan surat lamaran jika diperlukan"></textarea>
                </div>

                <div class="modal-buttons">
                    <a href="lowongan.php" class="btn btn-outline">Batal</a>
                    <button type="submit" class="btn btn-primary">Kirim Lamaran</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInputContainer = document.getElementById('fileInputContainer');
        const fileInput = document.getElementById('cv');
        const fileNameSpan = document.getElementById('fileName');
        const fileErrorSpan = document.getElementById('fileError');
        const form = document.getElementById('jobApplicationForm');

        fileInputContainer.addEventListener('click', function() {
            fileInput.click();
        });

        fileInput.addEventListener('change', function() {
            fileErrorSpan.textContent = '';
            if (this.files.length > 0) {

                if (this.files[0].size > 2 * 1024 * 1024) {
                    fileErrorSpan.textContent = 'Ukuran file terlalu besar! Maksimal 2MB.';
                    this.value = ''; // Reset input file
                    fileNameSpan.textContent = 'Pilih file';
                } else {
                    fileNameSpan.textContent = this.files[0].name;
                }
            } else {
                fileNameSpan.textContent = 'Pilih file';
            }
        });

        form.addEventListener('submit', function(e) {
            if (fileInput.files.length === 0) {
                e.preventDefault();
                fileErrorSpan.textContent = 'CV wajib diunggah!';
            }
        });
    });
    </script>
</body>

</html>