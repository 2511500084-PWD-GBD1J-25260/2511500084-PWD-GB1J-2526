<?php
session_start();
require_once __DIR__ . '/fungsi.php';

// Mengambil flash message dan old value untuk form biodata
$pesanSukses = $_SESSION['pesan_sukses'] ?? '';
$pesanError  = $_SESSION['pesan_error'] ?? '';
$dataLama    = $_SESSION['data_lama'] ?? [];
unset($_SESSION['pesan_sukses'], $_SESSION['pesan_error'], $_SESSION['data_lama']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Form Biodata Pengunjung</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <header>
    <h1>Aplikasi Biodata</h1>
    <button class="menu-toggle" id="menuToggle">&#9776;</button>
    <nav>
      <ul>
        <li><a href="#beranda">Beranda</a></li>
        <li><a href="#form-biodata">Form Biodata</a></li>
        <li><a href="daftar_biodata.php">Lihat Data</a></li>
      </ul>
    </nav>
  </header>

  <main>
    <section id="beranda" class="container">
      <h2>Selamat Datang</h2>
      <p>Silakan isi form biodata di bawah ini untuk mendaftar sebagai pengunjung.</p>
    </section>

    <section id="form-biodata" class="container">
      <h2>Form Biodata Pengunjung</h2>

      <?php if (!empty($pesanSukses)): ?>
        <div class="alert alert-success"><?= $pesanSukses; ?></div>
      <?php endif; ?>

      <?php if (!empty($pesanError)): ?>
        <div class="alert alert-error"><?= $pesanError; ?></div>
      <?php endif; ?>

      <form action="simpan_biodata.php" method="POST">
        
        <div class="form-group">
          <label for="inputNim">NIM</label>
          <input type="text" id="inputNim" name="inputNim" placeholder="Masukkan NIM" required
            value="<?= isset($dataLama['nim']) ? bersihkanInput($dataLama['nim']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputNama">Nama Lengkap</label>
          <input type="text" id="inputNama" name="inputNama" placeholder="Masukkan Nama Lengkap" required
            value="<?= isset($dataLama['nama']) ? bersihkanInput($dataLama['nama']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputTempat">Tempat Lahir</label>
          <input type="text" id="inputTempat" name="inputTempat" placeholder="Masukkan Tempat Lahir" required
            value="<?= isset($dataLama['tempat']) ? bersihkanInput($dataLama['tempat']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputTanggal">Tanggal Lahir</label>
          <input type="text" id="inputTanggal" name="inputTanggal" placeholder="Masukkan Tanggal Lahir" required
            value="<?= isset($dataLama['tanggal']) ? bersihkanInput($dataLama['tanggal']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputHobi">Hobi</label>
          <input type="text" id="inputHobi" name="inputHobi" placeholder="Masukkan Hobi" required
            value="<?= isset($dataLama['hobi']) ? bersihkanInput($dataLama['hobi']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputPasangan">Pasangan</label>
          <input type="text" id="inputPasangan" name="inputPasangan" placeholder="Masukkan Nama Pasangan" required
            value="<?= isset($dataLama['pasangan']) ? bersihkanInput($dataLama['pasangan']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputPekerjaan">Pekerjaan</label>
          <input type="text" id="inputPekerjaan" name="inputPekerjaan" placeholder="Masukkan Pekerjaan" required
            value="<?= isset($dataLama['pekerjaan']) ? bersihkanInput($dataLama['pekerjaan']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputOrtu">Nama Orang Tua</label>
          <input type="text" id="inputOrtu" name="inputOrtu" placeholder="Masukkan Nama Orang Tua" required
            value="<?= isset($dataLama['ortu']) ? bersihkanInput($dataLama['ortu']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputKakak">Nama Kakak</label>
          <input type="text" id="inputKakak" name="inputKakak" placeholder="Masukkan Nama Kakak" required
            value="<?= isset($dataLama['kakak']) ? bersihkanInput($dataLama['kakak']) : '' ?>">
        </div>

        <div class="form-group">
          <label for="inputAdik">Nama Adik</label>
          <input type="text" id="inputAdik" name="inputAdik" placeholder="Masukkan Nama Adik" required
            value="<?= isset($dataLama['adik']) ? bersihkanInput($dataLama['adik']) : '' ?>">
        </div>

        <div>
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="reset" class="btn btn-secondary">Reset</button>
          <a href="daftar_biodata.php" class="btn btn-info">Lihat Data</a>
        </div>
      </form>
    </section>
  </main>

  <footer>
    <p>&copy; 2025 Salsabillah Rimadany</p>
  </footer>

  <script src="script.js"></script>
</body>

</html>
