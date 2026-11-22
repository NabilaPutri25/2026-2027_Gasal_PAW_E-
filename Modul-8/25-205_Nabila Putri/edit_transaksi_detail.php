<?php
include "koneksi.php";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$transaksi_id = isset($_GET['transaksi_id']) ? (int) $_GET['transaksi_id'] : 0;
$barang_id = isset($_GET['barang_id']) ? (int) $_GET['barang_id'] : 0;

// Mengambil data transaksi detail
$query = mysqli_query($koneksi,
    "SELECT * FROM transaksi_detail
     WHERE transaksi_id = $transaksi_id
     AND barang_id = $barang_id");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi detail tidak ditemukan!");
}

// Proses menyimpan perubahan
if (isset($_POST['simpan'])) {

    $harga = (float) $_POST['harga'];
    $qty_baru = (int) $_POST['qty'];
    $qty_lama = (int) $data['qty'];

    if ($harga <= 0 || $qty_baru <= 0) {
        die("Harga dan jumlah harus lebih dari 0!");
    }

    mysqli_begin_transaction($koneksi);

    try {

        // Mengambil stok barang
        $barang = mysqli_query($koneksi,
            "SELECT stok FROM barang
             WHERE id = $barang_id
             FOR UPDATE");

        $data_barang = mysqli_fetch_assoc($barang);

        if (!$data_barang) {
            throw new Exception("Barang tidak ditemukan!");
        }

        $stok = (int) $data_barang['stok'];

        // Menghitung perubahan jumlah
        $selisih = $qty_baru - $qty_lama;

        if ($selisih > 0) {

            // Memastikan stok mencukupi
            if ($stok < $selisih) {
                throw new Exception("Stok barang tidak mencukupi!");
            }

            // Mengurangi stok
            mysqli_query($koneksi,
                "UPDATE barang
                 SET stok = stok - $selisih
                 WHERE id = $barang_id");

        } elseif ($selisih < 0) {

            // Mengembalikan stok
            $kembali = abs($selisih);

            mysqli_query($koneksi,
                "UPDATE barang
                 SET stok = stok + $kembali
                 WHERE id = $barang_id");
        }

        // Mengubah harga dan jumlah
        mysqli_query($koneksi,
            "UPDATE transaksi_detail
             SET harga = $harga, qty = $qty_baru
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

        // Menyimpan semua perubahan
        mysqli_commit($koneksi);

        header("Location: index.php");
        exit;

    } catch (Throwable $e) {

        // Membatalkan perubahan jika terjadi kesalahan
        mysqli_rollback($koneksi);

        die("Gagal mengubah transaksi: " .
            htmlspecialchars($e->getMessage()));
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi Detail</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Transaksi Detail</h2>

<form method="POST">

    <label>Harga Barang</label><br>
    <input type="number" name="harga"
        value="<?php echo htmlspecialchars($data['harga']); ?>"
        min="1" step="0.01" required>
    <br><br>

    <label>Jumlah Barang</label><br>
    <input type="number" name="qty"
        value="<?php echo htmlspecialchars($data['qty']); ?>"
        min="1" required>
    <br><br>

    <button type="submit" name="simpan">
        Simpan Perubahan
    </button>

    <a href="index.php">Batal</a>

</form>

</body>
</html>