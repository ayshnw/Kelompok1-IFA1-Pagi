<!--
// Nama File: privasi.php
// Deskripsi: File ini bertujuan untuk mengatur privasi akun pengguna khususnya pembeli
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 07 November
-->

<?php
// Memulai session untuk menyimpan data sementara pengguna
session_start();

// Mengimpor koneksi ke database
include 'koneksi.php';

// Memastikan pengguna sudah login sebelum menghapus akun
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('Anda harus login untuk menghapus akun!');
            window.location='login.php';
          </script>";
    exit();
}

// Mendapatkan username dari sesi pengguna
$username = $_SESSION['username'];

// Fungsi untuk menghapus akun dari database
function hapus_akun($username) {
    global $koneksi;

    // Mempersiapkan query untuk menghapus data pengguna
    $delete_stmt = mysqli_prepare($koneksi, "DELETE FROM users WHERE username = ?");
    mysqli_stmt_bind_param($delete_stmt, "s", $username);
    mysqli_stmt_execute($delete_stmt);

    // Memeriksa apakah penghapusan berhasil
    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        echo "<script>alert('Akun berhasil dihapus!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus akun!'); window.history.back();</script>";
    }
}

// Mengeksekusi penghapusan akun saat tombol ditekan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_akun'])) {
    hapus_akun($username);

    // Menghapus sesi setelah akun dihapus
    session_unset();
    session_destroy();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AStore</title>

  <!-- CSS Link -->
  <link rel="stylesheet" href="privasi.css">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100..900&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" crossorigin="anonymous">

  <!-- Boxicons -->
  <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>
  <!-- Header -->
  <header class="sticky">
    <div class="logo">
      <img src="ASTORE.PNG" alt="AStore Logo">
      <h1>AStore</h1>
    </div>
    <ul class="navmenu">
      <li><a href="menu_utama.php">Menu Utama</a></li>
      <li><a href="tentang_kami.php">Tentang Kami</a></li>
    </ul>

    <div class="search-bar">
      <input type="text" placeholder="SEARCH">
    </div>
    <div class="nav-icon">
      <a href="keranjang.php"><i class='bx bx-cart'></i></a>
      <a href="profile.php"><i class='bx bx-user'></i></a>
    </div>
  </header>

  <!-- Konten Profil -->
  <div class="profile-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <h3>Akun Saya</h3>
      <ul>
        <li><a href="profile.php">Profil</a></li>
        <li><a href="ubah_password.php">Ubah Password</a></li>
        <li><b><a href="privasi.php">Pengaturan Privasi</a></b></li>
        <li><a href="login.php">Logout</a></li>
      </ul>
    </div>

    <!-- Konten Pengaturan Privasi -->
    <div class="profile-content">
      <h2>Privasi Akun</h2>
      <p>Minta penghapusan akun</p>
      <div class="form-container">
        <form method="POST">
          <button type="submit" name="hapus_akun" class="btn-syariah">Menghapus</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
