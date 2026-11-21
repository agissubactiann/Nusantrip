<?php
// BAB 7 : proses login -> cek email dan password di tb_user (READ), simpan SESSION
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: login.php');
  exit;
}

$email = trim($_POST['user'] ?? '');
$pass  = $_POST['pass'] ?? '';

$sql = mysqli_prepare($koneksi, 'SELECT id, nama, password FROM tb_user WHERE email = ?');
mysqli_stmt_bind_param($sql, 's', $email);
mysqli_stmt_execute($sql);
mysqli_stmt_bind_result($sql, $id, $nama, $hash);

// Jika email ditemukan dan password cocok dengan hash
if (mysqli_stmt_fetch($sql) && password_verify($pass, $hash)) {
  $_SESSION['id']   = $id;
  $_SESSION['nama'] = $nama;
  $_SESSION['login_sebelumnya'] = $_COOKIE['login_terakhir'] ?? null;     // dari COOKIE
  setcookie('login_terakhir', date('d M Y, H:i'), time() + 86400 * 30, '/');

  echo "<script>
    alert('Login berhasil, selamat datang " . addslashes(e($nama)) . "');
    window.location = 'dashboard.php';
  </script>";
} else {
  $_SESSION['error'] = 'Email atau kata sandi salah.';
  header('Location: login.php');
}