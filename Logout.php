<?php
// BAB 5 : logout -> hapus semua SESSION lalu kembali ke beranda
session_start();
session_unset();     // hapus semua variabel session
session_destroy();   // hancurkan session
header('Location: index.php');
exit;