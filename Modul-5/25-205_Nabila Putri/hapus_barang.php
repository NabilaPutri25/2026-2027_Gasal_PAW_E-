<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM barang WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data barang tidak ditemukan!");
}

if (isset($_POST['hapus'])) {

    // Memeriksa apakah barang digunakan dalam transaksi
    $cek = mysqli_query($koneksi,
        "SELECT * FROM transaksi_detail WHERE barang_id = $id"
    );

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Barang tidak bisa dihapus karena sudah digunakan dalam transaksi.";
    } else {
        $hapus = mysqli_query($koneksi,
            "DELETE FROM barang WHERE id = $id"
        );

        if ($hapus) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal menghapus barang: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Barang</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Hapus Data Barang</h2>

<p>
Apakah kamu yakin ingin menghapus barang
<strong><?php echo htmlspecialchars($data['nama_barang']); ?></strong>?
</p>

<?php if (isset($pesan)) { ?>
    <p><?php echo htmlspecialchars($pesan); ?></p>
<?php } ?>

<form method="POST">
    <button type="submit" name="hapus">Ya, Hapus</button>
    <a href="index.php">Batal</a>
</form>

</body>
</html>