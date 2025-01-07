<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lihat Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Daftar Produk</h2>
        <a href="tambah_produk.php" class="btn btn-success mb-3">Tambah Produk</a>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Gambar</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Include koneksi ke database
                include 'koneksi.php';

                // Query untuk mengambil data produk
                $query = mysqli_query($koneksi, "SELECT * FROM produk");
                while ($data = mysqli_fetch_assoc($query)) {
                ?>
                    <tr>
                        <td><?= htmlspecialchars($data['id_produk']); ?></td>
                        <td><?= htmlspecialchars($data['nama_produk']); ?></td>
                        <td><?= htmlspecialchars($data['deskripsi']); ?></td>
                        <td><?= htmlspecialchars($data['harga']); ?></td>
                        <td><?= htmlspecialchars($data['stok']); ?></td>
                        <td>
                            <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Gambar Produk" width="100">
                        </td>
                        <td>
                            <a href="edit_produk.php?id_produk=<?= htmlspecialchars($data['id_produk']); ?>" class="btn btn-warning">Edit</a>
                            <a href="hapus_produk.php?id_produk=<?= htmlspecialchars($data['id_produk']); ?>" 
                               class="btn btn-danger" 
                               onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
