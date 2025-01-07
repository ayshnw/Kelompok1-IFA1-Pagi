<!--
// Nama File: profile.php
// Deskripsi: mengelola fungsi profile yang terdapat username, nama lengkap, email, no handphone, 
alamat, jenis kelamin, dan juga terkoneksi ke ubah_password
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 02 Desember 2024
-->

<?php
// Konfigurasi database
$host = "localhost"; // Nama host
$username = "root"; // Username database
$password = ""; // Password database
$dbname = "astore"; // Nama database

// Koneksi ke database
$conn = mysqli_connect($host, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error); // Jika terjadi kesalahan dalam koneksi, tampilkan pesan error dan hentikan eksekusi
}

// Ambil username dari sesi (pastikan user sudah login)
session_start(); // Memulai sesi untuk mendapatkan data yang disimpan di sesi
 // Jika user belum login, tampilkan peringatan dan alihkan ke halaman login
if (!isset($_SESSION['username'])) {
    echo "<script>
            alert('Silakan login terlebih dahulu!');
            window.location.href = 'login.php';
          </script>";
    exit(); // Hentikan eksekusi lebih lanjut jika user belum login
}

$username = $_SESSION['username']; // Username user yang login

// Ambil data pengguna dari database
$querySelect = "SELECT * FROM users WHERE username = '$username'";
$result = mysqli_query($conn, $querySelect); // Menjalankan query untuk mendapatkan data pengguna

// Cek jika data ditemukan
if ($result && mysqli_num_rows($result) > 0) {
    // Jika data ditemukan, ambil data pengguna dan simpan dalam variabel
    $user = mysqli_fetch_assoc($result);

    // Variabel untuk menampung data user
    $nama = $user['nama'];
    $email = $user['email'];
    $alamat =$user['alamat'];
    $telepon = $user['telepon'];
    $jenis_kelamin = $user['jenis_kelamin'];
    $tanggal_lahir = $user['tanggal_lahir'];
} else {
     // Jika data tidak ditemukan di database
    echo "Data tidak ditemukan!";
    exit(); // Hentikan eksekusi
}

// Proses update data jika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $telepon = $_POST['telepon'];
    $jenis_kelamin = $_POST['gender']; // Gender diambil dari form dengan name "gender"
    $tanggal_lahir = $_POST['birthday']; // Tanggal lahir diambil dari form dengan name "birthday"

    // Query untuk memperbarui data pengguna dalam database
    $queryUpdate = "UPDATE users SET 
        nama = '$nama',
        email = '$email',
        alamat = '$alamat',
        telepon = '$telepon',
        jenis_kelamin = '$jenis_kelamin',
        tanggal_lahir = '$tanggal_lahir'
        WHERE username = '$username'"; // Query untuk memperbarui data berdasarkan username

    // Menjalankan query update
    if (mysqli_query($conn, $queryUpdate)) {
        // Jika berhasil, tampilkan pesan sukses dan alihkan ke halaman profil
        echo "<script>
            alert('Profil berhasil diperbarui!');
            window.location.href = 'profile.php';
        </script>";
        exit();  // Hentikan eksekusi
    } else {
        // Jika gagal, tampilkan pesan error
        echo "Gagal memperbarui profil: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Profil - AStore</title>
    <link rel="stylesheet" href="profile.css">
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
                <li><b><a href="profile.php">Profil</a></b></li>
                <li><a href="ubah_password.php">Ubah Password</a></li>
                <li><a href="privasi.php">Pengaturan Privasi</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="profile-content">
            <h2>Ubah Profil</h2>
            <p>Kelola informasi profil Anda untuk mengontrol, melindungi, dan mengamankan akun</p>
            <div class="form-container">
                <form method="POST">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo $username; ?>" readonly>

                    <label>Nama</label>
                    <input type="text" name="nama" placeholder="Masukkan Nama Anda" value="<?php echo $nama; ?>" required>

                    <label>Email</label>
                    <input type="email" name="email" placeholder="Masukkan Email" value="<?php echo $email; ?>" required>

                    <label>Alamat</label>
                    <input type="alamat" name="alamat" placeholder="Masukkan Alamat" value="<?php echo $alamat; ?>" required>

                    <label>Nomor Handphone</label>
                    <input type="tel" name="telepon" placeholder="Masukkan Nomor Handphone" value="<?php echo $telepon; ?>" required>

                    <label>Jenis Kelamin</label>
                    <div class="gender">
                        <label><input type="radio" name="gender" value="Laki-laki" <?php echo ($jenis_kelamin == 'Laki-laki') ? 'checked' : ''; ?>> Laki-laki</label>
                        <label><input type="radio" name="gender" value="Perempuan" <?php echo ($jenis_kelamin == 'Perempuan') ? 'checked' : ''; ?>> Perempuan</label>
                    </div>

                    <label>Tanggal Lahir</label>
                    <input type="date" name="birthday" value="<?php echo $tanggal_lahir; ?>" required>

                    <button type="submit" class="save-btn">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
