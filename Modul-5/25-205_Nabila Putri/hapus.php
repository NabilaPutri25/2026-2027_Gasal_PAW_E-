<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if (isset($_POST['hapus'])) {
    mysqli_query($koneksi, "DELETE FROM pelanggan WHERE id = $id");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Pelanggan</title>
</head>
<body>

    <h2>Hapus Data Pelanggan</h2>

    <p>Apakah kamu yakin ingin menghapus data pelanggan ini?</p>

    <form method="POST">
        <button type="submit" name="hapus">Ya, Hapus</button>
        <a href="index.php">Batal</a>
    </form>

</body>
</html>