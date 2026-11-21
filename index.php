<?php
// BAB 7 : CRUD Destinasi - halaman daftar (READ)
session_start();
if (!isset($_SESSION['nama'])) { header('Location: ../login.php'); exit; }
include '../koneksi.php';

$pesan = $_SESSION['pesan'] ?? null;
unset($_SESSION['pesan']);
$hasil = mysqli_query($koneksi, 'SELECT * FROM tb_destinasi ORDER BY id DESC');
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Data Destinasi - Nusantrip</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="../css/dashboard.css">
  <link rel="stylesheet" href="../css/crud.css">
</head>
<body class="dash">
  <aside class="sidebar">
    <a href="../index.php" class="logo"><span class="logo-icon">≈</span>Nusan<span>trip</span></a>
    <nav>
      <a href="../dashboard.php">Dashboard</a>
      <a href="index.php" class="active">Destinasi Saya</a>
      <a href="#">Wishlist</a>
      <a href="#">Riwayat Booking</a>
      <a href="#">Profil</a>
    </nav>
    <a href="../logout.php" class="logout">Keluar</a>
  </aside>

  <div class="content">
    <header class="topbar">
      <strong>Kelola Destinasi</strong>
      <div class="user"><span class="avatar"><?= strtoupper(substr($_SESSION['nama'], 0, 1)) ?></span><div><strong><?= e($_SESSION['nama']) ?></strong><small>Member Nusantrip</small></div></div>
    </header>

    <main>
      <section class="panel">
        <div class="panel-head">
          <div><h2>Data destinasi</h2><p>Tambah, ubah, dan hapus destinasi wisata.</p></div>
          <a href="form.php" class="btn btn-primary btn-sm">+ Tambah destinasi</a>
        </div>

        <?php if ($pesan) { ?><p class="notif"><?= e($pesan) ?></p><?php } ?>

        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>No</th><th>Foto</th><th>Destinasi</th><th>Lokasi</th><th>Kategori</th><th>Harga</th><th>Rating</th><th>Aksi</th></tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($hasil)) { ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?php if ($row['photo']) { ?><img src="../img/destinasi/<?= e($row['photo']) ?>" class="thumb" alt="<?= e($row['nama']) ?>"><?php } else { echo '-'; } ?></td>
                <td><?= e($row['nama']) ?></td>
                <td><?= e($row['lokasi']) ?></td>
                <td><span class="pill"><?= e($row['kategori']) ?></span></td>
                <td><?= rupiah($row['harga']) ?></td>
                <td>★ <?= e($row['rating']) ?></td>
                <td class="act">
                  <a href="form.php?id=<?= (int) $row['id'] ?>">Edit</a>
                  <a href="hapus.php?id=<?= (int) $row['id'] ?>" class="del" onclick="return confirm('Yakin ingin menghapus <?= e(addslashes($row['nama'])) ?>?')">Hapus</a>
                </td>
              </tr>
              <?php } ?>
              <?php if ($no === 1) { ?>
              <tr><td colspan="8" class="kosong">Belum ada data destinasi. Klik "+ Tambah destinasi".</td></tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
</body>
</html>