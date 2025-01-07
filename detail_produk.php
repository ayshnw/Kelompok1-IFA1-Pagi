<!-- 
// Nama File: detail.php
// Deskripsi: halaman untuk menampilkan rincian dari produk
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 09 november 2024
-->

<?php
// Memulai sesi untuk mengelola data sementara pengguna
session_start();

// Menghubungkan ke file koneksi database
include 'koneksi.php';

// Mengambil ID produk dari URL dan memvalidasi keberadaannya
if (!isset($_GET['id_produk']) || empty($_GET['id_produk'])) {
    // Jika ID produk tidak ditemukan, arahkan kembali ke halaman utama
    header("Location: menu_utama.php");
    exit();
}

// Menyimpan ID produk yang diterima
$id_produk = $_GET['id_produk'];

// Query untuk mengambil detail produk berdasarkan ID
$query = "SELECT * FROM produk WHERE id_produk = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_produk);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Memeriksa apakah produk ditemukan
if ($result && mysqli_num_rows($result) > 0) {
    $produk = mysqli_fetch_assoc($result);

    // Memisahkan ukuran produk yang disimpan dalam bentuk string
    $sizes = explode(',', $produk['ukuran']);
} else {
    // Jika produk tidak ditemukan, tampilkan pesan peringatan dan kembali ke halaman utama
    echo "<script>alert('Produk tidak ditemukan'); window.location.href = 'menu_utama.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Metadata dasar dan link CSS -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AStore</title>
    <link rel="stylesheet" href="detail_produk.css">
    <!-- Font dan ikon eksternal -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
</head>
<body>

    <!-- Header Navigasi -->
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

    <!-- Rincian Produk -->
    <section id="rincian" class="section_p1">
        <div class="single-pro-image">
            <!-- Menampilkan gambar produk -->
            <img src="uploads/<?= htmlspecialchars($produk['gambar']); ?>" 
                 alt="<?= htmlspecialchars($produk['nama_produk']); ?>" width="100%">
        </div>
        <div class="single-pro-details">
            <!-- Menampilkan informasi produk -->
            <h6>AStore</h6>
            <h4><?= htmlspecialchars($produk['nama_produk']); ?></h4>
            <h2>Rp. <?= number_format($produk['harga'], 0, ',', '.'); ?></h2>

            <!-- Pilihan ukuran produk -->
            <?php if (!empty($sizes)) : ?>
                <label for="ukuran">Pilih Ukuran:</label>
                <select id="ukuran" name="ukuran" class="form-control" required>
                    <?php foreach ($sizes as $size) : ?>
                        <option value="<?= htmlspecialchars(trim($size)); ?>">
                            <?= htmlspecialchars(trim($size)); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>
            
            <p>Stok Tersedia: <strong><?= $produk['stok']; ?></strong></p>
            
            <!-- Form pembelian produk -->
            <form action="resi_detail.php" method="POST">
                <!-- Data tersembunyi yang dikirim dalam form -->
                <input type="hidden" name="id_produk" value="<?= $produk['id_produk']; ?>">
                <input type="hidden" name="nama_produk" value="<?= $produk['nama_produk']; ?>">
                <input type="hidden" name="harga" value="<?= $produk['harga']; ?>">
                <input type="hidden" name="stok" value="<?= $produk['stok']; ?>">
                <input type="hidden" name="gambar" value="<?= $produk['gambar']; ?>">
                <input type="hidden" name="ukuran" id="selected_size">
                
                <!-- Input jumlah produk -->
                <label for="jumlah">Jumlah:</label>
                <input type="number" name="jumlah" value="1" min="1" required>
                <button type="submit" onclick="setUkuran()">Beli Sekarang</button>
            </form>

            <!-- Deskripsi produk -->
            <h4>Deskripsi Produk</h4>
            <span><?= nl2br(htmlspecialchars($produk['deskripsi'])); ?></span>
        </div>
    </section>

    <!-- JavaScript untuk memilih ukuran produk -->
    <script>
        function setUkuran() {
            let ukuranSelect = document.getElementById('ukuran');
            let selectedSize = ukuranSelect.options[ukuranSelect.selectedIndex].value;
            document.getElementById('selected_size').value = selectedSize;
        }
    </script>

</body>
</html>
