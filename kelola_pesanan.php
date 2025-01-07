<!--
// Nama File: kelola_pesanan.php
// Deskripsi: File ini bertujuan untuk mengelola pesanan yang ada di database resi
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 16 Desember 2024
-->

<?php
// Menghubungkan file header dan navbar
include('includes/header.php'); 
include('includes/navbar.php');

// Menghubungkan ke database
include 'koneksi.php';

// Memulai sesi untuk menampilkan notifikasi
session_start(); 
if (isset($_SESSION['message'])) { // Cek apakah ada pesan dalam sesi
    $message = $_SESSION['message']; // Simpan pesan
    $type = $_SESSION['type']; // Simpan tipe pesan (contoh: success, danger)
    echo "
    <div class='alert alert-$type alert-dismissible fade show' role='alert'>
        $message
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";
    unset($_SESSION['message'], $_SESSION['type']); // Hapus notifikasi dari sesi setelah ditampilkan
}
?>

<!-- Container Utama -->
<div class="container">
    <h1>Kelola Pesanan</h1>
    <!-- Tombol untuk mengekspor data ke Excel -->
    <a href="export_excel.php" class="btn btn-success mb-3">Ekspor ke Excel</a>

    <!-- Tabel untuk menampilkan data pesanan -->
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID Resi</th>
                <th>Nama Pembeli</th>
                <th>Alamat</th>
                <th>Nama produk</th>
                <th>Ukuran</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Total Harga</th>
                <th>Tanggal pembelian</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil data dari tabel resi_pembelian
            $query = mysqli_query($koneksi, "SELECT * FROM resi_pembelian");

            // Loop untuk menampilkan data dalam baris tabel
            while ($data = mysqli_fetch_assoc($query)) { ?>
                <tr>
                    <!-- Menampilkan data pesanan dengan perlindungan htmlspecialchars -->
                    <td><?= htmlspecialchars($data['id_resi']); ?></td>
                    <td><?= htmlspecialchars($data['username']); ?></td>
                    <td><?= htmlspecialchars($data['alamat']); ?></td>
                    <td><?= htmlspecialchars($data['nama_produk']); ?></td>
                    <td><?= htmlspecialchars($data['ukuran']); ?></td>
                    <td><?= htmlspecialchars($data['jumlah']); ?></td>
                    <td><?= htmlspecialchars($data['harga']); ?></td>
                    <td><?= htmlspecialchars($data['total_harga']); ?></td>
                    <td><?= htmlspecialchars($data['waktu']); ?></td>
                    <td>
                        <!-- Tombol Hapus dengan konfirmasi -->
                        <a href="hapus_resi.php?id_resi=<?= htmlspecialchars($data['id_resi']); ?>" 
                           class="btn btn-danger btn-sm" 
                           onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<!-- Memuat script Bootstrap untuk fungsi interaktif -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
