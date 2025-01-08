<!--
// Nama File: sandibaru.php
// Deskripsi: mengelola fungsi dari resetsandi untuk membuat sandibaru.
// Dibuat oleh: Fahmi Ahmad Fardani - NIM: 3312401017
// Tanggal: 01 Desember 2024

DECLARE db_connection AS DatabaseConnection
DECLARE newPassword AS STRING
DECLARE confirmPassword AS STRING

db_connection = OPEN CONNECTION TO 'astore'

INPUT newPassword
INPUT confirmPassword

IF db_connection IS NOT NULL THEN
  DECLAE newPassword email AS STRING
  DELARE confirmPassword AS STRING

  EXECUTE QUERY 'SELECT password FROM users WHERE username :username' INTO password

	DISPLAY "Password berhasil diperbarui"

  ELSE  DISPLAY "Gagal memperbarui Password"
  ENDIF

CLOSE db_connection
-->

<?php
session_start();  // Mulai sesi
include 'koneksi.php';  // menyertakan file koneksi

// Periksa apakah email disimpan di sesi
if (!isset($_SESSION['email_reset'])) {
    header("Location: forgot_password.php");
    exit();
}
// Mengambil email dari sesi
$email = $_SESSION['email_reset']; 

// Memeriksa apakah permintaan menggunakan metode POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Mengambil nilai password baru dan konfirmasi password yang dikirim dari formulir
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Validasi password
    if ($newPassword !== $confirmPassword) {
        $error = "Password dan konfirmasi password tidak cocok."; // Pesan error jika password tidak cocok
    } elseif (strlen($newPassword) < 6) {
        $error = "Password harus memiliki minimal 6 karakter."; // Validasi untuk memeriksa apakah panjang password minimal 6 karakter
    } else {
        // Perbarui password di database dan membuat password menjadi default 
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        // Menyiapkan query untuk memperbarui password di database
        $stmt = $koneksi->prepare("UPDATE users SET password = ? WHERE email = ?");
        // Mengecek apakah query berhasil disiapkan
        if (!$stmt) {
            die("Query gagal: " . $koneksi->error);
        }
        // Mengikat parameter ke query dan menjalankan Query
        $stmt->bind_param("ss", $hashedPassword, $email);
        if ($stmt->execute()) {
            echo "<script>
            alert('password berhasil diperbarui');
            window.location.href = 'login.php';
            </script>";  // Jika password berhasil diperbarui, tampilkan pesan sukses dan arahkan ke halaman login
        } else {
            $error = "Gagal memperbarui password.";  // Jika gagal memperbarui password, tampilkan pesan error
        }
        // // Menutup statement
        $stmt->close();
    }
}
// Menutup koneksi ke database
$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandi Baru</title>
    <link rel="stylesheet" href="sandibaru.css">
</head>
<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <h2>Masukkan Sandi Baru</h2>
        <div class="form-box login">
            <form id="passwordForm" method="POST" action="">
                <div class="input-box">
                    <input type="password" id="newPassword" name="newPassword" placeholder="Masukkan Password Baru"
                        required>
                    <input type="password" id="confirmPassword" name="confirmPassword"
                        placeholder="Konfirmasi Password Baru" required>
                    <button type="submit" class="btn">Buat Sandi Baru</button>
                    <?php if (isset($error)): ?>
                        <p class="message" style="color: #f44336;"><?= $error ?></p>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
