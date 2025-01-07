<!--
// Nama File: hapus_akun.php
// Deskripsi: untuk menghapus akun 
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 07 Desember 2024
-->

<?php
// Memulai session
session_start();

include 'koneksi.php';

// Pastikan pengguna login
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('Anda harus login untuk menghapus akun!');
            window.location='login.php';
          </script>";
    exit();
}

$username = $_SESSION['username'];

// Fungsi untuk menghapus akun
function hapus_akun($username) {
    global $koneksi;

    // Hapus data pengguna dari database
    $delete_stmt = mysqli_prepare($koneksi, "DELETE FROM users WHERE username = ?");
    mysqli_stmt_bind_param($delete_stmt, "s", $username);
    mysqli_stmt_execute($delete_stmt);

    if (mysqli_stmt_affected_rows($delete_stmt) > 0) {
        echo "<script>alert('Akun berhasil dihapus!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus akun!'); window.history.back();</script>";
    }
}

// Konfirmasi penghapusan akun
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['hapus_akun'])) {
    hapus_akun($username);

    // Hapus sesi setelah akun dihapus
    session_unset();
    session_destroy();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Akun</title>
    <link rel="stylesheet" href="privasi.css">
</head>
<body>
    <div class="profile-container">
        <h2>Konfirmasi Hapus Akun</h2>
        <p>Apakah Anda yakin ingin menghapus akun Anda? Tindakan ini tidak dapat dibatalkan.</p>
        <form method="POST">
            <button type="submit" name="hapus_akun" class="btn-danger">Hapus Akun</button>
            <a href="profile.php" class="btn-cancel">Batal</a>
        </form>
    </div>
</body>
</html>
