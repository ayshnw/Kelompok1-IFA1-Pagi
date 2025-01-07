<?php
session_start();
include 'koneksi.php';

if (isset($_POST['id_keranjang']) && isset($_POST['jumlah'])) {
    $id_keranjang = intval($_POST['id_keranjang']);
    $jumlah = intval($_POST['jumlah']);
    $username = $_SESSION['username'];

    if ($jumlah > 0) {
        $query = mysqli_query($koneksi, "UPDATE keranjang SET jumlah = $jumlah WHERE id = $id_keranjang AND username = '$username'");
        if (!$query) {
            die("Gagal memperbarui keranjang: " . mysqli_error($koneksi));
        }
    } else {
        // Jika jumlah produk 0, hapus produk dari keranjang
        $query = mysqli_query($koneksi, "DELETE FROM keranjang WHERE id = $id_keranjang AND username = '$username'");
        if (!$query) {
            die("Gagal menghapus produk: " . mysqli_error($koneksi));
        }
    }
}
?>
