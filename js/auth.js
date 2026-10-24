/* ==========================================================
   Nusantrip - BAB 4 : Validasi form Login & Register
   Dipakai di login.html dan register.html
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

  // Ambil nama pengguna: dari kolom nama (register) atau dari email/username (login)
  const namaUser = nama ? nama.value : document.getElementById("user").value.split("@")[0];

  // WEB STORAGE : simpan nama agar bisa dipakai di halaman Dashboard
  localStorage.setItem("namaUser", namaUser.trim().split(" ")[0]);

  alert(nama ? "Akun berhasil dibuat! Selamat datang, " + namaUser + "." : "Login berhasil! Selamat datang kembali.");
});
