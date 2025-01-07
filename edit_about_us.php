<!-- Nama File: edit_about_us.php -->
<!-- Deskripsi: File ini mengelola edit data untuk tentang Kami -->
<!-- Dibuat oleh: Raid Aqil Athallah - NIM: 3312401022 -->
<!-- Tanggal: 16 November 2024-->

<?php
include 'koneksi.php'; // Menghubungkan ke file koneksi untuk database

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Memeriksa apakah request yang diterima adalah POST
    $id = intval($_POST['id']); // Mengambil ID dari data POST dan memastikan berupa angka
    $nama = htmlspecialchars($_POST['nama']); // Mengambil nama dari data POST dan mencegah XSS dengan htmlspecialchars
    $gambarLama = $_POST['gambar_lama']; // Mengambil nama file gambar lama dari data POST
    $fileName = $gambarLama; // Default nama file tetap gambar lama

    // Mengecek apakah ada file gambar baru yang diunggah
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = $_FILES['gambar']; // Mendapatkan informasi file gambar
        $ext = pathinfo($gambar['name'], PATHINFO_EXTENSION); // Mendapatkan ekstensi file
        $fileName = uniqid() . '.' . $ext; // Membuat nama file unik dengan ekstensi yang sama
        $uploadPath = 'uploads/' . $fileName; // Menentukan lokasi penyimpanan file di folder "uploads"

        // Memindahkan file yang diunggah ke folder tujuan
        if (move_uploaded_file($gambar['tmp_name'], $uploadPath)) {
            // Jika gambar lama ada di server, hapus file tersebut
            if (file_exists("uploads/$gambarLama")) {
                unlink("uploads/$gambarLama"); // Menghapus file gambar lama
            }
        } else {
            // Jika gagal mengunggah file baru, kirim pesan error ke sesi
            $_SESSION['message'] = "Gagal mengunggah gambar baru.";
            $_SESSION['type'] = "danger";
            header('Location: about_us.php'); // Redirect kembali ke halaman about_us.php
            exit; // Hentikan eksekusi
        }
    }

    // Membuat query untuk memperbarui data di tabel about_us
    $query = "UPDATE about_us SET nama='$nama', gambar='$fileName' WHERE id='$id'";
    if (mysqli_query($koneksi, $query)) { // Mengeksekusi query
        $_SESSION['message'] = "Data berhasil diperbarui!"; // Pesan jika berhasil
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['message'] = "Terjadi kesalahan pada database."; // Pesan jika terjadi error pada database
        $_SESSION['type'] = "danger";
    }
}

// Mengarahkan pengguna kembali ke halaman about_us.php
header('Location: about_us.php');
