<?php
include "koneksi.php";

// Mengaktifkan laporan kesalahan MySQL
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Mengambil ID transaksi
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID transaksi tidak valid!");
}

// Mengambil data transaksi
$query = mysqli_query($koneksi,
    "SELECT * FROM transaksi WHERE id = $id");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi tidak ditemukan!");
}

// Proses hapus transaksi
if (isset($_POST['hapus'])) {

    mysqli_begin_transaction($koneksi);

    try {
        // Mengambil rincian barang transaksi
        $detail = mysqli_query($koneksi,
            "SELECT barang_id, qty FROM transaksi_detail
             WHERE transaksi_id = $id");

        while ($d = mysqli_fetch_assoc($detail)) {

            $barang_id = (int) $d['barang_id'];
            $qty = (int) $d['qty'];

            // Mengembalikan stok barang
            mysqli_query($koneksi,
                "UPDATE barang
                 SET stok = stok + $qty
                 WHERE id = $barang_id");
        }

        // Menghapus data pembayaran
        mysqli_query($koneksi,
            "DELETE FROM pembayaran
             WHERE transaksi_id = $id");

        // Menghapus detail transaksi
        mysqli_query($koneksi,
            "DELETE FROM transaksi_detail
             WHERE transaksi_id = $id");

        // Menghapus transaksi utama
        mysqli_query($koneksi,
            "DELETE FROM transaksi
             WHERE id = $id");

        // Menyimpan perubahan
        mysqli_commit($koneksi);

        header("Location: index.php");
        exit;

    } catch (Throwable $e) {

        // Membatalkan semua perubahan jika gagal
        mysqli_rollback($koneksi);

        die("Gagal menghapus transaksi: " .
            htmlspecialchars($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Konfirmasi Hapus Transaksi</h2>

<p>Apakah kamu yakin ingin menghapus transaksi ini?</p>

<p>
    ID Transaksi:
    <?php echo $data['id']; ?>
</p>

<p>
    Total:
    Rp <?php echo number_format(
        (float) $data['total'], 0, ',', '.'
    ); ?>
</p>

<form method="POST">
    <button type="submit" name="hapus"
        onclick="return confirm('Yakin ingin menghapus transaksi ini?')">
        Ya, Hapus
    </button>

    <a href="index.php">Batal</a>
</form>

</body>
</html>