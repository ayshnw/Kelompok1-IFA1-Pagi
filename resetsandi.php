<!--
// Nama File: resetsandi.php
// Deskripsi: mengelola fungsi yang berhubungan dengan forgot password pada halaman login
// Dibuat oleh: Fahmi Ahmad Fardani - NIM: 3312401017
// Tanggal: 01 Desember 2024

DECLARE db_connention AS DatabaseConnection
DECLARE email AS STRING

db_connection = OPEN CONNECTION TO 'astore'

INPUT email

IF db_connection IS NOT NULL THEN
  DECLARE email AS STRING

  EXECUTE QUERY 'SELECT email FROM users WHERE username = :username' INTO email
	
	DISPLAY "Email berhasil"

  ELSE	DISPLAY "Email tidak ditemukan"
  ENDIF

CLOSE db_connection
-->

<?php 
session_start(); // Mulai sesi
include 'koneksi.php'; // menyertakan file koneksi

// Memeriksa metode permintaan 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email']; // Mengambil email dari formulir

    // Periksa apakah email ada di database
    // Menggunakan prepared statement untuk mencegah SQL injection
    $stmt = $koneksi->prepare("SELECT user_id FROM users WHERE email = ?");
    if (!$stmt) { die("Query gagal: " . $koneksi->error); }

    // mengikat parameter ke Query dan menjalankan Query
    $stmt->bind_param("s", $email); // "s" menunjukkan parameter string untuk email
    $stmt->execute(); // Menjalankan query yang telah dipersiapkan
    $result = $stmt->get_result(); // Mendapatkan hasil query

    // Memeriksa apakah email ditemukan dalam database
    if ($result->num_rows > 0) {
        // Simpan email dalam sesi
        $_SESSION['email_reset'] = $email;
        header("Location: sandibaru.php"); // Alihkan ke halaman reset password
        exit(); // Menghentikan eksekusi
    } else {
        $error = "Email tidak ditemukan."; // Jika email tidak ditemukan dalam database, tampilkan pesan error
    }
    // Menutup statement
    $stmt->close();
}
// Menutup database
$koneksi->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login website AStore</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link rel="stylesheet" href="resetsandi.css">
</head>
<body>
<div class="wrapper">
        <span class="bg-animate"></span>
        <h2>Lupa Kata Sandi</h2>
        <div class="form-box login">
        <form method="POST" action="">
            <div class="input-box">
                <input type="email" name="email" class="form-control" placeholder="Masukkan Email Anda" required>
            </div>
            <button type="submit" class="btn btn-primary">Next</button>
        </form>
        <?php if (isset($error)): ?>
            <p class="text-danger mt-3"><?= $error ?></p>
        <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
