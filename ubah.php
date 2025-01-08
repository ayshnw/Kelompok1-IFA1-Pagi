<!--
// Nama File: ubah.php
// Deskripsi: File ini merupakan file tambahan untuk file produk.php
// Dibuat oleh: Dionaldi Sion Yosua - NIM: 3312401011
// Tanggal: 02 November 2024

DECLARE db_connection AS DatabaseConnection  
DECLARE id_produk AS STRING  
DECLARE nama_produk AS STRING  
DECLARE ukuran AS STRING  
DECLARE deskripsi AS STRING  
DECLARE harga AS FLOAT  
DECLARE stok AS INT  
DECLARE gambar AS FILE  
DECLARE upload_dir AS STRING  
DECLARE upload_path AS STRING  
DECLARE query AS STRING  

// Membuka koneksi ke database
db_connection = OPEN CONNECTION TO 'astore'  

// Input dari pengguna
INPUT id_produk  
INPUT nama_produk  
INPUT ukuran  
INPUT deskripsi  
INPUT harga  
INPUT stok  
INPUT gambar  

// Cek apakah koneksi ke database berhasil
IF db_connection IS NOT NULL THEN  
    // Proses upload gambar
    upload_dir = "uploads/"  
    upload_path = upload_dir + BASE_NAME(gambar)  
    
    IF MOVE_FILE(gambar, upload_path) THEN  
        // Jika gambar berhasil diupload, buat query untuk menambahkan produk
        query = 'INSERT INTO produk (id_produk, nama_produk, ukuran, deskripsi, harga, stok, gambar) 
                 VALUES (id_produk, nama_produk, ukuran, deskripsi, harga, stok, gambar)'  
        
        // Eksekusi query
        IF EXECUTE_QUERY(query) THEN  
            DISPLAY "Produk berhasil ditambahkan!"  
        ELSE  
            DISPLAY "Gagal menambahkan produk!"  
        END IF  
    ELSE  
        DISPLAY "Gagal mengupload gambar."  
    END IF  
ELSE  
    DISPLAY "Gagal koneksi ke database!"  
END IF  

// Menutup koneksi database
CLOSE db_connection
-->

<?php
session_start(); // Mulai session untuk menyimpan pesan dan status
include 'koneksi.php'; // Mengimpor file koneksi untuk terhubung ke database

// Cek apakah request method adalah POST (form dikirim)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari form dan melakukan sanitasi untuk mencegah SQL Injection
    $id_produk = $_POST['id_produk'];
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $ukuran = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];

    // Query untuk memperbarui data produk tanpa gambar
    $query = "UPDATE produk SET nama_produk='$nama_produk', ukuran='$ukuran', deskripsi='$deskripsi', harga='$harga', stok='$stok' WHERE id_produk='$id_produk'";

    // Jika ada gambar baru yang diunggah
    if (!empty($_FILES['gambar']['name'])) {
        // Menyimpan nama gambar dan menentukan lokasi folder penyimpanan
        $gambar = basename($_FILES['gambar']['name']);
        $upload_dir = "uploads/"; // Folder untuk menyimpan gambar
        $upload_path = $upload_dir . $gambar;

        // Cek apakah file gambar berhasil diunggah
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
            // Jika berhasil, masukkan gambar baru ke query untuk memperbarui produk
            $query = "UPDATE produk SET nama_produk='$nama_produk', ukuran='$ukuran', deskripsi='$deskripsi', harga='$harga', stok='$stok', gambar='$gambar' WHERE id_produk='$id_produk'";
        } else {
            // Jika gagal mengunggah gambar, set pesan error dan redirect ke produk.php
            $_SESSION['message'] = "Gagal mengunggah gambar.";
            $_SESSION['type'] = "danger";
            header('Location: produk.php'); // Redirect ke halaman produk
            exit(); // Menghentikan eksekusi kode setelah redirect
        }
    }

    // Menjalankan query untuk memperbarui data produk di database
    if (mysqli_query($koneksi, $query)) {
        // Jika query berhasil, set pesan sukses dan tipe notifikasi success
        $_SESSION['message'] = "Produk berhasil diperbarui!";
        $_SESSION['type'] = "success";
    } else {
        // Jika query gagal, set pesan error dan tipe notifikasi danger
        $_SESSION['message'] = "Gagal memperbarui produk: " . mysqli_error($koneksi);
        $_SESSION['type'] = "danger";
    }

    // Redirect kembali ke halaman produk setelah proses selesai
    header('Location: produk.php');
    exit(); // Menghentikan eksekusi setelah redirect
}
?>
