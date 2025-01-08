<!-- Nama File: delete_about_us.php -->
<!-- Deskripsi: File ini mengelola hapus data untuk tentang Kami -->
<!-- Dibuat oleh: Raid Aqil Athallah - NIM: 3312401022 -->
<!-- Tanggal: 25 November 2024-->
<!--  
<!--DECLARE db_connection AS DatabaseConnection
DECLARE about_us AS LIST
DECLARE id AS INTEGER
DECLARE nama AS STRING
DECLARE gambar AS IMAGE

db_connection = OPEN CONNECTION TO 'astore'

IF db_connection IS NOT NULL THEN 
    EXECUTE QUERY 'SELECT * FROM about_us' INTO about_us

FOR about_us IN about_us DO
    OUTPUT "Id: " +id
    OUTPUT "Nama: " +nama
    OUTPUT "Gambar: " +gambar
ENDFOR

IF about_us IS NOT NULL THEN
    EXECUTE QUERY 'DELETE * FROM about_us WHERE id=id'
    OUTPUT "Data berhasil dihapus"
ELSE
    OUTPUT "Data gagal dihapus"
ENDIF

ELSE
    OUTPUT "Gagal koneksi ke database"
ENDIF

CLOSE db_connection
-->


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
