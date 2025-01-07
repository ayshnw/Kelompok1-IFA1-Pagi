<?php
session_start();
include 'koneksi.php';

if (isset($_POST['id_keranjang'])) {
    $id_keranjang = intval($_POST['id_keranjang']);
    $username = $_SESSION['username'];

    // Hapus produk dari keranjang
    $query = mysqli_query($koneksi, "DELETE FROM keranjang WHERE id = $id_keranjang AND username = '$username'");
    if (!$query) {
        die("Gagal menghapus produk: " . mysqli_error($koneksi));
    }
}
?>
