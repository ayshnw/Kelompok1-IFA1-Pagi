<!--
// Nama File: login.php
// Deskripsi: File ini bertujuan untuk menyediakan antarmuka yang memungkinkan pengguna baru untuk memasuki
              halaman utama pada website
// Dibuat oleh: Steven Marcell Samosir - NIM: 3312401003
// Tanggal: 02 November
-->

<?php
// Memulai session
session_start();

// Koneksi ke dalam database
include 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['login'])) {
    header("Location: menu_utama.php");
    exit();
}

// Proses login
if (isset($_POST['login'])) {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $role = $_POST['role'];

    // Mencegah SQL injection
    $username = mysqli_real_escape_string($koneksi, $username);
    $role = mysqli_real_escape_string($koneksi, $role);

    $result = mysqli_query($koneksi, "SELECT * FROM users WHERE username = '$username' AND role = '$role'");

    // Cek username
    if (mysqli_num_rows($result) === 1) {
        // Cek password
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            // Set session
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username']; // Simpan username di session
            $_SESSION['role'] = $role;

            // Cek remember me
            if (isset($_POST['remember'])) {
                // Buat cookie
                setcookie('togel', $row['user_id'], time() + 60);
                setcookie('error', hash('sha256', $row['username']), time() + 30 * 24 * 60 * 60);
            }

            if ($role === 'admin') {
                echo "<script>
                alert('Login berhasil');
                window.location.href = 'produk.php';
                </script>";
                exit();
            } else if($role === 'pembeli'){
                echo "<script>
                alert('Login berhasil');
                window.location.href = 'menu_utama.php';
                </script>";
                exit();
            }
        } else {
            // Jika username atau password salah
            $error = true;
        }
    } else {
        // Jika username atau password salah
        $error = true;
    }
}

// Cek cookie
if (isset($_COOKIE['togel']) && isset($_COOKIE['error'])) {
    $id = $_COOKIE['togel'];
    $key = $_COOKIE['error'];

    // Ambil username berdasarkan id
    $result = mysqli_query($koneksi, "SELECT username, role FROM users WHERE user_id = $id");
    $row = mysqli_fetch_assoc($result);

    if($row) {
        // Cek cookie dan username
        if ($key === hash('sha256', $row['username'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $row['username']; // Simpan username di session
            $_SESSION['role'] = $row['role'];

            if ($row['role'] === 'admin') {
                header("Location: produk.php");
            } else if ($row['role'] === 'pembeli') {
                header("location: menu_utama.php");
            } exit();
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="login.css">
    <title>Login website AStore</title>
</head>
<body>
    <div class="wrapper">
        <span class="bg-animate"></span>
        <div class="form-box login">
            <h2>Login</h2>
            <form action="#" method="POST">
                <div class="input-box">
                    <input type="text" name="username" required>
                    <label for="username">Username</label>
                    <i class="fa-solid fa-user"></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" required>
                    <label for="password">Password</label>
                    <i class="fa-solid fa-lock"></i>
                </div>
                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select" name="role" required>
                        <option value="pembeli" name="pembeli">Pembeli</option>
                        <option value="admin" name="admin">admin</option>
                    </select>
                </div>
                <button type="submit" class="btn" name="login">Login</button>
                <?php if (isset($error)) : ?>
                <p style="color: red; font-style:italic; text-align:center;">Username atau password salah</p>
                <?php endif ?>
                <div class="logreg-link">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>
                <div class="logreg-link">
                    <p>Lupa kata sandi? <a href="resetsandi.php" class="resetsandi-link">Lupa sandi</a></p>
                </div>
                <div class="logreg-link">
                    <p>Tidak punya akun? <a href="register.php" class="register-link">Sign Up</a></p>
                </div>
            </form>
        </div>
        <div class="info-text login">
            <img src="gambar/ASTORE.png" width="65%">
            <h2>Selamat Datang</h2>
            <p>Silahkan masuk dengan akun anda untuk belanja sepuasnya</p>
        </div>
    </div>
</body>
</html>