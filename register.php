<!--
// Nama File: register.php
// Deskripsi: File ini bertujuan untuk menyediakan antarmuka yang memungkinkan pengguna baru untuk membuat 
              akun pada website
// Dibuat oleh: Steven Marcell Samosir - NIM: 3312401003
// Tanggal: 02 November
-->

<?php
// Memulai session
session_start();

// Koneksi ke database
include 'koneksi.php';

// Jika sudah login, redirect ke menu utama
if(isset($_SESSION['login'])) {
    header('Location: menu_utama.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $role = mysqli_real_escape_string($koneksi, $_POST['role']);

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid'); window.history.back();</script>";
        exit();
    }

    if ($email !== $_POST['email']) {
        echo "<script>
        alert('Email sudah terdaftar!'); window.history.back();
        </script>";
    }

    $stmt = mysqli_prepare($koneksi, "SELECT username FROM users WHERE username = ?");
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
        echo "<script>alert('Username sudah terdaftar'); window.history.back();</>";
        return false;
    }

    mysqli_stmt_close($stmt);

    // Validasi apakah password dan confirm_password cocok
    if ($password !== $confirm_password) {
        echo "<script>alert('Password atau Confirm Password tidak sesuai'); window.history.back();</script>";
        exit();
    }

    // Hash password sebelum disimpan
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($koneksi, "INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $password_hash, $email, $role);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo "<script>alert('Registrasi berhasil'); 
            window.location.href = 'login.php';
        </script>";
        return true;
    } else {
        echo "<script>alert('Registrasi gagal'); window.history.back();</script>";
        return false;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Website AStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="wrapper">
        <div class="info-text login">
            <img src="gambar/ASTORE.png" width="65%" alt="AStore Logo">
            <h2>Selamat Datang</h2>
            <p>Daftarkan diri Anda dan mulai gunakan layanan kami</p>
        </div>
        <span class="bg-animate"></span>
        <div class="form-box login">
            <h2>Register</h2>
            <form action="" method="POST">
                <div class="input-box">
                    <input type="text" name="username" id="username" required>
                    <label for="username">Username</label>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" required>
                    <label for="password">Password</label>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="confirm_password" id="confirm_password" required>
                    <label for="confirm_password">Confirm Password</label>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="input-box">
                    <input type="email" name="email" required>
                    <label>Masukkan Email</label>
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" id="role" name="role" required>
                        <option value="pembeli">Pembeli</option>
                    </select>
                </div>
                <button type="submit" class="btn" name="register">Register</button>
            </form>
        </div>
    </div>
</body>
</html>