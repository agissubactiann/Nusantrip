/* ==========================================================
   Nusantrip - BAB 4 : Popup Modal (form pop-up buatan sendiri)
   bukaModal({ judul, fields, onSubmit }) dipakai di Index & Dashboard
   ========================================================== */

function bukaModal(opsi) {
  // Lapisan gelap di belakang popup
  const overlay = document.createElement("div");
  overlay.className = "modal-overlay";

  // Kotak popup berupa <form> supaya validasi "required" bawaan HTML ikut jalan
  const kotak = document.createElement("form");
  kotak.className = "modal-box";

  const judul = document.createElement("h2");
  judul.textContent = opsi.judul;
  kotak.append(judul);

  // Buat setiap kolom isian dari array fields
  opsi.fields.forEach(function (f) {
    const label = document.createElement("label");
    label.textContent = f.label + " :";
    label.htmlFor = "modal-" + f.id;

    const input = document.createElement(f.type === "textarea" ? "textarea" : "input");
    input.id = "modal-" + f.id;
    if (f.type !== "textarea") input.type = f.type;
    input.required = true;
    if (f.type === "textarea") input.rows = 3;
    if (f.nilai) input.value = f.nilai; // nilai awal (dipakai form Edit)

    kotak.append(label, input);
  });

  // Tombol Keluar & Lanjutkan
  const aksi = document.createElement("div");
  aksi.className = "modal-aksi";
  aksi.innerHTML = '<button type="button" class="btn-keluar">Keluar</button>' + '<button type="submit" class="btn-lanjut">Lanjutkan</button>';
  kotak.append(aksi);

  overlay.append(kotak);
  document.body.append(overlay);
  requestAnimationFrame(() => overlay.classList.add("show"));
  kotak.querySelector("input, textarea").focus();

  function tutup() {
    overlay.classList.remove("show");
    document.removeEventListener("keydown", tekanEsc);
    setTimeout(() => overlay.remove(), 250);
  }
  function tekanEsc(e) {
    if (e.key === "Escape") tutup();
  } // EVENT keydown

  // EVENT click : tombol Keluar & klik area gelap menutup popup
  aksi.querySelector(".btn-keluar").addEventListener("click", tutup);
  overlay.addEventListener("click", function (e) {
    if (e.target === overlay) tutup();
  });
  document.addEventListener("keydown", tekanEsc);

  // EVENT submit : ambil semua isian lalu kirim ke fungsi onSubmit
  kotak.addEventListener("submit", function (e) {
    e.preventDefault();
    const data = {};
    opsi.fields.forEach((f) => (data[f.id] = document.getElementById("modal-" + f.id).value.trim()));
    tutup();
    opsi.onSubmit(data);
  });
}

// ---------- Index: klik tombol ↗ pada card destinasi membuka form pemesanan ----------
document.querySelectorAll(".card .go").forEach(function (tombol) {
  tombol.addEventListener("click", function (e) {
    e.preventDefault();
    const tujuan = tombol.closest(".card").querySelector("h3").textContent;
    bukaModal({
      judul: "Formulir Pemesanan: " + tujuan,
      fields: [
        { id: "nama", label: "Nama", type: "text" },
        { id: "hp", label: "No. HP", type: "tel" },
        { id: "alamat", label: "Alamat", type: "textarea" },
      ],
      onSubmit: function (d) {
        const pesan = "Pesanan " + tujuan + " atas nama " + d.nama + " berhasil dicatat!";
        if (typeof showToast === "function") showToast(pesan, "success");
        else alert(pesan);
      },
    });
  });
});
