<?php
session_start();
include 'koneksi.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['username'])) {
    echo json_encode(["success" => false, "message" => "Pengguna belum login."]);
    exit();
}

// Pastikan parameter ID tersedia dan valid
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(["success" => false, "message" => "ID produk tidak valid."]);
    exit();
}

$username = $_SESSION['username']; // Username dari session
$produk_id = intval($_GET['id']); // Pastikan ID produk berupa angka

try {
    // Mulai transaksi
    mysqli_begin_transaction($koneksi);

    // Ambil ukuran produk dari database produk
    $query_produk = mysqli_prepare($koneksi, "SELECT ukuran FROM produk WHERE id = ?");
    if (!$query_produk) {
        throw new Exception("Gagal mempersiapkan query produk: " . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($query_produk, "i", $produk_id);
    mysqli_stmt_execute($query_produk);
    $result_produk = mysqli_stmt_get_result($query_produk);

    if (mysqli_num_rows($result_produk) === 0) {
        throw new Exception("Produk dengan ID tersebut tidak ditemukan.");
    }

    $ukuran = mysqli_fetch_assoc($result_produk)['ukuran'];

    // Periksa apakah produk sudah ada di keranjang
    $query_check = mysqli_prepare($koneksi, "SELECT id, jumlah FROM keranjang WHERE username = ? AND produk_id = ?");
    if (!$query_check) {
        throw new Exception("Gagal mempersiapkan query: " . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($query_check, "si", $username, $produk_id);
    mysqli_stmt_execute($query_check);
    $result_check = mysqli_stmt_get_result($query_check);

    if (mysqli_num_rows($result_check) > 0) {
        // Jika produk sudah ada di keranjang, tambahkan jumlah
        $row = mysqli_fetch_assoc($result_check);
        $jumlah_baru = $row['jumlah'] + 1;

        $query_update = mysqli_prepare($koneksi, "UPDATE keranjang SET jumlah = ?, ukuran = ? WHERE id = ?");
        if (!$query_update) {
            throw new Exception("Gagal mempersiapkan query update: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($query_update, "isi", $jumlah_baru, $ukuran, $row['id']);
        mysqli_stmt_execute($query_update);
    } else {
        // Jika produk belum ada di keranjang, tambahkan produk dengan jumlah 1
        $query_insert = mysqli_prepare($koneksi, "INSERT INTO keranjang (username, produk_id, jumlah, ukuran) VALUES (?, ?, 1, ?)");
        if (!$query_insert) {
            throw new Exception("Gagal mempersiapkan query insert: " . mysqli_error($koneksi));
        }
        mysqli_stmt_bind_param($query_insert, "sis", $username, $produk_id, $ukuran);
        mysqli_stmt_execute($query_insert);
    }

    // Hitung total jumlah produk di keranjang untuk pengguna
    $query_total = mysqli_prepare($koneksi, "SELECT SUM(jumlah) AS total_produk FROM keranjang WHERE username = ?");
    if (!$query_total) {
        throw new Exception("Gagal mempersiapkan query total: " . mysqli_error($koneksi));
    }
    mysqli_stmt_bind_param($query_total, "s", $username);
    mysqli_stmt_execute($query_total);
    $result_total = mysqli_stmt_get_result($query_total);
    $total_produk = mysqli_fetch_assoc($result_total)['total_produk'];

    // Commit transaksi
    mysqli_commit($koneksi);

    // Respons JSON
    echo json_encode([
        "success" => true,
        "message" => "Produk berhasil ditambahkan atau diperbarui di keranjang.",
        "total" => $total_produk
    ]);
} catch (Exception $e) {
    // Rollback jika ada error
    mysqli_rollback($koneksi);
    echo json_encode([
        "success" => false,
        "message" => "Terjadi kesalahan: " . $e->getMessage()
    ]);
    exit();
}
?>
