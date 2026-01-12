<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error1'] = 'Akses ditolak.';
    redirect_ke('read new.php');
}

$nim            = bersihkan($_POST['nim'] ?? '');
$nama_lengkap   = bersihkan($_POST['nama_lengkap'] ?? '');
$tempat_lahir   = bersihkan($_POST['tempat_lahir'] ?? '');
$tanggal_lahir  = bersihkan($_POST['tanggal_lahir'] ?? '');
$hobi           = bersihkan($_POST['hobi'] ?? '');
$pasangan       = bersihkan($_POST['pasangan'] ?? '');
$pekerjaan      = bersihkan($_POST['pekerjaan'] ?? '');
$nama_orang_tua = bersihkan($_POST['nama_orang_tua'] ?? '');
$nama_kakak     = bersihkan($_POST['nama_kakak'] ?? '');
$nama_adik      = bersihkan($_POST['nama_adik'] ?? '');

$errors = [];
if ($nama_lengkap === '') $errors[] = "Nama Lengkap wajib diisi.";
if ($tempat_lahir === '') $errors[] = "Tempat Lahir wajib diisi.";
if ($tanggal_lahir === '') $errors[] = "Tanggal Lahir wajib diisi.";

if (!empty($errors)) {
    $_SESSION['old1'] = $_POST;
    $_SESSION['flash_error1'] = implode('<br>', $errors);
    redirect_ke("proses new.php?nim=$nim");
}

$sql = "UPDATE mahasiswa SET 
        nama_lengkap=?, tempat_lahir=?, tanggal_lahir=?,
        hobi=?, pasangan=?, pekerjaan=?,
        nama_orang_tua=?, nama_kakak=?, nama_adik=?
        WHERE nim=?";

$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $_SESSION['flash_error1'] = "Kesalahan sistem.";
    redirect_ke("proses new.php?nim=$nim");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssss",
    $nama_lengkap, $tempat_lahir, $tanggal_lahir,
    $hobi, $pasangan, $pekerjaan,
    $nama_orang_tua, $nama_kakak, $nama_adik, $nim
);

if (mysqli_stmt_execute($stmt)) {
    unset($_SESSION['old1']);
    $_SESSION['flash_sukses1'] = "Data mahasiswa $nim berhasil diperbarui.";
    redirect_ke('read new.php');
} else {
    $_SESSION['flash_error1'] = "Gagal memperbarui data.";
    redirect_ke("proses new.php?nim=$nim");
}