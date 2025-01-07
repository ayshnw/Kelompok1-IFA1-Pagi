<!-- Nama File: add_about_us.php -->
<!-- Deskripsi: File ini mengelola tambah data untuk tentang Kami -->
<!-- Dibuat oleh: Raid Aqil Athallah - NIM: 3312401022 -->
<!-- Tanggal: 9 November 2024-->

<?php
// Menyertakan file koneksi ke database
include 'koneksi.php';

// Memeriksa apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari input form (nama) dan mengamankannya dengan htmlspecialchars
    $nama = htmlspecialchars($_POST['nama']);

    // Memeriksa apakah file gambar diunggah dan tidak ada error saat upload
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']; // Menyimpan informasi file yang diunggah
        $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION); // Mendapatkan ekstensi file gambar
        $fileName = uniqid() . '.' . $ext; // Membuat nama file unik menggunakan uniqid()
        $uploadPath = 'uploads/' . $fileName; // Menentukan lokasi penyimpanan file gambar

        // Memindahkan file gambar yang diunggah ke folder tujuan
        if (move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
            // Query untuk menyimpan data ke tabel 'about_us' di database
            $query = "INSERT INTO about_us (nama, gambar) VALUES ('$nama', '$fileName')";

            // Mengeksekusi query dan memberikan pesan notifikasi sesuai hasil eksekusi
            if (mysqli_query($koneksi, $query)) {
                // Jika data berhasil ditambahkan ke database
                $_SESSION['message'] = "Data berhasil ditambahkan!";
                $_SESSION['type'] = "success"; // Jenis notifikasi: sukses
            } else {
                // Jika terjadi kesalahan saat menyimpan data ke database
                $_SESSION['message'] = "Terjadi kesalahan pada database.";
                $_SESSION['type'] = "danger"; // Jenis notifikasi: error
            }
        } else {
            // Jika file gagal diunggah ke folder tujuan
            $_SESSION['message'] = "Gagal mengunggah gambar.";
            $_SESSION['type'] = "danger"; // Jenis notifikasi: error
        }
    } else {
        // Jika file gambar tidak ditemukan atau terjadi error saat upload
        $_SESSION['message'] = "Gambar tidak ditemukan.";
        $_SESSION['type'] = "danger"; // Jenis notifikasi: error
    }
}

// Mengarahkan pengguna kembali ke halaman about_us.php
header('Location: about_us.php');
