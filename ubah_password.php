<!--
// Nama File: ubah_password.php
// Deskripsi: mengelola fungsi untuk merubah password yang tersambung dari halaman profile
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 02 Desember 2024

DECLARE db_connection AS DatabaseConnection  
DECLARE username AS STRING  
DECLARE old_password AS STRING  
DECLARE new_password AS STRING  
DECLARE confirm_password AS STRING  

db_connection = OPEN CONNECTION TO 'astore'  

INPUT username  
INPUT old_password  

INPUT new_password  
INPUT confirm_password  

IF db_connection IS NOT NULL THEN  
    DECLARE stored_password AS STRING  

    EXECUTE QUERY 'SELECT password FROM users WHERE username = :username' INTO stored_password  

    IF VERIFY_PASSWORD(old_password, stored_password) THEN  
        IF new_password == confirm_password THEN  
            IF LENGTH(new_password) >= 6 THEN  
                DECLARE hashed_password AS STRING  
                hashed_password = HASH(new_password)  

                EXECUTE QUERY 'UPDATE users SET password = :hashed_password WHERE username = :username'  

                DISPLAY "Password berhasil diubah!"  
            ELSE  
                DISPLAY "Password baru harus minimal 6 karakter!"  
            ENDIF  
        ELSE  
            DISPLAY "Password baru dan konfirmasi password tidak cocok!"  
        ENDIF  
    ELSE  
        DISPLAY "Password lama salah!"  
    ENDIF  
ELSE  
    DISPLAY "Gagal koneksi ke database"  
ENDIF  

CLOSE db_connection  
-->

<?php
// Memulai session
session_start();

// Mengimpor file koneksi database atau fungsi
include 'koneksi.php';

// Periksa apakah pengguna sudah login
// Jika belum login (session "login" tidak ada), alihkan pengguna ke halaman login
if (!isset($_SESSION["login"])) {
    header("Location: login.php");  // Pengguna diarahkan ke halaman login
    exit; // Hentikan eksekusi
}

// Ambil data pengguna saat ini
$username = $_SESSION["username"]; // Pastikan session menyimpan username pengguna

// Variabel untuk pesan error dan sukses
$error_message = ''; // Menyimpan pesan error jika ada kesalahan
$success_message = ''; // Menyimpan pesan sukses jika password berhasil diubah

// Proses jika form di-submit
if (isset($_POST["ubah_password"])) {
    // Ambil data password yang dikirim melalui formulir
    $password_lama = htmlspecialchars($_POST["old_password"]);
    $password_baru = htmlspecialchars($_POST["new_password"]);
    $konfirmasi_password = htmlspecialchars($_POST["confirm_password"]);

    // Validasi input kosong
    if (empty($password_lama) || empty($password_baru) || empty($konfirmasi_password)) {
        // Jika ada input kosong, tampilkan pesan error
        $error_message = "Semua kolom wajib diisi!";
    } else {
        try {
            // Ambil password lama dari database
            $query = "SELECT password FROM users WHERE username = ?";
            $stmt = mysqli_prepare($koneksi, $query); // Menyiapkan query untuk eksekusi
            if (!$stmt) {
                throw new Exception("Gagal menyiapkan query: " . mysqli_error($koneksi));
            }
            
            mysqli_stmt_bind_param($stmt, "s", $username); // Mengikat parameter username
            mysqli_stmt_execute($stmt); // Menjalankan query
            $result = mysqli_stmt_get_result($stmt); // Mendapatkan hasil query

            // Mengecek apakah data pengguna ditemukan
            if ($result && mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result); // Mengambil data pengguna

                // Verifikasi password lama
                if (password_verify($password_lama, $user["password"])) {
                    // Periksa apakah password baru dan konfirmasi cocok
                    if ($password_baru === $konfirmasi_password) {
                        // Validasi panjang password baru harus minimal 6 karakter
                        if (strlen($password_baru) < 6) {
                            $error_message = "Password baru harus minimal 6 karakter!";
                        } else {
                             // Meng-hash password baru untuk keamanan
                            $password_baru_hash = password_hash($password_baru, PASSWORD_DEFAULT);

                            // Query untuk memperbarui password pengguna di database
                            $update_query = "UPDATE users SET password = ? WHERE username = ?";
                            $update_stmt = mysqli_prepare($koneksi, $update_query);  // Menyiapkan query update
                            if (!$update_stmt) {
                                throw new Exception("Gagal menyiapkan query update: " . mysqli_error($koneksi));
                            }

                            mysqli_stmt_bind_param($update_stmt, "ss", $password_baru_hash, $username); // Mengikat parameter password dan username

                            // Mengeksekusi query untuk memperbarui password
                            if (!mysqli_stmt_execute($update_stmt)) {
                                throw new Exception("Gagal memperbarui password: " . mysqli_stmt_error($update_stmt));
                            }

                            // Hapus session dan arahkan ke halaman login
                            session_destroy();
                            echo "<script>
                                alert('Password berhasil diubah! Silakan login kembali dengan password baru.');
                                window.location.href = 'login.php';
                            </script>";
                            exit; // Menghentikan eksekusi
                        }
                    } else {
                        $error_message = "Password baru dan konfirmasi password tidak cocok!"; // Pesan error jika password baru dan konfirmasi tidak cocok
                    }
                } else {
                    $error_message = "Password lama salah!"; // Pesan error jika password lama yang dimasukkan salah
                }
            } else {
                $error_message = "Data pengguna tidak ditemukan."; // Pesan error jika data pengguna tidak ditemukan di database
            }
        } catch (Exception $e) {
            // Tangkap exception dan tampilkan pesan kesalahan
            $error_message = "Terjadi kesalahan: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password - AStore</title>
    <link rel="stylesheet" href="ubah_password.css">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@100..900&family=Parkinsans:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
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

    <div class="profile-container">
        <div class="sidebar">
            <h3>Akun Saya</h3>
            <ul>
                <li><a href="profile.php">Profil</a></li>
                <li><b><a href="ubah_password.php">Ubah Password</a></b></li>
                <li><a href="privasi.php">Pengaturan Privasi</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>

        <div class="profile-content">
            <h2>Ubah Password</h2>
            <p>Untuk keamanan akun Anda, mohon untuk tidak menyebarkan password Anda ke orang lain</p>

            <!-- Tampilkan pesan error atau sukses -->
            <?php if (!empty($error_message)): ?>
                <div class="error-message">
                    <p><?= htmlspecialchars($error_message); ?></p>
                </div>
            <?php elseif (!empty($success_message)): ?>
                <div class="success-message">
                    <p><?= htmlspecialchars($success_message); ?></p>
                </div>
            <?php endif; ?>

            <div class="form-container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="old-password">Password lama</label>
                        <input type="password" id="old-password" name="old_password" placeholder="Masukkan password lama" required>
                    </div>
                    <div class="form-group">
                        <label for="new-password">Password Baru</label>
                        <input type="password" id="new-password" name="new_password" placeholder="Masukkan password baru" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Konfirmasi Password</label>
                        <input type="password" id="confirm-password" name="confirm_password" placeholder="Konfirmasi password" required>
                    </div>
                    <button type="submit" name="ubah_password" class="btn">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
