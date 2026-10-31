<?php
include "koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Mengambil ID transaksi dan barang
$transaksi_id = isset($_GET['transaksi_id']) ? (int) $_GET['transaksi_id'] : 0;
$barang_id = isset($_GET['barang_id']) ? (int) $_GET['barang_id'] : 0;

if ($transaksi_id <= 0 || $barang_id <= 0) {
    die("ID transaksi atau barang tidak valid!");
}

// Mengambil data detail transaksi
$query = mysqli_query($koneksi,
    "SELECT * FROM transaksi_detail
     WHERE transaksi_id = $transaksi_id
     AND barang_id = $barang_id");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi detail tidak ditemukan!");
}

// Proses hapus
if (isset($_POST['hapus'])) {

    mysqli_begin_transaction($koneksi);

    try {

        // Mengambil jumlah barang
        $detail = mysqli_query($koneksi,
            "SELECT qty FROM transaksi_detail
             WHERE transaksi_id = $transaksi_id
             AND barang_id = $barang_id
             FOR UPDATE");

        $hasil = mysqli_fetch_assoc($detail);

        if (!$hasil) {
            throw new Exception("Detail transaksi tidak ditemukan!");
        }

        $qty = (int) $hasil['qty'];

        // Mengembalikan stok barang
        mysqli_query($koneksi,
            "UPDATE barang
             SET stok = stok + $qty
             WHERE id = $barang_id");

        // Menghapus detail transaksi
        mysqli_query($koneksi,
            "DELETE FROM transaksi_detail
             WHERE transaksi_id = $transaksi_id
             AND barang_id = $barang_id");

        // Menghitung ulang total transaksi
        mysqli_query($koneksi,
            "UPDATE transaksi
             SET total = (
                 SELECT COALESCE(SUM(harga * qty), 0)
                 FROM transaksi_detail
                 WHERE transaksi_id = $transaksi_id
             )
             WHERE id = $transaksi_id");

        // Mengambil total terbaru
        $total_baru = mysqli_query($koneksi,
            "SELECT total FROM transaksi
             WHERE id = $transaksi_id");

        $hasil_total = mysqli_fetch_assoc($total_baru);
        $total = (float) $hasil_total['total'];

        // Memperbarui total pembayaran
        mysqli_query($koneksi,
            "UPDATE pembayaran
             SET total = $total
             WHERE transaksi_id = $transaksi_id");

        // Menyimpan perubahan
        mysqli_commit($koneksi);

        header("Location: index.php");
        exit;

    } catch (Throwable $e) {

        mysqli_rollback($koneksi);

        die("Gagal menghapus detail transaksi: " .
            htmlspecialchars($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Hapus Transaksi Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Konfirmasi Hapus Barang Transaksi</h2>

<p>Apakah kamu yakin ingin menghapus barang ini dari transaksi?</p>

<p>
    ID Transaksi:
    <?php echo $data['transaksi_id']; ?>
</p>

<p>
    ID Barang:
    <?php echo $data['barang_id']; ?>
</p>

<p>
    Harga:
    Rp <?php echo number_format(
        (float) $data['harga'], 0, ',', '.'
    ); ?>
</p>

<p>
    Jumlah:
    <?php echo $data['qty']; ?>
</p>

<form method="POST">
    <button type="submit" name="hapus"
        onclick="return confirm('Yakin ingin menghapus barang ini?')">
        Ya, Hapus
    </button>

    <a href="index.php">Batal</a>
</form>

</body>
</html>