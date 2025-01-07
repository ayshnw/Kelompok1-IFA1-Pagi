<!--
// Nama File: logout.php
// Deskripsi:fitur untuk keluar dari akun dan menghentikan session
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 09 Desember 2024
-->
<?php
session_start();

session_unset();
session_destroy();  

header("Location: halaman_pertama.php");
exit(); 
?>