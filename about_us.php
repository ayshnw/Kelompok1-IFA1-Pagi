<!-- Nama File: about_us.php -->
<!-- Deskripsi: File ini mengelola CRUD data untuk tentang Kami -->
<!-- Dibuat oleh: Raid Aqil Athallah - NIM: 3312401022 -->
<!-- Tanggal: 2 November 2024 -->

<?php
// Menyertakan file header untuk bagian atas halaman
include('includes/header.php');
// Menyertakan file navbar untuk navigasi di halaman
include('includes/navbar.php');

// Menyertakan file konfigurasi untuk koneksi databasezz
include('koneksi.php');

// Memulai sesi untuk menangani pesan notifikasi
session_start();
if (isset($_SESSION['message'])) {
    // Menampilkan pesan notifikasi jika ada di sesi
    echo "<div class='alert alert-{$_SESSION['type']} alert-dismissible fade show' role='alert'>";
    echo htmlspecialchars($_SESSION['message']); // Mencegah serangan XSS
    echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
    // Menghapus pesan dari sesi setelah ditampilkan
    unset($_SESSION['message'], $_SESSION['type']);
}
?>

<!-- Bagian konten utama halaman -->
<div class="container">
    <h1>Tentang Kami</h1>
    <!-- Tombol untuk membuka modal tambah data baru -->
    <button class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addModal">Tambah Data</button>

    <!-- Tabel untuk menampilkan data "about_us" dari database -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Gambar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Query untuk mengambil semua data dari tabel "about_us"
            $query = mysqli_query($koneksi, "SELECT * FROM about_us");
            while ($row = mysqli_fetch_assoc($query)) {
                echo "<tr>";
                // Menampilkan ID data
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                // Menampilkan nama data
                echo "<td>" . htmlspecialchars($row['nama']) . "</td>";
                // Menampilkan gambar data
                echo "<td><img src='uploads/" . htmlspecialchars($row['gambar']) . "' alt='Image' width='100'></td>";
                echo "<td>";
                // Tombol untuk membuka modal edit data
                echo "<button class='btn btn-warning btn-edit' data-bs-toggle='modal' data-bs-target='#editModal'";
                echo "data-id='" . $row['id'] . "' data-nama='" . htmlspecialchars($row['nama']) . "' data-gambar='" . htmlspecialchars($row['gambar']) . "'>Ubah</button> ";
                // Tombol untuk menghapus data dengan konfirmasi
                echo "<a href='delete_about_us.php?id=" . $row['id'] . "' class='btn btn-danger' onclick='return confirm(\"Are you sure?\");'>Hapus</a>";
                echo "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal untuk menambah data baru -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="add_about_us.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&#x2715;</button>
                </div>
                <div class="modal-body">
                    <!-- Input untuk nama baru -->
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="nama" name="nama" required>
                    </div>
                    <!-- Input untuk unggah gambar baru -->
                    <div class="mb-3">
                        <label for="gambar" class="form-label">Gambar</label>
                        <input type="file" class="form-control" id="gambar" name="gambar" accept="image/*" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk mengubah data -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="edit_about_us.php" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">&#x2715;</button>
                </div>
                <div class="modal-body">
                    <!-- Input ID tersembunyi untuk data yang akan diubah -->
                    <input type="hidden" id="editId" name="id">
                    <!-- Input untuk mengubah nama -->
                    <div class="mb-3">
                        <label for="editNama" class="form-label">Nama</label>
                        <input type="text" class="form-control" id="editNama" name="nama" required>
                    </div>
                    <!-- Menampilkan gambar saat ini dan input untuk mengganti gambar -->
                    <div class="mb-3">
                        <label class="form-label">Gambar saat ini</label><br>
                        <img id="editImagePreview" src="" alt="Image" width="150" class="mb-3">
                        <label for="editGambar" class="form-label">Ubah gambar</label>
                        <input type="file" class="form-control" id="editGambar" name="gambar" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batalkan</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    // JavaScript untuk menangani modal edit
    const editButtons = document.querySelectorAll('.btn-edit');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Mengisi data modal edit dengan data dari tabel
            document.getElementById('editId').value = this.dataset.id;
            document.getElementById('editNama').value = this.dataset.nama;
            document.getElementById('editImagePreview').src = 'uploads/' + this.dataset.gambar;
        });
    });
</script>

<!-- Menyertakan file Bootstrap untuk fungsi modal -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<?php
// Menyertakan file script tambahan untuk fungsionalitas
include('includes/scripts.php');
?>