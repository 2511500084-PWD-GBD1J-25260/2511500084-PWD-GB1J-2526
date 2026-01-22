<?php
/**
 * ubah_biodata.php
 * File untuk menampilkan form edit biodata pengunjung
 */

session_start();
require 'koneksi.php';
require 'fungsi.php';

// Validasi ID dari parameter GET
$idData = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
  'options' => ['min_range' => 1]
]);

if (!$idData) {
  $_SESSION['pesan_error'] = 'ID tidak valid.';
  arahkan('daftar_biodata.php');
}

// Query mengambil data berdasarkan ID
$stmt = mysqli_prepare($koneksi, "SELECT * FROM data_pengunjung WHERE pid = ? LIMIT 1");
if (!$stmt) {
  $_SESSION['pesan_error'] = 'Terjadi kesalahan query.';
  arahkan('daftar_biodata.php');
}

mysqli_stmt_bind_param($stmt, "i", $idData);
mysqli_stmt_execute($stmt);
$hasilQuery = mysqli_stmt_get_result($stmt);
$dataBiodata = mysqli_fetch_assoc($hasilQuery);
mysqli_stmt_close($stmt);

if (!$dataBiodata) {
  $_SESSION['pesan_error'] = 'Data tidak ditemukan.';
  arahkan('daftar_biodata.php');
}

// Nilai awal dari database
$nilaiNim      = $dataBiodata['pnim'] ?? '';
$nilaiNama     = $dataBiodata['pnama'] ?? '';
$nilaiTempat   = $dataBiodata['ptempat'] ?? '';
$nilaiTanggal  = $dataBiodata['ptanggal'] ?? '';
$nilaiHobi     = $dataBiodata['phobi'] ?? '';
$nilaiPasangan = $dataBiodata['ppasangan'] ?? '';
$nilaiPekerjaan = $dataBiodata['ppekerjaan'] ?? '';
$nilaiOrtu     = $dataBiodata['portu'] ?? '';
$nilaiKakak    = $dataBiodata['pkakak'] ?? '';
$nilaiAdik     = $dataBiodata['padik'] ?? '';

// Cek apakah ada data lama dari validasi gagal
$pesanError = $_SESSION['pesan_error'] ?? '';
$dataLama = $_SESSION['data_lama'] ?? [];
unset($_SESSION['pesan_error'], $_SESSION['data_lama']);

if (!empty($dataLama)) {
  $nilaiNim      = $dataLama['nim'] ?? $nilaiNim;
  $nilaiNama     = $dataLama['nama'] ?? $nilaiNama;
  $nilaiTempat   = $dataLama['tempat'] ?? $nilaiTempat;
  $nilaiTanggal  = $dataLama['tanggal'] ?? $nilaiTanggal;
  $nilaiHobi     = $dataLama['hobi'] ?? $nilaiHobi;
  $nilaiPasangan = $dataLama['pasangan'] ?? $nilaiPasangan;
  $nilaiPekerjaan = $dataLama['pekerjaan'] ?? $nilaiPekerjaan;
  $nilaiOrtu     = $dataLama['ortu'] ?? $nilaiOrtu;
  $nilaiKakak    = $dataLama['kakak'] ?? $nilaiKakak;
  $nilaiAdik     = $dataLama['adik'] ?? $nilaiAdik;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Biodata Pengunjung</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
    <h1>Aplikasi Biodata</h1>
    <button class="menu-toggle" id="menuToggle">&#9776;</button>
    <nav>
      <ul>
        <li><a href="index.php">Beranda</a></li>
        <li><a href="index.php#form-biodata">Form Biodata</a></li>
        <li><a href="daftar_biodata.php">Lihat Data</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <div class="container">
      <h2>Ubah Biodata Pengunjung</h2>

      <?php if (!empty($pesanError)): ?>
        <div class="alert alert-error"><?= $pesanError; ?></div>
      <?php endif; ?>

      <form action="update_biodata.php" method="POST">
        
        <div class="form-group">
          <label for="idBiodata">ID Biodata</label>
          <input type="text" id="idBiodata" name="idBiodata" 
            value="<?= (int)$idData; ?>" readonly>
        </div>

        <div class="form-group">
          <label for="inputNim">NIM</label>
          <input type="text" id="inputNim" name="inputNim" placeholder="Masukkan NIM" required
            value="<?= bersihkanInput($nilaiNim); ?>">
        </div>

        <div class="form-group">
          <label for="inputNama">Nama Lengkap</label>
          <input type="text" id="inputNama" name="inputNama" placeholder="Masukkan Nama" required
            value="<?= bersihkanInput($nilaiNama); ?>">
        </div>

        <div class="form-group">
          <label for="inputTempat">Tempat Lahir</label>
          <input type="text" id="inputTempat" name="inputTempat" placeholder="Masukkan Tempat Lahir" required
            value="<?= bersihkanInput($nilaiTempat); ?>">
        </div>

        <div class="form-group">
          <label for="inputTanggal">Tanggal Lahir</label>
          <input type="text" id="inputTanggal" name="inputTanggal" placeholder="Masukkan Tanggal Lahir" required
            value="<?= bersihkanInput($nilaiTanggal); ?>">
        </div>

        <div class="form-group">
          <label for="inputHobi">Hobi</label>
          <input type="text" id="inputHobi" name="inputHobi" placeholder="Masukkan Hobi" required
            value="<?= bersihkanInput($nilaiHobi); ?>">
        </div>

        <div class="form-group">
          <label for="inputPasangan">Pasangan</label>
          <input type="text" id="inputPasangan" name="inputPasangan" placeholder="Masukkan Pasangan" required
            value="<?= bersihkanInput($nilaiPasangan); ?>">
        </div>

        <div class="form-group">
          <label for="inputPekerjaan">Pekerjaan</label>
          <input type="text" id="inputPekerjaan" name="inputPekerjaan" placeholder="Masukkan Pekerjaan" required
            value="<?= bersihkanInput($nilaiPekerjaan); ?>">
        </div>

        <div class="form-group">
          <label for="inputOrtu">Nama Orang Tua</label>
          <input type="text" id="inputOrtu" name="inputOrtu" placeholder="Masukkan Nama Orang Tua" required
            value="<?= bersihkanInput($nilaiOrtu); ?>">
        </div>

        <div class="form-group">
          <label for="inputKakak">Nama Kakak</label>
          <input type="text" id="inputKakak" name="inputKakak" placeholder="Masukkan Nama Kakak" required
            value="<?= bersihkanInput($nilaiKakak); ?>">
        </div>

        <div class="form-group">
          <label for="inputAdik">Nama Adik</label>
          <input type="text" id="inputAdik" name="inputAdik" placeholder="Masukkan Nama Adik" required
            value="<?= bersihkanInput($nilaiAdik); ?>">
        </div>

        <div>
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
          <a href="daftar_biodata.php" class="btn btn-info">Kembali</a>
        </div>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Yohanes Setiawan Japriadi [0344300002]</p>
  </footer>

  <script src="script.js"></script>
</body>

</html>
