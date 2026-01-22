<?php
session_start();
require __DIR__ . '/koneksi.php';
require_once __DIR__ . '/fungsi.php';

// 1. Pastikan hanya akses POST yang diizinkan
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['flash_error1'] = 'Akses tidak valid.';
    redirect_ke('index.php#biodata');
}

/* =========================
   PROSES MAHASISWA (TAMBAH & UPDATE)
========================= */
// Mengecek apakah input berasal dari index.php (txtNim) atau edit_mahasigma.php (nim)
if (isset($_POST['txtNim']) || isset($_POST['nim'])) {

    // Konsolidasi input dari kedua jenis form
    $nim       = bersihkan($_POST['nim'] ?? $_POST['txtNim'] ?? '');
    $nama      = bersihkan($_POST['nama_lengkap'] ?? $_POST['txtNmLengkap'] ?? '');
    $tempat    = bersihkan($_POST['tempat_lahir'] ?? $_POST['txtT4Lhr'] ?? '');
    $tgl_input = bersihkan($_POST['tanggal_lahir'] ?? $_POST['txtTglLhr'] ?? '');
    $tanggal   = date('Y-m-d', strtotime($tgl_input));
    
    $hobi      = bersihkan($_POST['hobi'] ?? $_POST['txtHobi'] ?? '');
    $pasangan  = bersihkan($_POST['pasangan'] ?? $_POST['txtPasangan'] ?? '');
    $pekerjaan = bersihkan($_POST['pekerjaan'] ?? $_POST['txtKerja'] ?? '');
    $ortu      = bersihkan($_POST['nama_orang_tua'] ?? $_POST['txtNmOrtu'] ?? '');
    $kakak     = bersihkan($_POST['nama_kakak'] ?? $_POST['txtNmKakak'] ?? '');
    $adik      = bersihkan($_POST['nama_adik'] ?? $_POST['txtNmAdik'] ?? '');

    // Simpan input lama jika terjadi kesalahan validasi
    $_SESSION['old1'] = $_POST;

    if ($nim === '' || $nama === '') {
        $_SESSION['flash_error1'] = "NIM dan Nama Lengkap wajib diisi!";
        // Jika dari halaman edit, kembali ke edit. Jika tidak, ke index.
        $url = isset($_POST['nim']) ? "edit_mahasigma.php?nim=$nim" : "index.php#biodata";
        redirect_ke($url);
    }

    // Menggunakan INSERT ... ON DUPLICATE KEY UPDATE untuk menangani Tambah sekaligus Edit
    // Nama kolom disesuaikan dengan struktur database (lowercase)
    $sql = "INSERT INTO iyupp 
            (nim, nama_lengkap, tempat_lahir, tanggal_lahir, hobi, pasangan, pekerjaan, nama_orang_tua, nama_kakak, nama_adik)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
              nama_lengkap = VALUES(nama_lengkap),
              tempat_lahir = VALUES(tempat_lahir),
              tanggal_lahir = VALUES(tanggal_lahir),
              hobi = VALUES(hobi),
              pasangan = VALUES(pasangan),
              pekerjaan = VALUES(pekerjaan),
              nama_orang_tua = VALUES(nama_orang_tua),
              nama_kakak = VALUES(nama_kakak),
              nama_adik = VALUES(nama_adik)";

    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        $_SESSION['flash_error1'] = "Gagal menyiapkan perintah: " . mysqli_error($conn);
        redirect_ke("read_iyup.php");
    }

    mysqli_stmt_bind_param($stmt, "ssssssssss", $nim, $nama, $tempat, $tanggal, $hobi, $pasangan, $pekerjaan, $ortu, $kakak, $adik);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_sukses1'] = "Data mahasiswa berhasil disimpan/diperbarui.";
        unset($_SESSION['old1']);
        // Setelah sukses, arahkan ke daftar mahasiswa
        redirect_ke("index.php#biodata");
    } else {
        $_SESSION['flash_error1'] = "Gagal memproses data: " . mysqli_stmt_error($stmt);
        redirect_ke("index.php#biodata");
    }

    mysqli_stmt_close($stmt);
    exit;
}

/* =========================
   PROSES KONTAK (BUKU TAMU)
========================= */
if (isset($_POST['txtNama'])) {
    $nama    = bersihkan($_POST['txtNama']  ?? '');
    $email   = bersihkan($_POST['txtEmail'] ?? '');
    $pesan   = bersihkan($_POST['txtPesan'] ?? '');
    $captcha = bersihkan($_POST['txtCaptcha'] ?? '');

    if ($captcha !== "5") {
        $_SESSION['flash_error'] = 'Jawaban captcha salah.';
        $_SESSION['old'] = $_POST;
        redirect_ke("index.php#contact");
    }

    $sql = "INSERT INTO tbl_tamu (cnama, cemail, cpesan) VALUES (?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nama, $email, $pesan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['flash_sukses'] = "Pesan berhasil dikirim.";
    } else {
        $_SESSION['flash_error'] = "Gagal mengirim pesan.";
    }

    mysqli_stmt_close($stmt);
    redirect_ke("index.php#contact");
}