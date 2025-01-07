<!--
// Nama File: menu_utama.php
// Deskripsi: File ini bertujuan untuk menyediakan antarmuka yang menampikan data produk
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 02 November
-->

<?php
// Memulai session
session_start();

// Koneksi ke dalam database
include 'koneksi.php';

// Periksa apakah pengguna sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Ambil username pengguna dari session
$userName = $_SESSION['username'];

// Inisialisasi pencarian
$search = "";

// Percabangan kode untuk menangani error
try {
    // Jika form search dikirimkan
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $search = $_GET['search'];

        // Query dengan filter nama produk
        $query = mysqli_prepare($koneksi, "SELECT * FROM produk WHERE nama_produk LIKE ?");
        if (!$query) {
            throw new Exception("Query gagal: " . mysqli_error($koneksi));
        }

        // Wildcard untuk LIKE
        $searchTerm = '%' . $search . '%';
        mysqli_stmt_bind_param($query, "s", $searchTerm);
        mysqli_stmt_execute($query);
        $result = mysqli_stmt_get_result($query);

        // Cek jumlah hasil pencarian
        $found = mysqli_num_rows($result);
    } else {

        // Query untuk mengambil semua produk
        $query = mysqli_query($koneksi, "SELECT * FROM produk");
        if (!$query) {
            throw new Exception("Query gagal: " . mysqli_error($koneksi));
        }

        // Cek jumlah semua produk
        $found = mysqli_num_rows($query);
    }
}
// Jika percabangan try tidak berfungsi
 catch (Exception $e) {
    echo "<h3>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</h3>";
    exit();
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
    <link rel="stylesheet" href="menu.css">

    <!-- Link extension lain -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Parkinsans:wght@500&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css"/>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>

<body>
    <header class="sticky">
        <div class="logo">
            <img src="ASTORE.PNG" alt="AStore Logo">
            <h1>AStore</h1>
        </div>
        <ul class="navmenu">
            <li><a href="menu_utama.php"><b>Menu Utama</b></a></li>
            <li><a href="tentang_kami.php">Tentang Kami</a></li>
        </ul>

        <!-- Search Bar -->
        <div class="search-bar">
            <form method="GET">
                <input type="text" name="search" placeholder="SEARCH" value="<?= htmlspecialchars($search); ?>">
            </form>
        </div>
        <div class="nav-icon">
            <a href="keranjang.php"><i class='bx bx-cart' data-count="0"></i></a>
            <a href="profile.php"><i class='bx bx-user'></i></a>
        </div>
    </header>

    <!-- Daftar Produk -->
    <section class="daftar-produk" id="produk">
        <div class="center-text">
            <h2>Daftar Produk</h2>
        </div>

        <!-- Grid Container -->
        <div class="product-grid">
        <div class="produk">
    <?php 
    // Menampilkan output jika produk ditemukan atau tidak
    if ($found > 0) {
        // Jika hasil ditemukan
        if (isset($result)) {
            while ($data = mysqli_fetch_assoc($result)) { ?>
                <div class="product-card" data-id="<?= $data['id_produk']; ?>">
                    <div class="row-1">
                        <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="<?= htmlspecialchars($data['nama_produk']); ?>">
                        <div class="harga">
                        <div class="nama-btn">
                                <h4><?= htmlspecialchars($data['nama_produk']); ?></h4>
                            </div>
                        </div>
                        <div class="cart-icon">
                            <form action="keranjang.php" method="POST">
                                <input type="hidden" name="produk_id" value="<?= $data['id_produk']; ?>">
                                <input type="hidden" name="tambah_keranjang" value="true"> <!-- Menandakan produk harus ditambahkan ke keranjang -->
                                <label for="ukuran">Ukuran:</label>
                                <select name="ukuran" required>
                                    <?php $sizes = explode(',', $data['ukuran']); // Asumsi ukuran disimpan dalam format 'S,M,L' ?>
                                    <?php foreach ($sizes as $size): ?>
                                        <option value="<?= trim($size); ?>"><?= trim($size); ?></option>
                                    <?php endforeach; ?>
                                </select> <br>
                                <label for="jumlah" class="jumlah">Jumlah:</label>
                                    <input type="number" name="jumlah" class="jumlah" value="1" min="1" required>
                                    <p>Rp. <?= number_format($data['harga'], 0, ',', '.'); ?></p>
                                    <button type="submit" class="add-to-cart-btn">
                                    <i class='bx bx-cart-add'></i> <!-- Ikon Keranjang -->
                                </button>
                            </form>
                        </div>
                        <div class="ratting">
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class='bx bxs-star'></i>
                            <i class="bx bxs-star-half"></i>
                        </div>
                        <a href="detail_produk.php?id_produk=<?= $data['id_produk']; ?>">
                            <button class="normal">Detail</button>
                        </a>
                    </div>
                </div>
            <?php }
        } else {
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <div class="product-card">
                    <div class="row-2">
                        <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="<?= htmlspecialchars($data['nama_produk']); ?>">
                        <div class="harga">
                            <div class="nama-btn">
                                <h4><?= htmlspecialchars($data['nama_produk']); ?></h4>
                            </div>
                        </div>
                            <div class="cart-icon">
                                <form action="keranjang.php" method="POST">
                                    <input type="hidden" name="produk_id" value="<?= $data['id_produk']; ?>">
                                    <input type="hidden" name="tambah_keranjang" value="true"> <!-- Menandakan produk harus ditambahkan ke keranjang -->
                                    <label for="ukuran" class="ukuran">Ukuran:</label>
                                    <select name="ukuran" required>
                                        <?php $sizes = explode(',', $data['ukuran']); ?>
                                        <?php foreach ($sizes as $size): ?>
                                            <option value="<?= trim($size ); ?>"><?= trim($size); ?></option>
                                        <?php endforeach; ?>
                                    </select> <br>
                                    <label for="jumlah" class="jumlah">Jumlah:</label>
                                    <input type="number" name="jumlah" class="jumlah" value="1" min="1" required>
                                    <p>Rp. <?= number_format($data['harga'], 0, ',', '.'); ?></p>
                                    <button type="submit" class="add-to-cart-btn">
                                    <i class='bx bx-cart-add'></i> <!-- Ikon Keranjang -->
                                </button>
                                </form>
                            </div>
                        
                        <a href="detail_produk.php?id_produk=<?= $data['id_produk']; ?>">
                            <button class="normal">Detail</button>
                        </a>
                    </div>
                </div>
            <?php }
        }
    } else { ?>
        <div class="center-text" style="text-align: center; margin-top: 20px;">
            <h3 style="color: red;">Produk tidak ditemukan!</h3>
        </div>
    <?php } ?>
</div>
        </div>
    </section>

    <!-- Footer -->
    <footer style="background-image: linear-gradient(to bottom, #ffffff, #77c6e8,);">
        <div class="container">
            <div class="row1">
                <div class="col-md-3">
                    <h4>LAYANAN PELANGGAN</h4>
                    <h5>
                        <p> Jam Operasional
                    </h5> Senin - Jumat: 13:00 - 22:00 WIB
                    <br> Sabtu: 11:00 - 22:00 WIB ></br>
                    <br> Minggu & Hari Libur: Tutup </p>
                </div>
                <div class="col-md-3">
                    <h4>TENTANG KAMI</h4>
                    <p> AStore adalah aplikasi jual beli berbasis web dengan tersedia nya produk seragam SD
                        (merah putih),
                        Astore memudahkan pengguna aplikasi untuk membeli kebutuhan anak sekolahmelalui
                        mobile atau desktop,
                        Astore juga menyediakan produk berkualitas dan pastinya aman dan terpercaya.</p>
                </div>

                <div class="col-md-3">
                    <h4>HUBUNGI KAMI</h4>
                    <p>Call Center: +62-852-7475-5685</p>
                    <p>Email: asstore389@gmail.com</p>
                    <div class="buttons">
                        <button class="buttons__toggle"><i class="fa fa-share-alt"></i></button>
                        <div class="allbtns">
                            <a class="button"
                                href="https://www.instagram.com/astore.team?igsh=MzNlNGNkZWQ4Mg=="></i>Instagram</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah submit biasa
                let form = this.closest('form'); // Ambil form terdekat
                form.submit(); // Kirim form
            });
        });

        // Fungsi untuk redirect ke menu utama setelah produk ditambahkan ke keranjang
        function redirectToMenu() {
            // Redirect ke halaman menu utama setelah produk ditambahkan ke keranjang
            window.location.href = "menu_utama.php";
            return true; // Mengizinkan form untuk dikirim
        }
    </script>
</body>
</html>