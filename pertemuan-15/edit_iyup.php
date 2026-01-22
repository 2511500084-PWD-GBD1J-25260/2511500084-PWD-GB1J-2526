<?php
  session_start();
  require 'koneksi.php';
  require 'fungsi.php';

  /* 1. VALIDASI INPUT NIM 
    Menggunakan filter_input untuk keamanan. 
    NIM biasanya string, jadi kita gunakan FILTER_SANITIZE_SPECIAL_CHARS.
  */
  $nim = filter_input(INPUT_GET, 'nim', FILTER_SANITIZE_SPECIAL_CHARS);

  if (!$nim) {
    $_SESSION['flash_error1'] = 'Akses tidak valid. NIM tidak ditemukan.';
    redirect_ke('read_iyup.php');
  }

  /* 2. AMBIL DATA DARI DATABASE (PREPARED STATEMENT)
  */
  $stmt = mysqli_prepare($conn, "SELECT * FROM iyupp WHERE nim = ? LIMIT 1");
  if (!$stmt) {
    $_SESSION['flash_error1'] = 'Kesalahan sistem pada query.';
    redirect_ke('read_iyup.php');
  }

  mysqli_stmt_bind_param($stmt, "s", $nim);
  mysqli_stmt_execute($stmt);
  $res = mysqli_stmt_get_result($stmt);
  $data = mysqli_fetch_assoc($res);
  mysqli_stmt_close($stmt);

  if (!$data) {
    $_SESSION['flash_error1'] = 'Data mahasiswa tidak ditemukan.';
    redirect_ke('read_iyup.php');
  }

  /* 3. PENANGANAN PRE-FILL (Isi otomatis form)
    Mengambil data dari DB atau dari input lama (old input) jika ada error saat submit.
  */
  $flash_error1 = $_SESSION['flash_error1'] ?? '';
  $old1 = $_SESSION['old1'] ?? [];
  unset($_SESSION['flash_error1'], $_SESSION['old1']);

  // Logika: Jika ada input lama (setelah gagal submit), pakai itu. Jika tidak, pakai data DB.
  $nama_lengkap    = $old['nama_lengkap'] ?? $data['nama_lengkap'];
  $tempat_lahir    = $old['tempat_lahir'] ?? $data['tempat_lahir'];
  $tanggal_lahir   = $old['tanggal_lahir'] ?? $data['tanggal_lahir'];
  $hobi            = $old['hobi'] ?? $data['hobi'];
  $pasangan        = $old['pasangan'] ?? $data['pasangan'];
  $pekerjaan       = $old['pekerjaan'] ?? $data['pekerjaan'];
  $nama_orang_tua  = $old['nama_orang_tua'] ?? $data['nama_orang_tua'];
  $nama_kakak      = $old['nama_kakak'] ?? $data['nama_kakak'];
  $nama_adik       = $old['nama_adik'] ?? $data['nama_adik'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Biodata Mahasiswa</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
      <h1>Sistem Akademik</h1>
      <button class="menu-toggle" id="menuToggle" aria-label="Toggle Navigation">&#9776;</button>
      <nav>
        <ul>
          <li><a href="read_iyup.php">Data Mahasiswa</a></li>
          <li><a href="#">Beranda</a></li>
        </ul>
      </nav>
    </header>

    <main>
      <section id="biodata">
        <h2>Edit Biodata Mahasiswa</h2>

        <?php if (!empty($flash_error1)): ?>
          <div style="padding:10px; margin-bottom:10px; background:#f8d7da; color:#721c24; border-radius:6px; border: 1px solid #f5c6cb;">
            <?= htmlspecialchars($flash_error1); ?>
          </div>
        <?php endif; ?>

        <form action="proses_iyupp.php" method="POST">
          
          <label><span>NIM (ID):</span>
            <input type="text" value="<?= htmlspecialchars($nim); ?>" disabled>
            <input type="hidden" name="nim" value="<?= htmlspecialchars($nim); ?>">
          </label>

          <label for="nama_lengkap"><span>Nama Lengkap:</span>
            <input type="text" id="nama_lengkap" name="nama_lengkap" required 
                   value="<?= htmlspecialchars($nama_lengkap); ?>">
          </label>

          <label for="tempat_lahir"><span>Tempat Lahir:</span>
            <input type="text" id="tempat_lahir" name="tempat_lahir" required 
                   value="<?= htmlspecialchars($tempat_lahir); ?>">
          </label>

          <label for="tanggal_lahir"><span>Tanggal Lahir:</span>
            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required 
                   value="<?= htmlspecialchars($tanggal_lahir); ?>">
          </label>

          <label for="hobi"><span>Hobi:</span>
            <input type="text" id="hobi" name="hobi" 
                   value="<?= htmlspecialchars($hobi); ?>">
          </label>

          <label for="pasangan"><span>Pasangan:</span>
            <input type="text" id="pasangan" name="pasangan" 
                   value="<?= htmlspecialchars($pasangan); ?>">
          </label>

          <label for="pekerjaan"><span>Pekerjaan:</span>
            <input type="text" id="pekerjaan" name="pekerjaan" 
                   value="<?= htmlspecialchars($pekerjaan); ?>">
          </label>

          <label for="nama_orang_tua"><span>Nama Orang Tua:</span>
            <input type="text" id="nama_orang_tua" name="nama_orang_tua" 
                   value="<?= htmlspecialchars($nama_orang_tua); ?>">
          </label>

          <label for="nama_kakak"><span>Nama Kakak:</span>
            <input type="text" id="nama_kakak" name="nama_kakak" 
                   value="<?= htmlspecialchars($nama_kakak); ?>">
          </label>

          <label for="nama_adik"><span>Nama Adik:</span>
            <input type="text" id="nama_adik" name="nama_adik" 
                   value="<?= htmlspecialchars($nama_adik); ?>">
          </label>

         
            <button type="submit">Update Data</button>
            <button type="reset">Reset</button>
            <a href="read_iyup.php" class="btn-back" style="text-decoration: none; padding: 10px; background: #eee; border-radius: 4px; color: #333;">Batal</a>
          

        </form>
      </section>
    </main>

    <script src="script.js"></script>
</body>
</html>