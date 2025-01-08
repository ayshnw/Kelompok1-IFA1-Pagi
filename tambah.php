<!--
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
session_start(); // Pastikan session dimulai
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Tangkap input dari form
    $id_produk = $_POST['id_produk']; 
    $nama_produk = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $ukuran = mysqli_real_escape_string($koneksi, $_POST['ukuran']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $gambar = $_FILES['gambar']['name'];
    
    // Proses upload gambar
    $upload_dir = "uploads/";
    $upload_path = $upload_dir . basename($gambar);

    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
        // Query SQL untuk menambahkan produk
        $query = "INSERT INTO produk (id_produk, nama_produk, ukuran, deskripsi, harga, stok, gambar) 
                  VALUES ('$id_produk', '$nama_produk', '$ukuran', '$deskripsi', '$harga', '$stok', '$gambar')";

        if (mysqli_query($koneksi, $query)) {
            $_SESSION['message'] = "Produk berhasil ditambahkan!";
            $_SESSION['type'] = "success"; // Tipe notifikasi (success, danger, dll)
        } else {
            $_SESSION['message'] = "Gagal menambahkan produk: " . mysqli_error($koneksi);
            $_SESSION['type'] = "danger";
        }
    } else {
        $_SESSION['message'] = "Gagal mengupload gambar.";
        $_SESSION['type'] = "danger";
    }

    // Redirect kembali ke halaman produk
    header('Location: produk.php');
    exit();
}
?>
