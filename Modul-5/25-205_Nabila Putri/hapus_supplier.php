<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM supplier WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data supplier tidak ditemukan!");
}

if (isset($_POST['hapus'])) {

    $cek = mysqli_query($koneksi,
        "SELECT * FROM barang WHERE supplier_id = $id"
    );

    if (mysqli_num_rows($cek) > 0) {
        $pesan = "Supplier tidak bisa dihapus karena masih digunakan oleh barang.";
    } else {
        $hapus = mysqli_query($koneksi,
            "DELETE FROM supplier WHERE id = $id"
        );

        if ($hapus) {
            header("Location: index.php");
            exit;
        } else {
            $pesan = "Gagal menghapus supplier: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Supplier</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Hapus Data Supplier</h2>

<p>
Apakah kamu yakin ingin menghapus supplier
<strong><?php echo htmlspecialchars($data['nama']); ?></strong>?
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