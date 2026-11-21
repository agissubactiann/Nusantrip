<?php
// BAB 7 : CRUD Destinasi - hapus data (DELETE)
session_start();
if (!isset($_SESSION['nama'])) { header('Location: ../login.php'); exit; }
include '../koneksi.php';

$id = (int) ($_GET['id'] ?? 0);
if ($id <= 0) {
  echo "<script>alert('Tidak ada ID yang terdeteksi'); window.location = 'index.php';</script>";
  exit;
}

// Ambil nama file foto agar ikut dihapus dari folder
$q = mysqli_prepare($koneksi, 'SELECT photo FROM tb_destinasi WHERE id = ?');
mysqli_stmt_bind_param($q, 'i', $id);
mysqli_stmt_execute($q);
$data = mysqli_fetch_assoc(mysqli_stmt_get_result($q));

$sql = mysqli_prepare($koneksi, 'DELETE FROM tb_destinasi WHERE id = ?');
mysqli_stmt_bind_param($sql, 'i', $id);

if (mysqli_stmt_execute($sql)) {
  if (!empty($data['photo']) && file_exists('../img/destinasi/' . $data['photo'])) {
    unlink('../img/destinasi/' . $data['photo']);
  }
  $_SESSION['pesan'] = 'Destinasi berhasil dihapus.';
} else {
  $_SESSION['pesan'] = 'Gagal menghapus data.';
}
header('Location: index.php');