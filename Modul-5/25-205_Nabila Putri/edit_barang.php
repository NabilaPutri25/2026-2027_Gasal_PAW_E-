<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data barang tidak ditemukan!");
}

if (isset($_POST['simpan'])) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $harga = (float) $_POST['harga'];
    $stok = (int) $_POST['stok'];
    $supplier = (int) $_POST['supplier_id'];

    $query = "UPDATE barang SET
              kode_barang='$kode',
              nama_barang='$nama',
              harga=$harga,
              stok=$stok,
              supplier_id=$supplier
              WHERE id=$id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}

$supplier = mysqli_query($koneksi, "SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Data Barang</h2>

<form method="POST">

    Kode Barang:
    <input type="text" name="kode_barang"
    value="<?php echo htmlspecialchars($data['kode_barang']); ?>" required>
    <br><br>

    Nama Barang:
    <input type="text" name="nama_barang"
    value="<?php echo htmlspecialchars($data['nama_barang']); ?>" required>
    <br><br>

    Harga:
    <input type="number" name="harga" step="0.01"
    value="<?php echo htmlspecialchars($data['harga']); ?>" required>
    <br><br>

    Stok:
    <input type="number" name="stok"
    value="<?php echo htmlspecialchars($data['stok']); ?>" required>
    <br><br>

    Supplier:
    <select name="supplier_id">
        <?php while ($s = mysqli_fetch_assoc($supplier)) { ?>
            <option value="<?php echo $s['id']; ?>"
                <?php if ($s['id'] == $data['supplier_id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($s['nama']); ?>
            </option>
        <?php } ?>
    </select>
    <br><br>

    <button type="submit" name="simpan">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>

</form>

</body>
</html>