  <?php
    session_start();
    require __DIR__ . '/koneksi.php';
    require_once __DIR__ . '/fungsi.php';

    #cek method form, hanya izinkan POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      $_SESSION['flash_error'] = 'Akses tidak valid.';
      redirect_ke('read.php');
    }

    #validasi cid wajib angka dan > 0
    $cid = filter_input(INPUT_POST, 'cid', FILTER_VALIDATE_INT, [
      'options' => ['min_range' => 1]
    ]);

    if (!$cid) {
      $_SESSION['flash_error'] = 'CID Tidak Valid.';
      redirect_ke('edit.php?cid='. (int)$cid);
    }

    #ambil dan bersihkan (sanitasi) nilai dari form
    $nama  = bersihkan($_POST['txtNamaEd']  ?? '');
    $email = bersihkan($_POST['txtEmailEd'] ?? '');
    $pesan = bersihkan($_POST['txtPesanEd'] ?? '');
    $captcha = bersihkan($_POST['txtCaptcha'] ?? '');

    #Validasi sederhana
    $errors = []; #ini array untuk menampung semua error yang ada

    if ($nama === '') {
      $errors[] = 'Nama wajib diisi.';
    }

    if ($email === '') {
      $errors[] = 'Email wajib diisi.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $errors[] = 'Format e-mail tidak valid.';
    }

    if ($pesan === '') {
      $errors[] = 'Pesan wajib diisi.';
    }

    if ($captcha === '') {
      $errors[] = 'Pertanyaan wajib diisi.';
    }

    if (mb_strlen($nama) < 3) {
      $errors[] = 'Nama minimal 3 karakter.';
    }

    if (mb_strlen($pesan) < 10) {
      $errors[] = 'Pesan minimal 10 karakter.';
    }

    if ($captcha!=="6") {
      $errors[] = 'Jawaban '. $captcha.' captcha salah.';
    }

    /*
    kondisi di bawah ini hanya dikerjakan jika ada error, 
    simpan nilai lama dan pesan error, lalu redirect (konsep PRG)
    */
    if (!empty($errors)) {
      $_SESSION['old'] = [
        'nama'  => $nama,
        'email' => $email,
        'pesan' => $pesan
      ];

      $_SESSION['flash_error'] = implode('<br>', $errors);
      redirect_ke('edit.php?cid='. (int)$cid);
    }

    /*
      Prepared statement untuk anti SQL injection.
      menyiapkan query UPDATE dengan prepared statement 
      (WAJIB WHERE cid = ?)
    */
    $stmt = mysqli_prepare($conn, "UPDATE tbl_tamu 
                                  SET cnama = ?, cemail = ?, cpesan = ? 
                                  WHERE cid = ?");
    if (!$stmt) {
      #jika gagal prepare, kirim pesan error (tanpa detail sensitif)
      $_SESSION['flash_error'] = 'Terjadi kesalahan sistem (prepare gagal).';
      redirect_ke('edit.php?cid='. (int)$cid);
    }

    #bind parameter dan eksekusi (s = string, i = integer)
    mysqli_stmt_bind_param($stmt, "sssi", $nama, $email, $pesan, $cid);

    if (mysqli_stmt_execute($stmt)) { #jika berhasil, kosongkan old value
      unset($_SESSION['old']);
      /*
        Redirect balik ke read.php dan tampilkan info sukses.
      */
      $_SESSION['flash_sukses'] = 'Terima kasih, data anda sudah diperbarui.';
      redirect_ke('read.php'); #pola PRG: kembali ke data dan exit()
    } else { #jika gagal, simpan kembali old value dan tampilkan error umum
      $_SESSION['old'] = [
        'nama'  => $nama,
        'email' => $email,
        'pesan' => $pesan,
      ];
      $_SESSION['flash_error'] = 'Data gagal diperbarui. Silakan coba lagi.';
      redirect_ke('edit.php?cid='. (int)$cid);
    }
    #tutup statement
    mysqli_stmt_close($stmt);

    redirect_ke('edit.php?cid='. (int)$cid);

    session_start();
    require __DIR__ . '/koneksi.php';
    require_once __DIR__ . '/fungsi.php';
    
    # 1. Hanya izinkan metode POST
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $_SESSION['flash_error1'] = 'Akses ditolak.';
        redirect_ke('read_iyup.php');
    }
    
    # 2. Ambil dan sanitasi data
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
    
    # 3. Validasi input
    $errors = [];
    if (empty($nama_lengkap)) $errors[] = "Nama Lengkap wajib diisi.";
    if (empty($tempat_lahir)) $errors[] = "Tempat Lahir wajib diisi.";
    if (empty($tanggal_lahir)) $errors[] = "Tanggal Lahir wajib diisi.";
    
    # 4. Jika ada error, balik ke form edit
    if (!empty($errors)) {
        $_SESSION['old_mahasiswa'] = $_POST;
        $_SESSION['flash_error1'] = implode('<br>', $errors);
        redirect_ke("edit_iyup.php?nim=" . $nim);
    }
    
    # 5. Eksekusi Update dengan Prepared Statement
    $sql = "UPDATE iyupp SET 
                nama_lengkap = ?, tempat_lahir = ?, tanggal_lahir = ?, 
                hobi = ?, pasangan = ?, pekerjaan = ?, 
                nama_orang_tua = ?, nama_kakak = ?, nama_adik = ? 
            WHERE nim = ?";
    
    $stmt = mysqli_prepare($conn, $sql);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssss", 
            $nama_lengkap, $tempat_lahir, $tanggal_lahir, 
            $hobi, $pasangan, $pekerjaan, 
            $nama_orang_tua, $nama_kakak, $nama_adik, $nim
        );
    
        if (mysqli_stmt_execute($stmt)) {
            unset($_SESSION['old_mahasiswa']);
            $_SESSION['flash_sukses1'] = "Data mahasiswa $nim berhasil diperbarui.";
            redirect_ke('read_iyup.php');
        } else {
            $_SESSION['flash_error1'] = "Gagal memperbarui data: " . mysqli_error($conn);
            redirect_ke("edit_iyup.php?nim=" . $nim);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['flash_error1'] = "Kesalahan sistem database.";
        redirect_ke("edit_iyup.php?nim=" . $nim);
    }


    