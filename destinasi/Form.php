<?php
// BAB 7 : CRUD Destinasi - form tambah (CREATE) dan ubah (UPDATE)
session_start();
if (!isset($_SESSION['nama'])) { header('Location: ../login.php'); exit; }
include '../koneksi.php';

$id  = (int) ($_GET['id'] ?? 0);
$row = ['nama' => '', 'lokasi' => '', 'kategori' => 'Pantai', 'harga' => '', 'rating' => '', 'deskripsi' => '', 'photo' => ''];

if ($id > 0) {                                   // mode edit: ambil data lama
  $q = mysqli_prepare($koneksi, 'SELECT * FROM tb_destinasi WHERE id = ?');
  mysqli_stmt_bind_param($q, 'i', $id);
  mysqli_stmt_execute($q);
  $data = mysqli_fetch_assoc(mysqli_stmt_get_result($q));
  if (!$data) {
    echo "<script>alert('Data tidak ditemukan'); window.location = 'index.php';</script>";
    exit;
  }
  $row = $data;
}
$error = $_SESSION['error_form'] ?? null;
unset($_SESSION['error_form']);
$kategori = ['Pantai', 'Gunung', 'Budaya', 'Kuliner', 'Konservasi'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $id ? 'Edit' : 'Tambah' ?> Destinasi - Nusantrip</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/crud.css">
</head>
<body class="crud-page">
  <main class="crud-card">
    <a href="index.php" class="kembali">← Kembali</a>
    <h1><?= $id ? 'Edit' : 'Tambah' ?> Destinasi</h1>

    <?php if ($error) { ?><p class="notif error"><?= e($error) ?></p><?php } ?>

    <form action="proses.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?= $id ?>">

      <label for="nama">Nama destinasi</label>
      <input type="text" id="nama" name="nama" value="<?= e($row['nama']) ?>" required>

      <label for="lokasi">Lokasi</label>
      <input type="text" id="lokasi" name="lokasi" value="<?= e($row['lokasi']) ?>" required>

      <label for="kategori">Kategori</label>
      <select id="kategori" name="kategori">
        <?php foreach ($kategori as $k) { ?>
          <option value="<?= $k ?>" <?= $row['kategori'] === $k ? 'selected' : '' ?>><?= $k ?></option>
        <?php } ?>
      </select>

      <div class="dua-kolom">
        <div>
          <label for="harga">Harga (Rp)</label>
          <input type="number" id="harga" name="harga" min="0" value="<?= e($row['harga']) ?>" required>
        </div>
        <div>
          <label for="rating">Rating (0 - 5)</label>
          <input type="number" id="rating" name="rating" min="0" max="5" step="0.1" value="<?= e($row['rating']) ?>">
        </div>
      </div>

      <label for="deskripsi">Deskripsi</label>
      <textarea id="deskripsi" name="deskripsi" rows="3"><?= e($row['deskripsi']) ?></textarea>

      <label for="photo">Foto (jpg, png, webp, maks. 2 MB)</label>
      <?php if ($row['photo']) { ?><img src="../img/destinasi/<?= e($row['photo']) ?>" class="thumb besar" alt="Foto saat ini"><?php } ?>
      <input type="file" id="photo" name="photo" accept="image/jpeg,image/png,image/webp">

      <div class="aksi">
        <a href="index.php" class="btn btn-outline">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </main>
</body>
</html>