<!-- Nama File: resi_detail.php -->
<!-- Deskripsi: file ini digunakan untuk menampilkan detail resi dengan memverifikasi login pengguna, mengambil data dari database, dan menampilkan informasi dalam format HTML --> 
<!-- Dibuat oleh: Aulia Salsabilla - NIM: 3312401021 -->
<!-- Tanggal: 10-11-2024 --> 



<?php // Mulai script PHP
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
$username = mysqli_real_escape_string($koneksi, $_SESSION['username']); // Sanitasi data username
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

// Variabel untuk ID Resi dan informasi produk
$id_resi = $nama_produk = $ukuran = $jumlah = $harga = $total_harga = 0;
// Mendeklarasikan atau memproses variabel

// Pastikan data diterima dari form
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    if (isset($_POST['id_produk'], $_POST['nama_produk'], $_POST['harga'], $_POST['stok'], $_POST['gambar'], $_POST['ukuran'], $_POST['jumlah'])) {
// Mengecek apakah variabel tertentu sudah diset (digunakan dalam validasi)
        $id_produk = mysqli_real_escape_string($koneksi, $_POST['id_produk']);
// Mendeklarasikan atau memproses variabel
        $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
// Mendeklarasikan atau memproses variabel
        $harga = (float)$_POST['harga'];
// Mendeklarasikan atau memproses variabel
        $stok = (int)$_POST['stok'];
// Mendeklarasikan atau memproses variabel
        $gambar = mysqli_real_escape_string($koneksi, $_POST['gambar']);
// Mendeklarasikan atau memproses variabel
        $ukuran = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
// Mendeklarasikan atau memproses variabel
        $jumlah = (int)$_POST['jumlah'];
// Mendeklarasikan atau memproses variabel

        // Membuat ID Resi unik menggunakan timestamp dan ID produk
        $id_resi = "RESI" . strtoupper(substr(md5(time() . $id_produk), 0, 8)); // Contoh: RESI12AB34
// Mendeklarasikan atau memproses variabel

        // Hitung total harga
        $total_harga = $harga * $jumlah;
// Mendeklarasikan atau memproses variabel

        // Cek stok produk
        if ($stok >= $jumlah) {
            // Simpan data resi ke database
            $alamat = mysqli_real_escape_string($koneksi, $user['alamat']); // Gunakan alamat dari hasil query
// Mendeklarasikan atau memproses variabel
            $query_insert_resi = "INSERT INTO resi_pembelian (id_resi, id_produk, nama_produk, ukuran, jumlah, harga, total_harga, username, alamat) 
                                  VALUES ('$id_resi', '$id_produk', '$nama_produk', '$ukuran', '$jumlah', '$harga', '$total_harga', '$username', '$alamat')";

            if (mysqli_query($koneksi, $query_insert_resi)) {
// Menjalankan query SQL untuk mengambil data dari database
                // Update stok produk setelah pembelian berhasil
                $new_stok = $stok - $jumlah;  // Kurangi stok berdasarkan jumlah yang dibeli
// Mendeklarasikan atau memproses variabel
                $query_update_stok = "UPDATE produk SET stok = '$new_stok' WHERE id_produk = '$id_produk'";
// Mendeklarasikan atau memproses variabel

                if (mysqli_query($koneksi, $query_update_stok)) {
// Menjalankan query SQL untuk mengambil data dari database
                    // Jika berhasil mengupdate stok, tampilkan ID Resi pada invoice
                    echo "<script>alert('Pembelian berhasil! ID Resi: $id_resi');</script>";
                } else {
                    // Jika gagal mengupdate stok, rollback perubahan
                    echo "<script>alert('Terjadi kesalahan saat mengupdate stok produk!'); window.location.href = 'menu_utama.php';</script>";
                }
            } else {
                echo "<script>alert('Terjadi kesalahan saat menyimpan data resi!'); window.location.href = 'menu_utama.php';</script>";
            }
        } else {
            echo "<script>alert('Stok tidak cukup untuk pembelian ini!'); window.location.href = 'menu_utama.php';</script>";
        }
    } else {
        echo "<script>alert('Data produk tidak lengkap!'); window.location.href = 'menu_utama.php';</script>";
    }
} else {
    echo "<script>alert('Data produk tidak valid!'); window.location.href = 'menu_utama.php';</script>";
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>AStore - Resi</title>
  <link rel="stylesheet" href="resi.css">
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

      <label for="name">Nama Lengkap:</label>
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
          <tr>
            <td><?php echo htmlspecialchars($nama_produk); ?></td>
            <td><?php echo htmlspecialchars($ukuran); ?></td>
            <td><?php echo htmlspecialchars($jumlah); ?></td>
            <td>Rp <?php echo number_format($harga, 0, ',', '.'); ?></td>
            <td>Rp <?php echo number_format($harga * $jumlah, 0, ',', '.'); ?></td>
          </tr>
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
