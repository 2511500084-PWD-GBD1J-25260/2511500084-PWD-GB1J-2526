<?php
/**
 * hapus_biodata.php
 * File untuk menghapus data biodata pengunjung
 */

session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

// Validasi ID dari parameter GET
$idData = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
]);

if (!$idData) {
  $_SESSION['pesan_error'] = 'ID tidak valid.';
  arahkan('daftar_biodata.php');
}

// Query DELETE dengan prepared statement
$queryDelete = "DELETE FROM data_pengunjung WHERE pid = ?";
$stmt = mysqli_prepare($koneksi, $queryDelete);

if (!$stmt) {
  $_SESSION['pesan_error'] = 'Terjadi kesalahan sistem.';
  arahkan('daftar_biodata.php');
}

// Bind parameter dan eksekusi
mysqli_stmt_bind_param($stmt, "i", $idData);

if (mysqli_stmt_execute($stmt)) {
  $_SESSION['pesan_sukses'] = 'Data biodata berhasil dihapus!';
} else {
  $_SESSION['pesan_error'] = 'Gagal menghapus data. Coba lagi.';
}

mysqli_stmt_close($stmt);

// Redirect ke halaman daftar
arahkan('daftar_biodata.php');
