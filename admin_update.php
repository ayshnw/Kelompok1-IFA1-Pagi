<?php
include('includes/header.php');
include('includes/navbar.php');
include('config.php');

$id = isset($_GET['edit']) ? intval($_GET['edit']) : 0;
$messages = '';

if (isset($_POST['edit_produk'])) {
    // Ambil input dari form
    $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
    $ukuran = mysqli_real_escape_string($conn, $_POST['ukuran']);
    $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
    $harga = mysqli_real_escape_string($conn, $_POST['harga']);
    $stok = mysqli_real_escape_string($conn, $_POST['stok']);
    $gambar = $_FILES['gambar']['name'];
    $gambar_tmp_name = $_FILES['gambar']['tmp_name'];
    $gambar_folder = "uploaded_image/" . $gambar;

    // Validasi input
    if (empty($nama_produk) || empty($harga) || empty($ukuran) || empty($deskripsi)) {
        $messages = '<span class="message">Silahkan mengisi semua field</span>';
    } else {
        // Coba unggah gambar jika ada yang baru
        if (!empty($gambar)) {
            if (move_uploaded_file($gambar_tmp_name, $gambar_folder)) {
                // Query update jika gambar diunggah ulang
                $update = "UPDATE produk SET nama_produk='$nama_produk', ukuran='$ukuran', deskripsi='$deskripsi', harga='$harga', stok='$stok', gambar='$gambar' WHERE id=$id";
            } else {
                $messages = '<span class="message">Gagal mengunggah gambar</span>';
            }
        } else {
            // Query update jika tidak ada gambar yang diunggah ulang
            $update = "UPDATE produk SET nama_produk='$nama_produk', ukuran='$ukuran', deskripsi='$deskripsi', harga='$harga', stok='$stok' WHERE id=$id";
        }

        if (mysqli_query($conn, $update)) {
            // Redirect halaman setelah update berhasil
            header("Location: barang.php?message=Produk berhasil diperbarui");
            exit; // Pastikan tidak ada output setelah redirect
        } else {
            $messages = '<span class="message">Produk tidak berhasil diperbarui</span>';
        }
    }
}

// Query untuk mengambil data produk yang akan diedit
$select = mysqli_query($conn, "SELECT * FROM produk WHERE id=$id");
if (mysqli_num_rows($select) === 0) {
    echo "<span class='message'>Produk tidak ditemukan</span>";
    exit;
}
$row = mysqli_fetch_assoc($select);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Update</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php if (!empty($messages)) echo $messages; ?>

    <div class="container">
        <div class="admin-product-form-container centered">
            <form action="<?php echo $_SERVER['PHP_SELF'] . "?edit=$id"; ?>" method="post" enctype="multipart/form-data">
                <h3>Edit Produk</h3>
                <input type="text" placeholder="Masukkan nama produk" value="<?php echo $row['nama_produk']; ?>" name="nama_produk" class="box">
                <input type="text" placeholder="Masukkan ukuran produk" value="<?php echo $row['ukuran']; ?>" name="ukuran" class="box">
                <textarea placeholder="Masukkan deskripsi produk" name="deskripsi" class="box"><?php echo $row['deskripsi']; ?></textarea>
                <input type="number" placeholder="Masukkan harga produk" value="<?php echo $row['harga']; ?>" name="harga" class="box">
                <input type="number" placeholder="Masukkan stok produk" value="<?php echo $row['stok']; ?>" name="stok" class="box">
                <input type="file" accept="image/png, image/jpeg, image/jpg" name="gambar" class="box">
                <input type="submit" class="btn" name="edit_produk" value="Edit produk">
                <a href="barang.php" class="btn">Kembali</a>
            </form>
        </div>
    </div>
</body>

</html>

<?php include('includes/scripts.php'); ?>
