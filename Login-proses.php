<?php
// BAB 5 : proses login -> simpan nama ke SESSION dan COOKIE
session_start();

$user = trim($_POST['user'] ?? '');
$pass = $_POST['pass'] ?? '';

if ($user === '' || $pass === '') {          // validasi dengan if
  $_SESSION['error'] = 'Email/username dan kata sandi wajib diisi.';
  header('Location: login.php');
  exit;
}

$_SESSION['nama'] = ucfirst(explode('@', $user)[0]);         // SESSION (di server)
$_SESSION['login_sebelumnya'] = $_COOKIE['login_terakhir'] ?? null; // waktu login terakhir dari COOKIE
setcookie('login_terakhir', date('d M Y, H:i'), time() + 86400 * 30, '/'); // COOKIE 30 hari
setcookie('user_terakhir', $user, time() + 86400 * 7, '/');

header('Location: dashboard.php');
exit;