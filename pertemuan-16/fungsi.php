<?php
// Fungsi helper untuk aplikasi

// Redirect ke halaman lain
function arahkan($halaman) {
  header("Location: " . $halaman);
  exit();
}

// Membersihkan input dari karakter berbahaya
function bersihkanInput($data) {
  return htmlspecialchars(trim($data));
}

// Cek apakah string tidak kosong
function inputValid($data) {
  return strlen(trim($data)) > 0;
}

// Format tanggal ke Indonesia
function formatWaktu($waktu) {
  return date("d M Y H:i:s", strtotime($waktu));
}

// Tampilkan data dalam format paragraf
function cetakData($konfigurasi, $data) {
  $hasil = "";
  foreach ($konfigurasi as $kunci => $nilai) {
    $label = $nilai["label"];
    $isi = bersihkanInput($data[$kunci] ?? '');
    $akhiran = $nilai["suffix"];
    $hasil .= "<p><strong>{$label}</strong> {$isi}{$akhiran}</p>";
  }
  return $hasil;
}
