<!--
// Nama File: produk.php
// Deskripsi: File ini mengelola CRUD dari dasbor penjual menuju ke halaman pembeli
// Dibuat oleh: Dionaldi Sion Yosua - NIM: 3312401011
// Tanggal: 02 November 2024
-->


<?php
// Untuk memanggil header
include('includes/header.php');

// Untuk memanggil navbar 
include('includes/navbar.php');

// Untuk melakukan koneksi ke database
include 'koneksi.php';

// Session untuk menangkap notifikasi
session_start(); 

// Menampilkan notifikasi
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    $type = $_SESSION['type'];
    echo "
    <div class='alert alert-$type alert-dismissible fade show' role='alert'>
        $message
        <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>";

    // Hapus notifikasi setelah ditampilkan
    unset($_SESSION['message'], $_SESSION['type']); 
}
?>


    <!-- Container Utama -->
    <div class="container">
        <h1>Data Produk</h1>
        <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Produk</button>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID Produk</th>
                    <th>Nama Produk</th>
                    <th>Ukuran</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Foto</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Untuk menghubungkan ke database
                include 'koneksi.php';
                // Query untuk mengambil data dari database produk
                $query = mysqli_query($koneksi, "SELECT * FROM produk");
                while ($data = mysqli_fetch_assoc($query)) { ?>
                    <tr>
                        <!-- Untuk menampilkan id produk dan nama produk -->
                        <td><?= htmlspecialchars($data['id_produk']); ?></td>
                        <td><?= htmlspecialchars($data['nama_produk']); ?></td>
                        <td>
                            <?php
                            // Untuk memisahkan ukuran berdasarkan koma
                            $ukuran = explode(',', htmlspecialchars($data['ukuran'])); 
                            foreach ($ukuran as $size) {
                            // Tampilkan tiap ukuran sebagai badge
                            echo "<span>$size</span>"; 
                            }
                            ?>
                        </td>
                        <!-- Untuk menampilkan deskripsi, harga dan stok -->
                        <td><?= htmlspecialchars($data['deskripsi']); ?></td>
                        <td><?= htmlspecialchars($data['harga']); ?></td>
                        <td><?= htmlspecialchars($data['stok']); ?></td>
                        <td>
                        <!-- Untuk menampilkan gambar produk -->
                            <img src="uploads/<?= htmlspecialchars($data['gambar']); ?>" alt="Foto Produk" width="100">
                        </td>
                        <td>
                            <!-- Tombol untuk mengedit produk, membuka modal edit dengan data produk yang sesuai -->
                            <button class="btn btn-warning btn-edit" data-bs-toggle="modal" data-bs-target="#editModal"
                        data-id_produk="<?= $data['id_produk'] ?>"
                        data-nama="<?= htmlspecialchars($data['nama_produk']) ?>"
                        data-ukuran="<?= htmlspecialchars($data['ukuran']) ?>"
                        data-deskripsi="<?= htmlspecialchars($data['deskripsi']) ?>"
                        data-harga="<?= $data['harga'] ?>"
                        data-stok="<?= $data['stok'] ?>"
                        data-gambar="<?= htmlspecialchars($data['gambar']) ?>">
                        Ubah
                    </button>
                            <!-- Tombol untuk menghapus produk, mengarahkan ke halaman hapus.php dengan ID produk -->
                            <a href="hapus.php?id_produk=<?= htmlspecialchars($data['id_produk']); ?>" 
                               class="btn btn-danger btn-sm" 
                               onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Modal Tambah Produk -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="tambah.php" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addModalLabel">Tambah Produk</h5>
                        <button type="button" class="btn btn-close-custom" data-bs-dismiss="modal" aria-label="Close">&#x2715;</button>
                    </div>
                    <div class="modal-body">
                         <!-- Form input untuk nama produk, ukuran, deskripsi, harga, stok, dan gambar produk -->
                        <div class="mb-3">
                            <label for="nama_produk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="nama_produk" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label for="ukuran" class="form-label">Ukuran</label>
                            <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Contoh: S, M, L, XL, All size" required>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="harga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="harga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="stok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="stok" name="stok" required>
                        </div>
                        <div class="mb-3">
                            <label for="gambar" class="form-label">Gambar Produk</label>
                            <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    

    <!-- Modal Edit Produk -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="ubah.php" enctype="multipart/form-data">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Produk</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&#x2715;</button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editId" name="id">
                        <!-- Form input untuk nama produk, ukuran, deskripsi, harga, stok, dan gambar produk yang akan diubah -->
                        <div class="mb-3">
                            <label for="editNamaProduk" class="form-label">Nama Produk</label>
                            <input type="text" class="form-control" id="editNamaProduk" name="nama_produk" required>
                        </div>
                        <div class="mb-3">
                            <label for="ukuran" class="form-label">Ukuran</label>
                            <input type="text" class="form-control" id="ukuran" name="ukuran" placeholder="Contoh: S, M, L, XL, All size" required>
                        </div>

                        <div class="mb-3">
                            <label for="editDeskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="editDeskripsi" name="deskripsi" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="editHarga" class="form-label">Harga</label>
                            <input type="number" class="form-control" id="editHarga" name="harga" required>
                        </div>
                        <div class="mb-3">
                            <label for="editStok" class="form-label">Stok</label>
                            <input type="number" class="form-control" id="editStok" name="stok" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Foto Produk Saat Ini</label><br>
                            <img id="editFotoPreview" src="" alt="Foto Produk" width="150" class="mb-3 border rounded"><br>
                            <label for="editGambar" class="form-label">Ganti Foto</label>
                            <input type="file" class="form-control" id="editGambar" name="gambar" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Edit Produk -->
    <script>
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function () {
            // Mengisi form edit dengan data produk yang dipilih
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editNamaProduk').value = this.dataset.nama;
            document.getElementById('editUkuran').value = this.dataset.ukuran;
            document.getElementById('editDeskripsi').value = this.dataset.deskripsi;
            document.getElementById('editHarga').value = this.dataset.harga;
            document.getElementById('editStok').value = this.dataset.stok;

            // Menampilkan gambar produk di preview
            const fotoPath = this.dataset.gambar ? uploads/${this.dataset.gambar} : '';
            document.getElementById('editFotoPreview').src = fotoPath;
        });
    });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    // Hilangkan notifikasi setelah 5 detik
    setTimeout(() => {
        const notification = document.querySelector('.notification');
        if (notification) {
            notification.classList.remove('show'); // Hapus kelas 'show'
            notification.classList.add('fade'); // Tambahkan efek fade
        }
    }, 5000); // 5000ms = 5 detik
</script>

</body>