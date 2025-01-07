<!-- Nama File: resi.php -->
<!-- Deskripsi: file ini digunakan untuk memproses dan menampilkan data resi pengiriman dengan validasi login pengguna, pengambilan data dari database, dan menampilkan hasilnya dalam format HTML yang dinamis --> 
<!-- Dibuat oleh: Aulia Salsabilla - NIM: 3312401021 -->
<!-- Tanggal: 5-11-2024 --> 

<?php
session_start();
// Memulai sesi untuk menyimpan data pengguna sementara
include 'koneksi.php';

// Cek apakah pengguna sudah login dan memiliki username di sesi
if (!isset($_SESSION['username'])) {
// Mengecek apakah variabel tertentu sudah diset (digunakan dalam validasi)
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href = 'login.php';</script>";
    exit;
}

// Ambil data pengguna berdasarkan username yang ada di sesi
$username = mysqli_real_escape_string($koneksi, $_SESSION['username']);
// Mendeklarasikan atau memproses variabel
$query_user = "SELECT nama, alamat FROM users WHERE username = '$username'";
// Mendeklarasikan atau memproses variabel
$result_user = mysqli_query($koneksi, $query_user);
// Menjalankan query SQL untuk mengambil data dari database

// Periksa jika query berhasil dan data ditemukan
if (!$result_user || mysqli_num_rows($result_user) == 0) {
    echo "<script>alert('Data pengguna tidak ditemukan!'); window.location.href = 'profile.php';</script>";
    exit;
}

// Ambil hasil query dan simpan ke dalam array $user
$user = mysqli_fetch_assoc($result_user);
// Mendeklarasikan atau memproses variabel

// Cek apakah data nama dan alamat ada dalam array $user
if (empty($user['nama']) || empty($user['alamat'])) {
    echo "<script>alert('Lengkapi data diri Anda, termasuk nama dan alamat!'); window.location.href = 'profile.php';</script>";
    exit;
}

// Ambil data keranjang pengguna
$query_keranjang = "
    SELECT k.nama_produk, k.jumlah, k.ukuran, p.harga, (k.jumlah * p.harga) AS subtotal
    FROM keranjang k
    JOIN produk p ON k.nama_produk = p.nama_produk
    WHERE k.username = '$username'
";

$result_keranjang = mysqli_query($koneksi, $query_keranjang);
// Menjalankan query SQL untuk mengambil data dari database

// Jika keranjang kosong, tampilkan pesan
if (!$result_keranjang || mysqli_num_rows($result_keranjang) === 0) {
    die('<p>Keranjang Anda kosong. Tidak ada resi yang dapat dibuat.</p>');
}

// Inisialisasi total harga
$total_harga = 0;
// Mendeklarasikan atau memproses variabel

// Membuat ID Resi unik menggunakan random_bytes untuk ID yang lebih aman
do {
  $id_resi = "RESI" . strtoupper(bin2hex(random_bytes(16))); // ID yang lebih panjang dan acak
  // Periksa apakah ID Resi sudah ada dalam database
  $query_check_resi = "SELECT id_resi FROM resi_keranjang WHERE id_resi = '$id_resi'";
  $result_check_resi = mysqli_query($koneksi, $query_check_resi);
} while (mysqli_num_rows($result_check_resi) > 0); // Periksa duplikat ID, jika ada, buat ID baru


// Proses setiap item di keranjang
while ($row = mysqli_fetch_assoc($result_keranjang)) {
    $nama_produk = $row['nama_produk'];
// Mendeklarasikan atau memproses variabel
    $ukuran = $row['ukuran'];
// Mendeklarasikan atau memproses variabel
    $jumlah = $row['jumlah'];
// Mendeklarasikan atau memproses variabel
    $harga = $row['harga'];
// Mendeklarasikan atau memproses variabel
    $subtotal = $row['subtotal'];
// Mendeklarasikan atau memproses variabel

    // Tambahkan subtotal ke total harga
    $total_harga += $subtotal;
// Mendeklarasikan atau memproses variabel

    // Cek stok produk
    $query_produk = mysqli_query($koneksi, "SELECT stok FROM produk WHERE nama_produk = '$nama_produk'");
// Menjalankan query SQL untuk mengambil data dari database
    $produk = mysqli_fetch_assoc($query_produk);
// Mendeklarasikan atau memproses variabel

    if ($produk && $produk['stok'] >= $jumlah) {
        // Simpan data resi ke database
        $alamat = mysqli_real_escape_string($koneksi, $user['alamat']);
// Mendeklarasikan atau memproses variabel
        $query_insert_resi = "INSERT INTO resi_keranjang (id_resi, nama_produk, ukuran, jumlah, harga, total_harga, username, alamat)
                              VALUES ('$id_resi', '$nama_produk', '$ukuran', '$jumlah', '$harga', '$subtotal', '$username', '$alamat')";

        if (mysqli_query($koneksi, $query_insert_resi)) {
// Menjalankan query SQL untuk mengambil data dari database
            // Update stok produk setelah pembelian berhasil
            $new_stok = $produk['stok'] - $jumlah;
// Mendeklarasikan atau memproses variabel
            $query_update_stok = "UPDATE produk SET stok = '$new_stok' WHERE nama_produk = '$nama_produk'";
// Mendeklarasikan atau memproses variabel
            mysqli_query($koneksi, $query_update_stok);
// Menjalankan query SQL untuk mengambil data dari database
        } else {
            echo "<script>alert('Terjadi kesalahan saat menyimpan data resi!'); window.location.href = 'menu_utama.php';</script>";
            exit;
        }
    } else {
        echo "<script>alert('Stok tidak cukup untuk pembelian produk: $nama_produk!'); window.location.href = 'menu_utama.php';</script>";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AStore - Resi</title>
  <link rel="stylesheet" href="resi.css">
  <style>
    .button-finish {
      display: block;
      margin: 20px auto;
      padding: 10px 20px;
      background-color: #4CAF50;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }

    .button-finish:hover {
      background-color: #45a049;
    }
  </style>
</head>
<body>
  <div class="invoice">
    <div class="invoice-header">
      <img src="ASTORE.png" alt="Logo">
      <h2>AStore</h2>
    </div>
    <hr />
    <div class="invoice-body">
      <label for="id_resi">ID Resi:</label>
      <p><?php echo htmlspecialchars($id_resi); ?></p> <!-- Menampilkan ID Resi -->

      <label for="name">Nama Pelanggan:</label>
      <p><?php echo htmlspecialchars($user['nama']); ?></p>

      <label for="alamat">Alamat:</label>
      <p><?php echo htmlspecialchars($user['alamat']); ?></p>

      <label for="rincian_harga">Rincian Pembelian:</label>
      <table border="1" cellpadding="10" cellspacing="0">
        <thead>
          <tr>
            <th>Nama Produk</th>
            <th>Ukuran</th>
            <th>Jumlah</th>
            <th>Harga per Unit</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody>
<?php // Mulai script PHP
          mysqli_data_seek($result_keranjang, 0); // Reset pointer hasil query
          while ($row = mysqli_fetch_assoc($result_keranjang)): ?>
            <tr>
              <td><?php echo htmlspecialchars($row['nama_produk']); ?></td>
              <td><?php echo htmlspecialchars($row['ukuran']); ?></td>
              <td><?php echo $row['jumlah']; ?></td>
              <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
              <td>Rp <?php echo number_format($row['subtotal'], 0, ',', '.'); ?></td>
            </tr>
            <?php endwhile; ?>
        </tbody>
      </table>

      <label for="total_harga">Total Harga:</label>
      <p>Rp <?php echo number_format($total_harga, 0, ',', '.'); ?></p>
    </div>
    <hr />
    <div class="footer">
      <p>Resi ini berfungsi sebagai bukti bahwa pembayaran Anda telah berhasil.</p>
      <p>Terima kasih telah berbelanja di AStore!</p>
      <button class="button-finish" onclick="showMessage()">Selesai</button>
    </div>
  </div>

  <script>
    function showMessage() {
        alert("Demo Selesai");
        window.location.href = 'menu_utama.php';
    }
  </script>
</body>
</html>
