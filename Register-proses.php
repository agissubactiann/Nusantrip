<?php
// BAB 7 : proses register -> simpan user baru ke tb_user (CREATE)
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: register.php');
  exit;
}

$nama  = trim($_POST['nama'] ?? '');
$email = trim($_POST['email'] ?? '');
$pass  = $_POST['pass'] ?? '';
$pass2 = $_POST['pass2'] ?? '';

// Validasi
if ($nama === '' || $email === '' || $pass === '') {
  $_SESSION['error'] = 'Semua kolom wajib diisi.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
  $_SESSION['error'] = 'Format email tidak valid.';
} elseif (strlen($pass) < 8) {
  $_SESSION['error'] = 'Kata sandi minimal 8 karakter.';
} elseif ($pass !== $pass2) {
  $_SESSION['error'] = 'Konfirmasi kata sandi tidak sama.';
} else {
  // Cek apakah email sudah terdaftar
  $cek = mysqli_prepare($koneksi, 'SELECT id FROM tb_user WHERE email = ?');
  mysqli_stmt_bind_param($cek, 's', $email);
  mysqli_stmt_execute($cek);
  mysqli_stmt_store_result($cek);
  if (mysqli_stmt_num_rows($cek) > 0) {
    $_SESSION['error'] = 'Email sudah terdaftar, gunakan email lain.';
  }
}

if (isset($_SESSION['error'])) {
  header('Location: register.php');
  exit;
}

// Simpan data. Password di-hash agar tidak tersimpan sebagai teks biasa
$hash = password_hash($pass, PASSWORD_DEFAULT);
$sql  = mysqli_prepare($koneksi, 'INSERT INTO tb_user (nama, email, password, tgl_daftar) VALUES (?, ?, ?, CURDATE())');
mysqli_stmt_bind_param($sql, 'sss', $nama, $email, $hash);

if (mysqli_stmt_execute($sql)) {
  echo "<script>
    alert('Registrasi berhasil, silakan login');
    window.location = 'login.php';
  </script>";
} else {
  $_SESSION['error'] = 'Registrasi gagal, coba lagi.';
  header('Location: register.php');
}