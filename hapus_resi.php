<!--
// Nama File: hapus_resi.php
// Deskripsi: File ini bertujuan untuk menghapus resi
// Dibuat oleh: Aisyah Nurwa Hida - NIM: 3312401004
// Tanggal: 16 Desember 2024
-->


<?php
// Mulai session
session_start();

// Menghubungkan ke database
include 'koneksi.php';

// Periksa apakah ID Resi dikirimkan
if (isset($_GET['id_resi']) && !empty($_GET['id_resi'])) {
    $id_resi = intval($_GET['id_resi']); // Pastikan ID Resi adalah integer

    // Query untuk menghapus data
    $query = "DELETE FROM resi_pembelian WHERE id_resi = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, 'i', $id_resi);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute && mysqli_stmt_affected_rows($stmt) > 0) {
            // Berhasil menghapus data
            $_SESSION['message'] = "Data berhasil dihapus.";
            $_SESSION['type'] = "success";
        } else {
            // Data tidak ditemukan atau gagal menghapus
            $_SESSION['message'] = "Data tidak ditemukan atau gagal dihapus.";
            $_SESSION['type'] = "warning";
        }
        mysqli_stmt_close($stmt);
    } else {
        // Query gagal dipersiapkan
        $_SESSION['message'] = "Terjadi kesalahan pada sistem saat mempersiapkan query.";
        $_SESSION['type'] = "danger";
    }
} else {
    // Jika ID Resi tidak ditemukan atau kosong
    $_SESSION['message'] = "ID Resi tidak ditemukan atau tidak valid.";
    $_SESSION['type'] = "warning";
}

// Redirect kembali ke halaman utama
header("Location: kelola_pesanan.php");
exit();
