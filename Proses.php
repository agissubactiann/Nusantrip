<?php
// BAB 7 : CRUD Destinasi - proses simpan (INSERT jika id kosong, UPDATE jika ada id)
session_start();
if (!isset($_SESSION['nama'])) { header('Location: ../login.php'); exit; }
include '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: index.php'); exit; }

$id        = (int) ($_POST['id'] ?? 0);
$nama      = trim($_POST['nama'] ?? '');
$lokasi    = trim($_POST['lokasi'] ?? '');
$kategori  = trim($_POST['kategori'] ?? '');
$harga     = $_POST['harga'] ?? '';
$rating    = ($_POST['rating'] ?? '') === '' ? 0 : (float) $_POST['rating'];
$deskripsi = trim($_POST['deskripsi'] ?? '');

function kembali($pesan, $id) {
  $_SESSION['error_form'] = $pesan;
  header('Location: form.php' . ($id ? '?id=' . $id : ''));
  exit;
}

// Validasi
if ($nama === '' || $lokasi === '') kembali('Nama dan lokasi wajib diisi.', $id);
if (!is_numeric($harga) || $harga < 0) kembali('Harga harus berupa angka, minimal 0.', $id);
if ($rating < 0 || $rating > 5) kembali('Rating harus antara 0 sampai 5.', $id);
$harga = (int) $harga;

// Upload foto (opsional)
$fotoBaru = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] !== UPLOAD_ERR_NO_FILE) {
  $file = $_FILES['photo'];
  $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
  if ($file['error'] !== UPLOAD_ERR_OK)              kembali('Upload foto gagal.', $id);
  if (!in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) kembali('Format foto harus jpg, png, atau webp.', $id);
  if ($file['size'] > 2 * 1024 * 1024)               kembali('Ukuran foto maksimal 2 MB.', $id);

  $folder = '../img/destinasi/';
  if (!is_dir($folder)) mkdir($folder, 0777, true);
  $fotoBaru = 'dest_' . uniqid() . '.' . $ext;
  move_uploaded_file($file['tmp_name'], $folder . $fotoBaru);
}

if ($id === 0) {
  // CREATE
  $sql = mysqli_prepare($koneksi, 'INSERT INTO tb_destinasi (photo, nama, lokasi, kategori, harga, rating, deskripsi) VALUES (?, ?, ?, ?, ?, ?, ?)');
  mysqli_stmt_bind_param($sql, 'ssssids', $fotoBaru, $nama, $lokasi, $kategori, $harga, $rating, $deskripsi);
  $pesan = 'Destinasi berhasil ditambahkan.';
} else {
  // UPDATE
  if ($fotoBaru) {
    // hapus foto lama jika ada foto baru
    $q = mysqli_prepare($koneksi, 'SELECT photo FROM tb_destinasi WHERE id = ?');
    mysqli_stmt_bind_param($q, 'i', $id);
    mysqli_stmt_execute($q);
    $lama = mysqli_fetch_assoc(mysqli_stmt_get_result($q))['photo'] ?? null;
    if ($lama && file_exists('../img/destinasi/' . $lama)) unlink('../img/destinasi/' . $lama);

    $sql = mysqli_prepare($koneksi, 'UPDATE tb_destinasi SET photo=?, nama=?, lokasi=?, kategori=?, harga=?, rating=?, deskripsi=? WHERE id=?');
    mysqli_stmt_bind_param($sql, 'ssssidsi', $fotoBaru, $nama, $lokasi, $kategori, $harga, $rating, $deskripsi, $id);
  } else {
    $sql = mysqli_prepare($koneksi, 'UPDATE tb_destinasi SET nama=?, lokasi=?, kategori=?, harga=?, rating=?, deskripsi=? WHERE id=?');
    mysqli_stmt_bind_param($sql, 'sssidsi', $nama, $lokasi, $kategori, $harga, $rating, $deskripsi, $id);
  }
  $pesan = 'Destinasi berhasil diubah.';
}

if (mysqli_stmt_execute($sql)) {
  $_SESSION['pesan'] = $pesan;
  header('Location: index.php');
} else {
  kembali('Gagal menyimpan data.', $id);
}