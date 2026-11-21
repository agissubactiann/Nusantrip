/* ==========================================================
   Nusantrip - BAB 4 : Validasi form Login & Register
   Dipakai di login.php dan register.php
   ========================================================== */

const form = document.querySelector("form");

// EVENT submit : dijalankan saat tombol Masuk / Daftar ditekan
form.addEventListener("submit", function (e) {
  const pass2 = document.getElementById("pass2"); // hanya ada di register
  const nama = document.getElementById("nama"); // hanya ada di register

  // Validasi: konfirmasi sandi harus sama
  if (pass2 && document.getElementById("pass").value !== pass2.value) {
    e.preventDefault(); // batalkan pengiriman form
    alert("Konfirmasi kata sandi tidak sama!"); // POPUP 1: Alert Box
    return;
  }

  // BAB 7 : sisanya (cek email/sandi, simpan SESSION) dikerjakan oleh PHP di server
});
