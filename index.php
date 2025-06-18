<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BantuKerja - Tingkatkan Keterampilan, Dapatkan Pekerjaan</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="index.php">KerjaAja</a>
            </div>
        </div>
    </header>
    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1 class="hero-title">
                        KerjaAja - Tingkatkan Keterampilan, Dapatkan Pekerjaan
                    </h1>
                    <p class="hero-description">
                        Platform yang membantu warga desa/kecamatan untuk mendaftar pelatihan kerja dan melamar
                        pekerjaan dari perusahaan lokal.
                    </p>
                    <div class="hero-buttons">
                        <a href="auth/register.php" class="btn btn-primary">
                            Daftar Sekarang
                            <i class="fas fa-arrow-right"></i>
                        </a>
                        <a href="auth/login.php" class="btn btn-outline">
                            Login
                        </a>
                    </div>
                </div>
            </div>
            <div class="hero-banner">
                <img src="img/hero.jpg" class="img-cover">
            </div>
        </div>
        </div>
    </section>

    <section class="features-section">
        <div class="container">
            <div class="features-header">
                <div class="section-title">
                    <h2>Fitur Utama</h2>
                    <p>BantuKerja menyediakan berbagai fitur untuk membantu Anda mendapatkan pelatihan dan pekerjaan
                        yang sesuai.</p>
                </div>
            </div>
            <div class="features-grid">
                <a href="auth/login.php" class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Pelatihan Kerja</h3>
                        <p>Akses berbagai pelatihan kerja untuk meningkatkan keterampilan Anda.</p>
                    </div>
                </a>
                <a href="auth/login.php" class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Lowongan Kerja</h3>
                        <p>Temukan dan lamar pekerjaan dari perusahaan lokal yang sesuai dengan keahlian Anda.</p>
                    </div>
                </a>
                <a href="auth/login.php" class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="feature-content">
                        <h3>Riwayat</h3>
                        <p>Pantau riwayat pelatihan dan lamaran kerja Anda dengan mudah.</p>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="brand">
                        <span>BantuKerja</span>
                    </div>
                    <p class="footer-description">Platform pendaftaran pelatihan & penyaluran kerja warga lokal.</p>
                </div>
                <div class="footer-links">
                    <p class="footer-title">Tautan</p>
                    <nav class="footer-nav">
                        <a href="index.php">Beranda</a>
                        <a href="auth/login.php">Login</a>
                        <a href="auth/register.php">Register</a>
                    </nav>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 BantuKerja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>
    </div>

    <script src="js/main.js"></script>
</body>

</html>