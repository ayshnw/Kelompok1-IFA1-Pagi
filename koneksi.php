<?php 
$host = "localhost"; 	 
$user = 'root'; 	 
$pass = ""; 	 
$db = "astore"; //Nama Database 	 

// melakukan koneksi ke db 	 
$koneksi = mysqli_connect($host, $user, $pass, $db); 	 

// Cek apakah koneksi berhasil
if (!$koneksi) { 	 
    die("Koneksi gagal: " . mysqli_connect_error()); // Gunakan mysqli_connect_error() untuk mendapatkan pesan kesalahan yang lebih jelas
} 
?>
