<?php
/**
 * update_biodata.php
 * File untuk memproses update data biodata pengunjung
 */

session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

// Validasi method request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  $_SESSION['pesan_error'] = 'Akses tidak diizinkan.';
  arahkan('daftar_biodata.php');
}

// Validasi ID
$idData = filter_input(INPUT_POST, 'idBiodata', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
]);

if (!$idData) {
  $_SESSION['pesan_error'] = 'ID tidak valid.';
  arahkan('daftar_biodata.php');
}

// Mengambil dan membersihkan data dari form
$inputNim      = bersihkanInput($_POST['inputNim'] ?? '');
$inputNama     = bersihkanInput($_POST['inputNama'] ?? '');
$inputTempat   = bersihkanInput($_POST['inputTempat'] ?? '');
$inputTanggal  = bersihkanInput($_POST['inputTanggal'] ?? '');
$inputHobi     = bersihkanInput($_POST['inputHobi'] ?? '');
$inputPasangan = bersihkanInput($_POST['inputPasangan'] ?? '');
$inputPekerjaan = bersihkanInput($_POST['inputPekerjaan'] ?? '');
$inputOrtu     = bersihkanInput($_POST['inputOrtu'] ?? '');
$inputKakak    = bersihkanInput($_POST['inputKakak'] ?? '');
$inputAdik     = bersihkanInput($_POST['inputAdik'] ?? '');

// Array untuk menampung pesan error
$daftarError = [];

// Validasi NIM
if ($inputNim === '') {
  $daftarError[] = 'NIM tidak boleh kosong.';
} elseif (!ctype_digit($inputNim)) {
  $daftarError[] = 'NIM harus berupa angka.';
} elseif (mb_strlen($inputNim) < 5) {
  $daftarError[] = 'NIM minimal 5 digit.';
}

// Validasi Nama
if ($inputNama === '') {
  $daftarError[] = 'Nama tidak boleh kosong.';
} elseif (mb_strlen($inputNama) < 3) {
  $daftarError[] = 'Nama minimal 3 huruf.';
}

// Validasi field lainnya
if ($inputTempat === '') $daftarError[] = 'Tempat Lahir tidak boleh kosong.';
if ($inputTanggal === '') $daftarError[] = 'Tanggal Lahir tidak boleh kosong.';
if ($inputHobi === '') $daftarError[] = 'Hobi tidak boleh kosong.';
if ($inputPasangan === '') $daftarError[] = 'Pasangan tidak boleh kosong.';
if ($inputPekerjaan === '') $daftarError[] = 'Pekerjaan tidak boleh kosong.';
if ($inputOrtu === '') $daftarError[] = 'Nama Orang Tua tidak boleh kosong.';
if ($inputKakak === '') $daftarError[] = 'Nama Kakak tidak boleh kosong.';
if ($inputAdik === '') $daftarError[] = 'Nama Adik tidak boleh kosong.';

// Jika ada error, simpan data lama dan redirect
if (!empty($daftarError)) {
  $_SESSION['data_lama'] = [
    'nim' => $inputNim, 'nama' => $inputNama, 'tempat' => $inputTempat,
    'tanggal' => $inputTanggal, 'hobi' => $inputHobi, 'pasangan' => $inputPasangan,
    'pekerjaan' => $inputPekerjaan, 'ortu' => $inputOrtu, 'kakak' => $inputKakak, 'adik' => $inputAdik
  ];
  $_SESSION['pesan_error'] = implode('<br>', $daftarError);
  arahkan('ubah_biodata.php?id=' . (int)$idData);
}

// Query UPDATE dengan prepared statement
$queryUpdate = "UPDATE data_pengunjung SET 
  pnim = ?, pnama = ?, ptempat = ?, ptanggal = ?, 
  phobi = ?, ppasangan = ?, ppekerjaan = ?, portu = ?, pkakak = ?, padik = ?
  WHERE pid = ?";

$stmt = mysqli_prepare($koneksi, $queryUpdate);

if (!$stmt) {
  $_SESSION['pesan_error'] = 'Terjadi kesalahan sistem.';
  arahkan('ubah_biodata.php?id=' . (int)$idData);
}

// Bind parameter
mysqli_stmt_bind_param($stmt, "ssssssssssi", 
  $inputNim, $inputNama, $inputTempat, $inputTanggal, 
  $inputHobi, $inputPasangan, $inputPekerjaan, $inputOrtu, $inputKakak, $inputAdik, $idData
);

// Eksekusi query
if (mysqli_stmt_execute($stmt)) {
  unset($_SESSION['data_lama']);
  $_SESSION['pesan_sukses'] = 'Data biodata berhasil diperbarui!';
  arahkan('daftar_biodata.php');
} else {
  $_SESSION['data_lama'] = [
    'nim' => $inputNim, 'nama' => $inputNama, 'tempat' => $inputTempat,
    'tanggal' => $inputTanggal, 'hobi' => $inputHobi, 'pasangan' => $inputPasangan,
    'pekerjaan' => $inputPekerjaan, 'ortu' => $inputOrtu, 'kakak' => $inputKakak, 'adik' => $inputAdik
  ];
  $_SESSION['pesan_error'] = 'Gagal memperbarui data. Coba lagi.';
  arahkan('ubah_biodata.php?id=' . (int)$idData);
}

mysqli_stmt_close($stmt);
