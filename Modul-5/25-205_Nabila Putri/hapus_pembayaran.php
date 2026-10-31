<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi,
    "SELECT * FROM pembayaran WHERE id = $id");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pembayaran tidak ditemukan!");
}

if (isset($_POST['hapus'])) {

    mysqli_query($koneksi,
        "DELETE FROM pembayaran WHERE id = $id");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Pembayaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Konfirmasi Hapus Pembayaran</h2>

<p>Apakah kamu yakin ingin menghapus pembayaran ini?</p>

<p>ID Pembayaran: <?php echo $data['id']; ?></p>

<p>Total:
    Rp <?php echo number_format(
        (float) $data['total'], 0, ',', '.'
    ); ?>
</p>

<p>Metode: <?php echo htmlspecialchars($data['metode']); ?></p>

<form method="POST">
    <button type="submit" name="hapus"
        onclick="return confirm('Yakin ingin menghapus pembayaran ini?')">
        Ya, Hapus
    </button>

    <a href="index.php">Batal</a>
</form>

</body>
</html>