<?php
session_start();
// Halaman ini hanya boleh dibuka jika sudah login (ada session)
if (!isset($_SESSION["nama"])) {
    header("Location: login.php");
    exit;
}
$nama = $_SESSION["nama"];
$loginSebelumnya = $_SESSION["login_sebelumnya"] ?? null;

function rupiah($angka) {
    return "Rp" . number_format($angka, 0, ",", ".");
}

// Data 1: Destinasi (array 2 dimensi)
$destinasi = [
    ["Raja Ampat", "Papua Barat Daya", "Konservasi", 4850000, 4.9],
    ["Gunung Bromo", "Jawa Timur", "Gunung", 1350000, 4.9],
    ["Ubud", "Bali", "Budaya", 2750000, 4.7],
    ["Labuan Bajo", "Nusa Tenggara Timur", "Pantai", 2900000, 4.8],
    ["Kawah Ijen", "Banyuwangi, Jawa Timur", "Gunung", 1150000, 4.8],
];

// Data 2: Booking (array 2 dimensi)
$booking = [
    ["Labuan Bajo Liveaboard", "18 Okt 2026", 2, 5800000, "Dikonfirmasi", "ok"],
    ["Sunrise Tour Bromo", "07 Nov 2026", 4, 5400000, "Menunggu", "wait"],
    ["Ekspedisi Raja Ampat", "12 Des 2026", 2, 9700000, "Menunggu", "wait"],
    ["Ubud Retreat", "03 Agu 2026", 1, 2750000, "Selesai", "done"],
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dashboard - Nusantrip</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
</head>
<body class="dash" data-nama="<?= htmlspecialchars($nama) ?>">

  <aside class="sidebar">
    <a href="index.php" class="logo"><span class="logo-icon">≈</span>Nusan<span>trip</span></a>
    <nav>
      <a href="dashboard.php" class="active">Dashboard</a>
      <a href="#destinasi">Destinasi Saya</a>
      <a href="#">Wishlist</a>
      <a href="#booking">Riwayat Booking</a>
      <a href="#">Profil</a>
    </nav>
    <a href="logout.php" class="logout" id="logout">Keluar</a>
  </aside>

  <div class="content">
    <header class="topbar">
      <input type="search" id="cari" placeholder="Cari destinasi, trip, atau booking..." aria-label="Cari">
      <div class="user"><span class="avatar"><?= strtoupper(substr($nama, 0, 1)) ?></span><div><strong><?= htmlspecialchars($nama) ?></strong><small>Member Nusantrip</small></div></div>
    </header>

    <main>
      <section class="hello">
        <div>
          <h1 id="sapaan">Halo, <?= htmlspecialchars($nama) ?>! 👋</h1>
          <p>Selamat datang kembali. Lihat rencana perjalanan dan destinasi favoritmu hari ini.</p>
          <p id="tips" class="tips">Memuat tips...</p>
          <p class="info-login"><?= $loginSebelumnya ? "Login terakhir: " . htmlspecialchars($loginSebelumnya) : "Ini login pertamamu. Selamat datang!" ?></p>
        </div>
        <a href="index.php#destinasi" class="btn btn-primary btn-sm">Jelajahi destinasi →</a>
      </section>

      <section class="stats">
        <div class="stat"><small>Destinasi tersimpan</small><strong><?= count($destinasi) ?></strong><span>Wishlist aktif</span></div>
        <div class="stat"><small>Perjalanan</small><strong>3</strong><span>Trip direncanakan</span></div>
        <div class="stat"><small>Pemesanan</small><strong><?= count($booking) ?></strong><span>Booking tercatat</span></div>
      </section>

      <!-- DATA 1: Destinasi -->
      <section class="panel" id="destinasi">
        <div class="panel-head">
          <div><h2>Data destinasi</h2><p>Destinasi yang kamu simpan dan rencanakan.</p></div>
          <button class="btn btn-primary btn-sm" id="tambah-destinasi">+ Tambah destinasi</button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>No</th><th>Destinasi</th><th>Lokasi</th><th>Kategori</th><th>Harga</th><th>Rating</th><th>Aksi</th></tr>
            </thead>
            <tbody id="tbody-destinasi">
              <?php foreach ($destinasi as $i => $d): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($d[0]) ?></td>
                <td><?= htmlspecialchars($d[1]) ?></td>
                <td><span class="pill"><?= htmlspecialchars($d[2]) ?></span></td>
                <td><?= rupiah($d[3]) ?></td>
                <td>★ <?= $d[4] ?></td>
                <td class="act"><button>Edit</button><button class="del">Hapus</button></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>

      <!-- DATA 2: Booking -->
      <section class="panel" id="booking">
        <div class="panel-head">
          <div><h2>Data booking</h2><p>Riwayat dan jadwal pemesanan perjalananmu.</p></div>
          <button class="btn btn-primary btn-sm" id="tambah-booking">+ Tambah booking</button>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr><th>No</th><th>Nama trip</th><th>Tanggal</th><th>Jumlah orang</th><th>Total</th><th>Status</th><th>Aksi</th></tr>
            </thead>
            <tbody id="tbody-booking">
              <?php foreach ($booking as $i => $b): ?>
              <tr>
                <td><?= $i + 1 ?></td>
                <td><?= htmlspecialchars($b[0]) ?></td>
                <td><?= $b[1] ?></td>
                <td><?= $b[2] ?></td>
                <td><?= rupiah($b[3]) ?></td>
                <td><span class="status <?= $b[5] ?>"><?= $b[4] ?></span></td>
                <td class="act"><button>Edit</button><button class="del">Hapus</button></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>

  <script src="js/modal.js"></script>
  <script src="js/dashboard.js"></script>
</body>
</html>