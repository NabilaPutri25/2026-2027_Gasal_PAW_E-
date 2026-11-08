<?php
include 'koneksi.php';

$transaksi_id = $_GET['id'];

// 1. Ambil data master transaksi
$q_trans = mysqli_query($conn, "SELECT t.*, p.nama FROM transaksi t LEFT JOIN pelanggan p ON t.pelanggan_id = p.id WHERE t.id = '$transaksi_id'");
$trans = mysqli_fetch_assoc($q_trans);

// 2. PROSES INPUT DETAIL (Soal 4: Hitung Ulang Otomatis)
if (isset($_POST['simpan_detail'])) {
    $barang_id = $_POST['barang_id'];
    $qty       = $_POST['qty'];

    // Ambil harga barang saat ini
    $q_brg = mysqli_query($conn, "SELECT harga FROM barang WHERE id = '$barang_id'");
    $d_brg = mysqli_fetch_assoc($q_brg);
    $harga = $d_brg['harga'];

    // Simpan ke transaksi_detail
    mysqli_query($conn, "INSERT INTO transaksi_detail (transaksi_id, barang_id, harga, qty) VALUES ('$transaksi_id', '$barang_id', '$harga', '$qty')");

    // SOAL 4: Recalculate Total di Tabel Transaksi secara Otomatis
    $q_sum = mysqli_query($conn, "SELECT SUM(harga * qty) AS total_baru FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
    $d_sum = mysqli_fetch_assoc($q_sum);
    $total_baru = $d_sum['total_baru'] ?? 0;

    // Update kolom total di tabel transaksi
    mysqli_query($conn, "UPDATE transaksi SET total = '$total_baru' WHERE id = '$transaksi_id'");

    // Refresh halaman
    header("Location: transaksi_detail.php?id=$transaksi_id");
    exit();
}

// 3. Ambil daftar barang_id yang SUDAH ADA di transaksi ini (Untuk Soal 2)
$q_exist = mysqli_query($conn, "SELECT barang_id FROM transaksi_detail WHERE transaksi_id = '$transaksi_id'");
$barang_terpakai = [];
while ($row = mysqli_fetch_assoc($q_exist)) {
    $barang_terpakai[] = $row['barang_id'];
}
?>

<!DOCTYPE html>
<html>
<head><title>Detail Transaksi TRX-<?= $transaksi_id; ?></title></head>
<body>
    <a href="transaksi.php">← Kembali ke Master Transaksi</a>
    <h2>Detail Transaksi #TRX-<?= $transaksi_id; ?></h2>
    
    <p>
        <b>Pelanggan:</b> <?= $trans['nama']; ?><br>
        <b>Tanggal:</b> <?= $trans['waktu_transaksi']; ?><br>
        <b>Total Saat Ini:</b> Rp <?= number_format($trans['total']); ?>
    </p>

    <hr>

    <!-- FORM INPUT DETAIL (SOAL 2) -->
    <h3>Tambah Barang ke Transaksi</h3>
    <form method="POST">
        <label>Pilih Barang:</label>
        <select name="barang_id" required>
            <option value="">-- Pilih Barang --</option>
            <?php
            $q_all_brg = mysqli_query($conn, "SELECT * FROM barang");
            while ($b = mysqli_fetch_assoc($q_all_brg)):
                // Cek apakah barang sudah di-input pada transaksi ini
                $is_used = in_array($b['id'], $barang_terpakai);
            ?>
                <!-- SOAL 2: Barang yang sudah ada di-disabled -->
                <option value="<?= $b['id']; ?>" <?= $is_used ? 'disabled style="color:red;"' : ''; ?>>
                    <?= $b['nama_barang']; ?> (Rp <?= number_format($b['harga']); ?>) <?= $is_used ? '- [Sudah Ada]' : ''; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Qty:</label>
        <input type="number" name="qty" min="1" value="1" required>

        <button type="submit" name="simpan_detail">Tambah Barang</button>
    </form>

    <br>

    <!-- TABEL DETAIL TRANSAKSI -->
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Harga Satuan</th>
            <th>Qty</th>
            <th>Subtotal</th>
        </tr>
        <?php
        $no = 1;
        $q_detail = mysqli_query($conn, "SELECT td.*, b.nama_barang FROM transaksi_detail td JOIN barang b ON td.barang_id = b.id WHERE td.transaksi_id = '$transaksi_id'");
        while ($det = mysqli_fetch_assoc($q_detail)):
            $subtotal = $det['harga'] * $det['qty'];
        ?>
        <tr>
            <td><?= $no++; ?></td>
            <td><?= $det['nama_barang']; ?></td>
            <td>Rp <?= number_format($det['harga']); ?></td>
            <td><?= $det['qty']; ?></td>
            <td>Rp <?= number_format($subtotal); ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>