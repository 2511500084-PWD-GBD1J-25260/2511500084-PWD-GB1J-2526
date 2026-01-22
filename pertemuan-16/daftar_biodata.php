<?php
/**
 * daftar_biodata.php
 * File untuk menampilkan semua data biodata pengunjung
 */

session_start();
require 'koneksi.php';
require 'fungsi.php';

// Query mengambil semua data
$querySelect = "SELECT * FROM data_pengunjung ORDER BY pid DESC";
$hasil = mysqli_query($koneksi, $querySelect);

if (!$hasil) {
  die("Query error: " . mysqli_error($koneksi));
}

// Mengambil flash message
$pesanSukses = $_SESSION['pesan_sukses'] ?? '';
$pesanError  = $_SESSION['pesan_error'] ?? '';
unset($_SESSION['pesan_sukses'], $_SESSION['pesan_error']);
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Biodata Pengunjung</title>
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
      <h2>Daftar Biodata Pengunjung</h2>

      <?php if (!empty($pesanSukses)): ?>
        <div class="alert alert-success"><?= $pesanSukses; ?></div>
      <?php endif; ?>

      <?php if (!empty($pesanError)): ?>
        <div class="alert alert-error"><?= $pesanError; ?></div>
      <?php endif; ?>

      <table>
        <thead>
          <tr>
            <th>No</th>
            <th>Aksi</th>
            <th>ID</th>
            <th>NIM</th>
            <th>Nama</th>
            <th>Tempat Lahir</th>
            <th>Tanggal Lahir</th>
            <th>Hobi</th>
            <th>Dibuat</th>
          </tr>
        </thead>
        <tbody>
          <?php $nomor = 1; ?>
          <?php if (mysqli_num_rows($hasil) === 0): ?>
            <tr>
              <td colspan="9" style="text-align:center;">Belum ada data.</td>
            </tr>
          <?php else: ?>
            <?php while ($baris = mysqli_fetch_assoc($hasil)): ?>
              <tr>
                <td><?= $nomor++; ?></td>
                <td>
                  <a class="btn btn-info" href="ubah_biodata.php?id=<?= (int)$baris['pid']; ?>">Edit</a>
                  <a class="btn btn-danger" 
                     onclick="return confirm('Yakin hapus data <?= bersihkanInput($baris['pnama']); ?>?')" 
                     href="hapus_biodata.php?id=<?= (int)$baris['pid']; ?>">Hapus</a>
                </td>
                <td><?= $baris['pid']; ?></td>
                <td><?= bersihkanInput($baris['pnim']); ?></td>
                <td><?= bersihkanInput($baris['pnama']); ?></td>
                <td><?= bersihkanInput($baris['ptempat']); ?></td>
                <td><?= bersihkanInput($baris['ptanggal']); ?></td>
                <td><?= bersihkanInput($baris['phobi']); ?></td>
                <td><?= formatWaktu($baris['created_at']); ?></td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>

      <a href="index.php#form-biodata" class="btn btn-primary" style="margin-top:20px;">Tambah Data Baru</a>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Yohanes Setiawan Japriadi [0344300002]</p>
  </footer>

  <script src="script.js"></script>
</body>

</html>
