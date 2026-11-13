<?php
session_start();

// Function menghitung total (diskon 10% jika subtotal di atas Rp 100.000)
function hitungTotal($jumlah, $harga) {
  $subtotal = $jumlah * $harga;
  $diskon   = ($subtotal > 100000) ? $subtotal * 0.10 : 0;
  return ['subtotal' => $subtotal, 'diskon' => $diskon, 'total' => $subtotal - $diskon];
}

function rupiah($angka) {
  return 'Rp' . number_format($angka, 0, ',', '.');
}

$errors = [];
$hasil  = null;
$produk = $jumlah = $harga = '';

if (isset($_POST['btn_proses'])) {
  $produk = trim($_POST['produk'] ?? '');
  $jumlah = trim($_POST['jumlah'] ?? '');
  $harga  = trim($_POST['harga'] ?? '');

  // Validasi dengan if else
  if ($produk === '') {
    $errors[] = 'Nama paket wajib diisi.';
  }
  if ($jumlah === '') {
    $errors[] = 'Jumlah wajib diisi.';
  } elseif (!is_numeric($jumlah) || $jumlah <= 0) {
    $errors[] = 'Jumlah harus berupa angka lebih dari 0.';
  }
  if ($harga === '') {
    $errors[] = 'Harga satuan wajib diisi.';
  } elseif (!is_numeric($harga) || $harga <= 0) {
    $errors[] = 'Harga satuan harus berupa angka lebih dari 0.';
  }

  if (empty($errors)) {
    $hasil = hitungTotal((float) $jumlah, (float) $harga);
  }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pemesanan Paket - Nusantrip</title>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/pemesanan.css">
</head>
<body class="pesan-page">
  <main class="pesan-card">
    <a href="<?= isset($_SESSION['nama']) ? 'dashboard.php' : 'index.php' ?>" class="kembali">← Kembali</a>
    <h1>Form Pemesanan Paket Wisata</h1>
    <p class="sub">Diskon 10% otomatis jika total di atas Rp100.000.</p>

    <?php if (!empty($errors)) { ?>
      <ul class="error">
        <?php foreach ($errors as $e) { echo '<li>' . $e . '</li>'; } ?>
      </ul>
    <?php } ?>

    <form action="" method="post">
      <label for="produk">Nama Paket / Destinasi</label>
      <input type="text" name="produk" id="produk" value="<?= htmlspecialchars($produk) ?>">

      <label for="jumlah">Jumlah Orang</label>
      <input type="number" name="jumlah" id="jumlah" value="<?= htmlspecialchars($jumlah) ?>">

      <label for="harga">Harga Satuan (Rp)</label>
      <input type="number" name="harga" id="harga" value="<?= htmlspecialchars($harga) ?>">

      <div class="aksi">
        <input type="submit" name="btn_proses" value="Hitung Total" class="btn btn-primary">
        <input type="reset" value="Hapus" class="btn btn-outline">
      </div>
    </form>

    <?php if ($hasil) { ?>
      <table>
        <tr><th>Nama Paket</th><td><?= htmlspecialchars($produk) ?></td></tr>
        <tr><th>Jumlah</th><td><?= $jumlah ?> orang</td></tr>
        <tr><th>Harga Satuan</th><td><?= rupiah($harga) ?></td></tr>
        <tr><th>Subtotal</th><td><?= rupiah($hasil['subtotal']) ?></td></tr>
        <tr><th>Diskon (10%)</th><td><?= rupiah($hasil['diskon']) ?></td></tr>
        <tr class="total"><th>Total Bayar</th><td><?= rupiah($hasil['total']) ?></td></tr>
      </table>
    <?php } else if (empty($errors)) { ?>
      <p class="hint">Hasil perhitungan akan tampil di sini.</p>
    <?php } ?>
  </main>
</body>
</html>