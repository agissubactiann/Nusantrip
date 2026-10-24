/* ==========================================================
   Nusantrip - BAB 4 : Interaksi Dashboard
   Popup : alert, confirm, prompt
   Event : click, keyup, dblclick, mouseover, mouseout
   Lainnya : Web Storage (localStorage), async/await + fetch
   ========================================================== */

// ---------- Toast sederhana (notifikasi kecil di pojok kanan bawah) ----------
function showToast(pesan, tipe = "info") {
  let wadah = document.getElementById("toast-container");
  if (!wadah) {
    wadah = document.createElement("div");
    wadah.id = "toast-container";
    document.body.append(wadah);
  }
  const toast = document.createElement("div");
  toast.className = "toast toast-" + tipe;
  toast.innerHTML = "<span></span>";
  toast.firstChild.textContent = pesan;
  wadah.append(toast);
  requestAnimationFrame(() => toast.classList.add("show"));
  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}

// ---------- WEB STORAGE : tampilkan nama pengguna yang disimpan saat login ----------
const sapaan = document.getElementById("sapaan");
const avatar = document.querySelector(".avatar");
const namaTopbar = document.querySelector(".user strong");

function tampilkanNama() {
  const nama = localStorage.getItem("namaUser") || "Agis"; // default jika belum login
  sapaan.textContent = "Halo, " + nama + "! 👋";
  namaTopbar.textContent = nama;
  avatar.textContent = nama.charAt(0).toUpperCase();
}
tampilkanNama();

// EVENT dblclick : klik 2x pada avatar untuk mengganti nama
avatar.addEventListener("dblclick", function () {
  const baru = prompt("Masukkan nama baru:", localStorage.getItem("namaUser") || "Agis"); // POPUP: Prompt Box
  if (baru && baru.trim() !== "") {
    localStorage.setItem("namaUser", baru.trim());
    tampilkanNama();
    showToast("Nama berhasil diubah", "success");
  }
});

// ---------- Edit & Hapus data (event delegation pada tombol di tabel) ----------
function nomori() {
  // rapikan ulang nomor urut setelah tambah / hapus
  document.querySelectorAll("tbody").forEach(function (tb) {
    Array.from(tb.rows).forEach((baris, i) => (baris.cells[0].textContent = i + 1));
  });
}

// EVENT click : satu listener untuk semua tombol Edit & Hapus
document.addEventListener("click", function (e) {
  const tombol = e.target.closest(".act button");
  if (!tombol) return;
  const baris = tombol.closest("tr");
  const nama = baris.cells[1].textContent;

  if (tombol.classList.contains("del")) {
    if (confirm('Yakin ingin menghapus "' + nama + '"?')) {
      // POPUP: Confirm Box
      baris.remove();
      nomori();
      showToast('"' + nama + '" dihapus', "error");
    }
  } else {
    // Edit memakai POPUP MODAL (nilai awal = nama sekarang)
    bukaModal({
      judul: "Edit Data",
      fields: [{ id: "nama", label: "Nama", type: "text", nilai: nama }],
      onSubmit: function (d) {
        baris.cells[1].textContent = d.nama;
        showToast("Data berhasil diubah", "success");
      },
    });
  }
});

// ---------- Tambah data ----------
function tambahBaris(idTbody, kolomTengah, nama, info) {
  const tr = document.createElement("tr");
  tr.innerHTML = "<td></td><td></td><td></td>" + kolomTengah + '<td class="act"><button>Edit</button><button class="del">Hapus</button></td>';
  tr.cells[1].textContent = nama; // textContent agar input pengguna aman
  tr.cells[2].textContent = info;
  document.getElementById(idTbody).append(tr);
  nomori();
  showToast('"' + nama + '" ditambahkan', "success");
}

// Tambah destinasi memakai POPUP MODAL buatan sendiri (js/modal.js)
document.getElementById("tambah-destinasi").addEventListener("click", function () {
  bukaModal({
    judul: "Formulir Destinasi",
    fields: [
      { id: "nama", label: "Nama destinasi", type: "text" },
      { id: "lokasi", label: "Lokasi", type: "text" },
    ],
    onSubmit: function (d) {
      tambahBaris("tbody-destinasi", '<td><span class="pill">Baru</span></td><td>Rp0</td><td>★ -</td>', d.nama, d.lokasi);
    },
  });
});

// Tambah booking memakai POPUP MODAL buatan sendiri (js/modal.js)
document.getElementById("tambah-booking").addEventListener("click", function () {
  bukaModal({
    judul: "Formulir Booking",
    fields: [
      { id: "trip", label: "Nama trip", type: "text" },
      { id: "tanggal", label: "Tanggal berangkat", type: "date" },
    ],
    onSubmit: function (d) {
      const tgl = new Date(d.tanggal).toLocaleDateString("id-ID", { day: "2-digit", month: "short", year: "numeric" });
      tambahBaris("tbody-booking", '<td>1</td><td>Rp0</td><td><span class="status wait">Menunggu</span></td>', d.trip, tgl);
    },
  });
});

// ---------- EVENT keyup : cari data di kedua tabel ----------
document.getElementById("cari").addEventListener("keyup", function () {
  const kata = this.value.toLowerCase();
  document.querySelectorAll("tbody tr").forEach(function (baris) {
    baris.hidden = !baris.textContent.toLowerCase().includes(kata);
  });
});

// ---------- EVENT mouseover & mouseout : kartu ringkasan terangkat saat disentuh mouse ----------
document.querySelectorAll(".stat").forEach(function (kartu) {
  kartu.addEventListener("mouseover", () => (kartu.style.transform = "translateY(-4px)"));
  kartu.addEventListener("mouseout", () => (kartu.style.transform = "translateY(0)"));
});

// ---------- Konfirmasi saat keluar ----------
document.getElementById("logout").addEventListener("click", function (e) {
  if (!confirm("Yakin ingin keluar dari Nusantrip?")) {
    // POPUP: Confirm Box
    e.preventDefault(); // batal pindah halaman
  } else {
    localStorage.removeItem("namaUser"); // hapus data tersimpan
  }
});

// ---------- ASYNC / AWAIT + FETCH (Promise) : ambil tips perjalanan dari file JSON ----------
async function muatTips() {
  const el = document.getElementById("tips");
  try {
    const res = await fetch("data/tips.json"); // fetch mengembalikan Promise
    if (!res.ok) throw new Error("Gagal memuat: " + res.status);
    const data = await res.json();
    el.textContent = "💡 " + data[Math.floor(Math.random() * data.length)];
  } catch (err) {
    el.textContent = "Tips perjalanan belum dapat dimuat.";
    console.error(err);
  }
}
muatTips();
