<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - KerjaAja</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="../index.php">KerjaAja</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php" class="btn-register active">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Register</h2>
                    <p>Buat akun baru untuk mengakses KerjaAja</p>
                </div>
                <form method="POST" action="cek_register.php" id="registerForm" class="auth-form">
                    <div class="form-group">
                        <label for="name">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama lengkap Anda" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="nama@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role</label>
                        <select name="role" required>
                            <option value="" disabled selected>Pilih role Anda</option>
                            <option value="admin">Admin</option>
                            <option value="perusahaan">Perusahaan</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="confirmPassword">Konfirmasi Password</label>
                        <input type="password" id="confirmPassword" required>
                    </div>
                    <div id="errorMessage" class="error-message"></div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                    <p class="auth-redirect">
                        Sudah memiliki akun? <a href="login.php">Login</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <div class="footer-bottom">
                <p>&copy; 2025 KerjaAja. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>

</body>

</html>