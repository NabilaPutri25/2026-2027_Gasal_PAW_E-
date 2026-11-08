<?php
include 'koneksi.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Master Transaksi</title>
</head>
<body>

    <h2>Daftar Transaksi (Master)</h2>

    <a href="barang.php">Kelola Barang</a> |
    <a href="transaksi.php">Refresh</a>

    <br><br>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID Transaksi</th>
            <th>Waktu Transaksi</th>
            <th>Pelanggan</th>
            <th>Keterangan</th>
            <th>Total Transaksi</th>
            <th>Aksi</th>
        </tr>

        <?php
        $q = mysqli_query($conn, "
            SELECT transaksi.*, pelanggan.nama AS nama_pelanggan
            FROM transaksi
            LEFT JOIN pelanggan
            ON transaksi.pelanggan_id = pelanggan.id
            ORDER BY transaksi.id DESC
        ");

        while ($t = mysqli_fetch_assoc($q)):
        ?>

        <tr>
            <td>TRX-<?= $t['id']; ?></td>
            <td><?= $t['waktu_transaksi']; ?></td>
            <td><?= $t['nama_pelanggan']; ?></td>
            <td><?= $t['keterangan']; ?></td>
            <td>
                <b>Rp <?= number_format($t['total']); ?></b>
            </td>
            <td>
                <a href="transaksi_detail.php?id=<?= $t['id']; ?>">
                    Kelola Detail
                </a>
            </td>
        </tr>

        <?php endwhile; ?>

    </table>

</body>
</html>