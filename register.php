<?php session_start(); $error = $_SESSION["error"] ?? null; unset($_SESSION["error"]); ?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Daftar - Nusantrip</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/auth.css">
</head>
<body class="auth-page">

  <header class="auth-top">
    <div class="container auth-top-inner">
      <a href="index.php" class="logo"><span class="logo-icon">≈</span>Nusan<span>trip</span></a>
      <nav>
        <a href="index.php">← Kembali ke Beranda</a>
        <a href="#">Bantuan</a>
      </nav>
    </div>
  </header>

  <main class="auth-main">
    <div class="auth-card">
      <aside class="auth-visual">
        <img src="img/labuan-bajo.jpg" alt="Labuan Bajo">
        <div class="visual-text">
          <h2>Mulai perjalanan magis keliling surga Nusantara.</h2>
          <p>Buat akun gratis, simpan destinasi favorit, dan rencanakan trip berikutnya dalam satu tempat.</p>
        </div>
        <div class="visual-tag"><strong>Labuan Bajo</strong><small>Pilihan baru bulan ini</small></div>
      </aside>

      <section class="auth-form">
        <span class="badge">Mulai perjalananmu</span>
        <h1>Buat akun baru</h1>
        <p class="sub">Daftarkan diri Anda untuk mulai merencanakan perjalanan tak terlupakan.</p>

        <form action="register-proses.php" method="post">
          <?php if ($error): ?><p class="form-error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
          <div class="field">
            <label for="nama">Nama lengkap</label>
            <input type="text" id="nama" name="nama" placeholder="Contoh: Agis Subactian" required>
          </div>
          <div class="field">
            <label for="email">Alamat email</label>
            <input type="email" id="email" name="email" placeholder="nama@email.com" required>
          </div>
          <div class="two-col">
            <div class="field">
              <label for="pass">Kata sandi</label>
              <input type="password" id="pass" name="pass" placeholder="Min. 8 karakter" minlength="8" required>
            </div>
            <div class="field">
              <label for="pass2">Konfirmasi sandi</label>
              <input type="password" id="pass2" name="pass2" placeholder="Ulangi kata sandi" minlength="8" required>
            </div>
          </div>
          <label class="check"><input type="checkbox" name="setuju" required> Saya menyetujui <a href="#" class="link">Syarat &amp; Ketentuan</a> dan <a href="#" class="link">Kebijakan Privasi</a></label>
          <button type="submit" class="btn btn-primary btn-block">Daftar ke Nusantrip →</button>
        </form>

        <div class="divider"><span>atau daftar dengan</span></div>
        <div class="social">
          <button type="button" class="btn btn-outline">Google</button>
          <button type="button" class="btn btn-outline">Apple</button>
        </div>
        <p class="switch">Sudah punya akun? <a href="login.php" class="link">Masuk sekarang</a></p>
      </section>
    </div>
  </main>

  <footer class="auth-foot">
    <div class="container">
      <small>© 2026 Nusantrip Indonesia. Hak cipta dilindungi.</small>
      <small><a href="#">Syarat &amp; Ketentuan</a> · <a href="#">Kebijakan Privasi</a></small>
    </div>
  </footer>

  <script src="js/auth.js"></script>
</body>
</html>