/* ==========================================================
   Nusantrip - BAB 3 JavaScript
   ========================================================== */

/* ---------- TOAST / SNACKBAR ----------
   Dibuat murni dengan DOM (createElement), tanpa framework.
   Pemakaian: showToast("Pesan", "success" | "info" | "error")   */
function showToast(pesan, tipe = "info", durasi = 3000) {
  // Cari wadah toast, kalau belum ada buat dulu (DOM: createElement + append)
  let wadah = document.getElementById("toast-container");
  if (!wadah) {
    wadah = document.createElement("div");
    wadah.id = "toast-container";
    document.body.append(wadah);
  }

  const toast = document.createElement("div");
  toast.className = "toast toast-" + tipe;
  toast.setAttribute("role", "status");

  const teks = document.createElement("span");
  teks.textContent = pesan;

  const tutup = document.createElement("button");
  tutup.className = "toast-close";
  tutup.setAttribute("aria-label", "Tutup");
  tutup.textContent = "×";

  toast.append(teks, tutup);
  wadah.append(toast);

  // Munculkan dengan animasi (tambah class setelah elemen masuk ke halaman)
  requestAnimationFrame(() => toast.classList.add("show"));

  function hilangkan() {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300); // hapus elemen setelah animasi selesai
  }
  tutup.addEventListener("click", hilangkan);
  setTimeout(hilangkan, durasi);
}

/* ---------- DOM 1: Tombol favorit (querySelectorAll + classList + setAttribute) ---------- */
const tombolFav = document.querySelectorAll(".fav");
let jumlahFav = 0;

tombolFav.forEach(function (tombol) {
  tombol.addEventListener("click", function () {
    const kartu = tombol.closest(".card");
    const nama = kartu.querySelector("h3").textContent;
    const aktif = tombol.classList.toggle("active");

    tombol.textContent = aktif ? "♥" : "♡";
    tombol.setAttribute("aria-pressed", aktif);

    jumlahFav += aktif ? 1 : -1;
    perbaruiBadge();

    if (aktif) {
      showToast(nama + " ditambahkan ke favorit", "success");
    } else {
      showToast(nama + " dihapus dari favorit", "info");
    }
  });
});

/* ---------- DOM 2: Badge jumlah favorit di navbar (createElement + textContent + style) ---------- */
const badge = document.createElement("span");
badge.id = "fav-badge";
badge.textContent = "0";
badge.style.display = "none";
document.querySelector(".nav-actions").prepend(badge);

function perbaruiBadge() {
  badge.textContent = "♥ " + jumlahFav;
  badge.style.display = jumlahFav > 0 ? "inline-block" : "none";
}

/* ---------- DOM 3: Bayangan navbar saat halaman di-scroll (querySelector + classList) ---------- */
const navbar = document.querySelector(".navbar");
window.addEventListener("scroll", function () {
  navbar.classList.toggle("scrolled", window.scrollY > 10);
});

/* ---------- DOM 4: Tahun otomatis di footer (getElementById + innerHTML) ---------- */
document.getElementById("tahun").innerHTML = new Date().getFullYear();

/* ---------- Toast sambutan saat halaman dibuka ---------- */
window.addEventListener("load", function () {
  showToast("Selamat datang di Nusantrip! 👋", "info", 3500);
});
