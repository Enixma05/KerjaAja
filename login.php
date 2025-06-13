<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - KerjaAja</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">
                <a href="homePage.php">KerjaAja</a>
            </div>
            <nav class="nav">
                <ul>
                    <li><a href="login.php" class="active">Login</a></li>
                    <li><a href="register.php" class="btn-register">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="auth-section">
        <div class="container">
            <div class="auth-card">
                <div class="auth-header">
                    <h2>Login</h2>
                    <p>Masukkan email dan password Anda untuk masuk ke akun</p>
                </div>
                <form method="POST" action="cek_login.php" id="loginForm" class="auth-form">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" placeholder="nama@email.com" required />
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" required />
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                    <p class="auth-redirect">Belum memiliki akun? <a href="register.php">Register</a></p>
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