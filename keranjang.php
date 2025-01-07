<!--
// Nama File: login.php
// Deskripsi: File ini bertujuan untuk menyediakan antarmuka yang memungkinkan pengguna menambahkan barang ke wishlist atau 
              keranjang belanja.
// Dibuat oleh: Steven Marcell Samosir - NIM: 3312401003
// Tanggal: 02 Desember
-->

<?php

// Memulai session
session_start();

// Menyambungkan ke dalam database
include 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Menggunakan username dari session
$username = $_SESSION['username'];

// Ambil data keranjang dari database beserta informasi produk (ukuran, stok, dan gambar)
$query_keranjang = mysqli_query($koneksi, "
    SELECT k.*, p.ukuran AS ukuran_tersedia, p.stok AS stok_tersedia, p.gambar
    FROM keranjang k
    JOIN produk p ON k.nama_produk = p.nama_produk
    WHERE k.username = '$username'
");

// Inisialisasi variabel $cartItems
$cartItems = [];
$total = 0;

while ($row = mysqli_fetch_assoc($query_keranjang)) {
    $cartItems[] = $row;
}

// Menambahkan produk ke keranjang
if (isset($_POST['tambah_keranjang'])) {
    $produk_id = intval($_POST['produk_id']);
    $jumlah = intval($_POST['jumlah']);
    $ukuran = isset($_POST['ukuran']) ? mysqli_real_escape_string($koneksi, $_POST['ukuran']) : '';
    // Ambil data produk dari database berdasarkan produk_id
    $query_produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE id_produk = '$produk_id'");
    $produk = mysqli_fetch_assoc($query_produk);

    if ($produk) {
        $nama_produk = $produk['nama_produk'];
        $harga = $produk['harga'];
        $stok = $produk['stok'];

        // Periksa apakah stok mencukupi
        if ($stok >= $jumlah) {
            $query_keranjang = mysqli_query($koneksi, "SELECT * FROM keranjang WHERE username = '$username'
                                            AND nama_produk = '$nama_produk' AND ukuran = '$ukuran'");

            if (mysqli_num_rows($query_keranjang) > 0) {
                // Jika produk sudah ada di keranjang, update jumlah
                $item = mysqli_fetch_assoc($query_keranjang);
                $jumlah_baru = $item['jumlah'] + $jumlah;

                mysqli_query($koneksi, "UPDATE keranjang SET jumlah = '$jumlah_baru' WHERE 
                            produk_id = '{$item['id_produk']}'");
            } else {
                // Jika produk belum ada di keranjang, tambahkan produk baru
                mysqli_query($koneksi, "INSERT INTO keranjang (username, produk_id, nama_produk, ukuran, 
                            harga, jumlah) VALUES ('$username', '$produk_id', '$nama_produk', '$ukuran', 
                            '$harga', '$jumlah')");
            }
            echo "<script>
                    alert('Produk berhasil ditambahkan ke keranjang!'); window.location.href='keranjang.php';
            </script>";
        } else {
            echo "<script>alert('Stok tidak mencukupi!'); window.location.href='keranjang.php';</script>";
        }
    } else {
        echo "<script>alert('Produk tidak ditemukan!'); window.location.href='keranjang.php';</script>";
    }
}

// Hapus item dari keranjang
if (isset($_POST['hapus_item'])) {
    $id_produk = intval($_POST['hapus_item']);
    mysqli_query($koneksi, "DELETE FROM keranjang WHERE produk_id = '$id_produk'");
    echo "<script>window.location.href='keranjang.php';</script>";
}

// Cek apakah nama pengguna sudah terisi di profil
$query_user = "SELECT nama FROM users WHERE username = '$username'";
$result_user = mysqli_query($koneksi, $query_user);
$user = mysqli_fetch_assoc($result_user);

if (isset($_POST['bayar']) && empty($user['nama'])) {
    // Jika nama kosong dan menekan tombol bayar, arahkan pengguna ke halaman profil untuk mengisi nama
    echo "<script>
            alert('Silakan lengkapi data profil Anda terlebih dahulu!');
            window.location.href = 'profile.php'; // Arahkan ke halaman profil untuk mengisi nama
          </script>";
    exit();
}

// Proses pembayaran ketika tombol bayar ditekan
if (isset($_POST['bayar'])) {
    if (isset($_POST['pilih_bayar'])) {
        $totalBayar = 0;
        // Hitung total harga untuk produk yang dipilih
        $totalBayar += $item['harga'] * $item['jumlah'];
        // Tampilkan total yang harus dibayar
        echo "<script>
        alert('Total yang harus dibayar: Rp " . number_format($totalBayar, 0, ',', '.') . "');
        window.location.href = 'resi.php'; // Arahkan ke halaman resi atau proses pembayaran
        </script>";
    } else {
        echo "<script>
                alert('Silakan pilih produk yang ingin dibayar.');
                window.location.href = 'keranjang.php';
              </script>";
    }
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AStore</title>
    <!-- CSS link -->
    <link rel="stylesheet" href="keranjang.css">
    <!-- Link extension lain -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Jost:ital,wght@0,100..900;1,100..900&family=Parkinsans:wght@500&display=swap">
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

    <div class="shopping-cart">
    <h1>Keranjang Anda</h1>
    <form method="POST" action="">
        <?php if (count($cartItems) > 0): ?>
            <form method="POST" action="resi.php">
    <label>
        <input type="checkbox" id="select_all" onchange="selectAllItems(this)">
        Pilih Semua
    </label>
    <?php foreach ($cartItems as $item): ?>
        <div class="cart-item">
            <img src="uploads/<?php echo $item['gambar']; ?>" alt="<?php echo $item['nama_produk']; ?>">
            <div class="item-details">
                <h2><?php echo $item['nama_produk']; ?></h2>
                <p>Harga per unit: Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></p>
                <p>Ukuran: <?php echo htmlspecialchars($item['ukuran']); ?></p>
                <p>Stok Tersedia: <?php echo $item['stok_tersedia']; ?></p>
                <label for="jumlah_<?php echo $item['produk_id']; ?>">Jumlah:</label>
                <input 
                    type="number" 
                    id="jumlah_<?php echo $item['produk_id']; ?>" 
                    name="jumlah[<?php echo $item['produk_id']; ?>]" 
                    value="<?php echo $item['jumlah']; ?>" 
                    min="1" 
                    max="<?php echo $item['stok_tersedia']; ?>" 
                    onchange="updateHarga(<?php echo $item['produk_id']; ?>, <?php echo $item['harga']; ?>)"
                />
            </div>
            <div class="price" id="total_harga_<?php echo $item['produk_id']; ?>" data-harga-per-unit="<?php echo $item['harga']; ?>">
                Rp <?php echo number_format($item['harga'] * $item['jumlah'], 0, ',', '.'); ?>
            </div>
            <input type="checkbox" name="selected_items[]" value="<?php echo $item['produk_id']; ?>" class="select-item" 
                    onchange="updateSubtotal()">
            <form method="POST" action="">
                <button type="submit" name="hapus_item" value="<?php echo $item['produk_id']; ?>" class="delete-button">Hapus</button>
            </form>
        </div>
            <?php endforeach; ?>
            <form method="POST" action="resi.php">
                <div class="summary">
                    <p class="subtotal">Total: <span class ="price" id="total_semua">Rp 0</span></p>
                    <button type="submit" class="proceed-to-buy" name="bayar">Bayar</button>
                </div>
            </form>
        <?php else: ?>
            <p>Keranjang Anda kosong.</p>
        <?php endif; ?>
    </form>
</div>

    <script>

        function updateHarga(itemId, hargaPerUnit) {
            const jumlahInput = document.getElementById('jumlah_' + itemId);
            const totalHarga = document.getElementById('total_harga_' + itemId);
            const totalSemua = document.getElementById('total_semua');

            const jumlah = parseInt(jumlahInput.value) || 0;
            const totalItem = jumlah * hargaPerUnit;
            totalHarga.innerText = 'Rp ' + totalItem.toLocaleString('id-ID');

            let totalKeseluruhan = 0;
            document.querySelectorAll('.cart-item').forEach((item) => {
                const jumlahItem = parseInt(item.querySelector('input[type=number]').value) || 0;
                const hargaItem = parseInt(item.querySelector('.price').dataset.hargaPerUnit) || 0;
                totalKeseluruhan += jumlahItem * hargaItem;
            });

            totalSemua.innerText = 'Rp ' + totalKeseluruhan.toLocaleString('id-ID');
        }

        document.querySelectorAll('.select-item').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
            // Update subtotal saat checkbox dipilih atau dibatalkan
            updateSubtotal();
            });
        });

        function updateSubtotal() {
            let totalKeseluruhan = 0;

            // Ambil semua checkbox yang dicentang
            document.querySelectorAll('.select-item:checked').forEach(function(checkbox) {
            // Ambil id produk yang dipilih
            const itemId = checkbox.value;

            // Ambil harga dan jumlah untuk item yang dipilih
            const hargaPerUnit = parseInt(document.querySelector('#total_harga_' + itemId).dataset.hargaPerUnit);
            const jumlah = parseInt(document.querySelector('input[name="jumlah[' + itemId + ']"]').value);

            // Hitung harga untuk item yang dipilih
            totalKeseluruhan += hargaPerUnit * jumlah;
        });

        // Update total harga di bawah
        document.getElementById('total_semua').innerText = 'Rp ' + totalKeseluruhan.toLocaleString('id-ID');
}

        // Fungsi untuk memilih atau membatalkan semua item
        function selectAllItems(selectAllCheckbox) {
        const allCheckboxes = document.querySelectorAll('.select-item');
        allCheckboxes.forEach(function(checkbox) {
            checkbox.checked = selectAllCheckbox.checked;  // Set status checkbox berdasarkan checkbox "Pilih Semua"
        });

        // Update subtotal setelah memilih atau membatalkan semua item
        updateSubtotal();
}
</script>
</body>
</html>
