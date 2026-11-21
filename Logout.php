<?php
// BAB 7 : logout -> hapus SESSION
session_start();
session_unset();
session_destroy();
echo "<script>
  alert('Berhasil logout');
  window.location = 'login.php';
</script>";