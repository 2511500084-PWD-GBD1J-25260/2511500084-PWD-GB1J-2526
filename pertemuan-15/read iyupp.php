<?php 
// 1. Tambahkan session_start di paling atas untuk menampilkan pesan sukses/gagal
session_start(); 
include 'koneksi.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Biodata Mahasiswa</title>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
        }
        th {
            background-color: #f2f2f2;
            text-align: center;
            padding: 10px;
            border: 1px solid #333;
        }
        td {
            border: 1px solid #333;
            padding: 8px;
            text-align: center;
        }
        .link-aksi {
            text-decoration: none;
            color: blue;
            margin: 0 5px;
        }
        .link-delete {
            color: red;
        }
        .alert {
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
    </style>
</head>
<body>

    <h2>Daftar Biodata Mahasiswa</h2>

    <?php if(isset($_SESSION['flash_sukses1'])): ?>
        <div class="alert alert-success">
            <?php 
                echo $_SESSION['flash_sukses1']; 
                unset($_SESSION['flash_sukses1']); // Hapus pesan setelah ditampilkan
            ?>
        </div>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Aksi</th>
                <th>NIM</th>
                <th>Nama Lengkap</th>
                <th>Tempat Lahir</th>
                <th>Tanggal Lahir</th>
                <th>Hobi</th>
                <th>Pasangan</th>
                <th>Pekerjaan</th>
                <th>Nama Orang Tua</th>
                <th>Nama Kakak</th>
                <th>Nama Adik</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            $ambildata = mysqli_query($conn, "SELECT * FROM iyupp");
            
            while($d = mysqli_fetch_array($ambildata)){
                ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td>
                        <a class="link-aksi" href="proses new.php?nim=<?php echo $d['nim']; ?>">Edit</a> | 
                        
                        <a class="link-aksi link-delete" 
                           href="proses_delete.php?nim=<?php echo $d['nim']; ?>" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus data dengan NIM <?php echo $d['nim']; ?>?')">
                           Delete
                        </a>
                    </td>
                    <td><?php echo $d['nim']; ?></td>
                    <td><?php echo $d['nama_lengkap']; ?></td>
                    <td><?php echo $d['tempat_lahir']; ?></td>
                    <td><?php echo $d['tanggal_lahir']; ?></td>
                    <td><?php echo $d['hobi']; ?></td>
                    <td><?php echo $d['pasangan']; ?></td>
                    <td><?php echo $d['pekerjaan']; ?></td>
                    <td><?php echo $d['nama_orang_tua']; ?></td>
                    <td><?php echo $d['nama_kakak']; ?></td>
                    <td><?php echo $d['nama_adik']; ?></td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
 
</body>
</html>