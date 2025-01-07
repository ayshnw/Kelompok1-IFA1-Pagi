<!-- Nama File: delete_about_us.php -->
<!-- Deskripsi: File ini mengelola hapus data untuk tentang Kami -->
<!-- Dibuat oleh: Raid Aqil Athallah - NIM: 3312401022 -->
<!-- Tanggal: 25 November 2024-->


<?php
// Menghubungkan file koneksi database untuk dapat menggunakan koneksi ke database
include 'koneksi.php';

// Mengecek apakah parameter 'id' tersedia di URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Mengambil ID dari parameter URL dan mengubahnya menjadi integer untuk keamanan

    // Query untuk mengambil data gambar berdasarkan ID
    $query = mysqli_query($koneksi, "SELECT gambar FROM about_us WHERE id = '$id'");
    $data = mysqli_fetch_assoc($query); // Mendapatkan data hasil query sebagai array asosiatif
    $gambar = $data['gambar']; // Menyimpan nama file gambar dari database

    // Query untuk menghapus data dari tabel about_us berdasarkan ID
    if (mysqli_query($koneksi, "DELETE FROM about_us WHERE id = '$id'")) {
        // Jika query berhasil, hapus file gambar dari folder uploads
        if (file_exists("uploads/$gambar")) { // Mengecek apakah file gambar ada di folder
            // Menghapus file gambar dari server
            unlink("uploads/$gambar");
        }
        // Menyimpan pesan keberhasilan penghapusan dalam session
        $_SESSION['message'] = "Data berhasil dihapus!";
        // Jenis pesan sukses
        $_SESSION['type'] = "success";
    } else {
        // Jika query gagal, simpan pesan kesalahan dalam session
        $_SESSION['message'] = "Terjadi kesalahan saat menghapus data.";
        // Jenis pesan gagal
        $_SESSION['type'] = "danger";
    }
}

// Mengarahkan kembali ke halaman about_us.php
header('Location: about_us.php');
