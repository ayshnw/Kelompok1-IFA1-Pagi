<!--
// Nama File: hapus.php
// Deskripsi: File ini merupakan file tambahan untuk file produk.php
// Dibuat oleh: Dionaldi Sion Yosua - NIM: 3312401011
// Tanggal: 02 November 2024
-->

<?php
session_start(); // Mulai session
include 'koneksi.php'; // Untuk melakukan koneksi ke database

// Periksa apakah ada parameter 'id_produk' dalam URL
if (isset($_GET['id_produk']) && is_numeric($_GET['id_produk'])) {
    $id = $_GET['id_produk'];

    // Mengambil gambar produk untuk dihapus
    $query = "SELECT gambar FROM produk WHERE id_produk = $id";
    $result = mysqli_query($koneksi, $query);
    
    // Pastikan query berhasil dan produk ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $produk = mysqli_fetch_assoc($result);

        // Hapus gambar produk jika file ada
        if ($produk && file_exists("uploads/" . $produk['gambar'])) {
            unlink("uploads/" . $produk['gambar']); // Hapus file gambar
        }

        // Hapus data produk
        $query = "DELETE FROM produk WHERE id_produk = $id";
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['message'] = "Produk berhasil dihapus!";
            $_SESSION['type'] = "success"; // Tipe notifikasi (success, danger, dll)
        } else {
            $_SESSION['message'] = "Gagal menghapus produk: " . mysqli_error($koneksi);
            $_SESSION['type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Produk tidak ditemukan!";
        $_SESSION['type'] = "danger";
    }

    // Redirect ke halaman produk setelah proses selesai
    header('Location: produk.php');
    exit();
} else {
    // Jika id_produk tidak ada atau tidak valid
    $_SESSION['message'] = "ID Produk tidak valid!";
    $_SESSION['type'] = "danger";
    header('Location: produk.php');
    exit();
}
?>