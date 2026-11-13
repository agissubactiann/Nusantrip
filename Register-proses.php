<?php
// BAB 5 : proses register -> validasi lalu simpan nama ke SESSION
session_start();

$nama  = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass  = $_POST['pass'] ?? '';
$pass2 = $_POST['pass2'] ?? '';

if ($nama === '' || $email === '' || $pass === '') {
  $_SESSION['error'] = 'Semua kolom wajib diisi.';
} elseif ($pass !== $pass2) {
  $_SESSION['error'] = 'Konfirmasi kata sandi tidak sama.';
}

if (isset($_SESSION['error'])) {
  header('Location: register.php');         // kembali ke form dan tampilkan pesan error
  exit;
}

$_SESSION['nama'] = explode(' ', $nama)[0]; // simpan nama depan
$_SESSION['login_sebelumnya'] = null;
setcookie('login_terakhir', date('d M Y, H:i'), time() + 86400 * 30, '/');
setcookie('user_terakhir', $email, time() + 86400 * 7, '/');

header('Location: dashboard.php');
exit;