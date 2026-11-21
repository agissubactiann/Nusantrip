<?php
// BAB 7 : koneksi ke database db_nusantrip
$servername = 'localhost';
$username   = 'root';
$password   = '';              // Laragon default: kosong
$database   = 'db_nusantrip';

$koneksi = mysqli_connect($servername, $username, $password, $database);
if (!$koneksi) {
  die('Koneksi gagal: ' . mysqli_connect_error());
}

// Fungsi bantu: amankan teks sebelum ditampilkan ke HTML
function e($teks) {
  return htmlspecialchars($teks ?? '', ENT_QUOTES, 'UTF-8');
}

function rupiah($angka) {
  return 'Rp' . number_format((float) $angka, 0, ',', '.');
}