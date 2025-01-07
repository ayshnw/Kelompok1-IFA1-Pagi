<!--
// Nama File: tentang_kami.php
// Deskripsi: Tentang kami berfungsi untuk menambahkan data admin dari file about_us.php
// Dibuat oleh: Fahmi Ahmad Fardani - NIM: 3312401017
// Tanggal: 17 November 2024
-->

<?php
include('config.php'); // Menyertakan file konfigurasi yang berisi koneksi database
session_start(); // Memulai sesi

// Menangani pesan notifikasi
if (isset($_SESSION['message'])) { // Memeriksa apakah ada pesan dalam session untuk ditampilkan
    // Menampilkan pesan notifikasi dengan kelas bootstrap yang sesuai dengan jenis pesan (success, error, dsb.)
    echo "<div class='alert alert-{$_SESSION['type']} alert-dismissible fade show' role='alert'>";
    // Menampilkan pesan notifikasi setelah memprosesnya dengan htmlspecialchars
    echo htmlspecialchars($_SESSION['message']);
    // Menambahkan tombol untuk menutup pesan notifikasi menggunakan Bootstrap
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    // Menutup elemen div untuk pesan notifikasi
    unset($_SESSION['message'], $_SESSION['type']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AStore</title>
    <!--CSS-link-->
    <link rel="stylesheet" href="tentang.css">

    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Parkinsans:wght@500&display=swap" rel="stylesheet">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" integrity="sha512-5Hs3dF2AEPkpNAR7UiOHba+lRSJNeM2ECkwxUIxC1Q/FLycGTbNapWXB4tP889k5T5Ju8fs4b1P5z/iB4nMfSQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet"
  href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <header class="sticky">
        <div class="logo">
            <img src="ASTORE.PNG" alt="AStore Logo">
            <h1>AStore</h1>
        </div>
        <ul class="navmenu">
            <li><a href="menu_utama.php">Menu Utama</a></li>
            <li><a href="tentang_kami.php"><b>Tentang Kami</b></a></li>
        </ul>
        <div class="search-bar">
            <input type="text" placeholder="SEARCH">
        </div>
        <div class="nav-icon">
            <a href="keranjang.php"><i class='bx bx-cart'></i></a>
            <a href="profile.php"><i class='bx bx-user'></i></a>
        </div>
    </header>

    <main>
        <section class="about-us">
            <h2>About Us</h2>
            <img src="about_as.jpg" alt="about Image">
            <p>Visi

            1. Menjadi platform jual beli seragam SD Negeri terdepan dan terpercaya di Indonesia.
            2. Meningkatkan efisiensi dan kenyamanan transaksi jual beli seragam bagi siswa, orang tua, dan sekolah.
            3. Membangun ekosistem digital yang mendukung pendidikan dan kemudahan akses barang kebutuhan sekolah.

            Misi

            1. Mengembangkan aplikasi yang mudah digunakan, aman, dan efisien.
            2. Meningkatkan kualitas layanan dan kepuasan pengguna.
            3. Membangun kerjasama strategis dengan sekolah dan penyedia seragam.
            4. Meningkatkan kesadaran dan kemudahan akses seragam berkualitas bagi siswa.
            5. Mengembangkan fitur-fitur inovatif untuk memenuhi kebutuhan pengguna.
            </p>
        </section>

        <section class="cards-container">
            <?php
            // Menampilkan data dari tabel 'about_us' yang diambil dari database
            $query = mysqli_query($koneksi, "SELECT * FROM about_us");
            while ($row = mysqli_fetch_assoc($query)) {
                // Menampilkan setiap item sebagai sebuah kartu
                echo "<div class='card'>";
                echo "<div class='card-header'>" . htmlspecialchars($row['nama']) . "</div>";
                echo "<div class='card-body'>";
                echo "<img src='uploads/" . htmlspecialchars($row['gambar']) . "' alt='Card Image'>";
                echo "</div>";
                echo "</div>";
            }
            ?>
        </section>
    </main>

     <!-- Footer dengan informasi layanan pelanggan, informasi perusahaan, dan kontak -->
    <footer style="background-image: linear-gradient(to bottom, #ffffff, #77c6e8);">
        <div class="container">
            <div class="row">
                <!-- Informasi layanan pelanggan -->
                <div class="col-md-3">
                    <h4>LAYANAN PELANGGAN</h4>
                    <h5>
                        <p> Jam Operasional
                    </h5> Senin - Jumat: 13:00 - 22:00 WIB
                    Sabtu: 11:00 - 22:00 WIB
                    <br> Minggu & Hari Libur: Tutup </p>
                </div>
                 <!-- Informasi tentang perusahaan AStore -->
                <div class="col-md-3">
                    <h4>TENTANG KAMI</h4>
                    <p> AStore adalah aplikasi jual beli berbasis web dengan tersedia nya produk seragam SD (merah
                        putih), Astore memudahkan pengguna aplikasi untuk membeli kebutuhan anak sekolah melalui mobile
                        atau desktop, Astore juga menyediakan produk berkualitas dan pastinya aman dan terpercaya.</p>
                </div>
                <!-- Informasi kontak -->
                <div class="col-md-3">
                    <h4>HUBUNGI KAMI</h4>
                    <p>Call Center: +62-852-7475-5685</p>
                    <p>Email: asstore389@gmail.com</p>
                    <div class="buttons">
                         <!-- Tombol untuk berbagi ke sosial media seperti Instagram -->
                        <button class="buttons__toggle"><i class="fa fa-share-alt"></i></button>
                        <div class="allbtns">
                            <a class="button"
                                href="https://www.instagram.com/astore.team?igsh=MzNlNGNkZWQ4Mg==">Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
</body>

</html>
