<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data user tidak ditemukan!");
}

if (isset($_POST['hapus'])) {
    $hapus = mysqli_query($koneksi, "DELETE FROM user WHERE id_user = $id");

    if ($hapus) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Konfirmasi Hapus User</h2>

<p>Apakah kamu yakin ingin menghapus user berikut?</p>

<p>Username: <?php echo htmlspecialchars($data['username']); ?></p>
<p>Nama: <?php echo htmlspecialchars($data['nama']); ?></p>

<form method="POST">
    <button type="submit" name="hapus"
        onclick="return confirm('Yakin ingin menghapus data ini?')">
        Ya, Hapus
    </button>

    <a href="index.php">Batal</a>
</form>

</body>
</html>