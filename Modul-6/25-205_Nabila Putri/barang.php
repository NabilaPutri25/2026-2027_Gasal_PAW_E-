<?php
include 'koneksi.php';
$pesan = isset($_GET['pesan']) ? $_GET['pesan'] : '';
?>
<!DOCTYPE html>
<html>
<head><title>Data Barang</title></head>
<body>
    <h2>Data Barang</h2>
    <?php if($pesan == 'terpakai'): ?>
        <p style="color: red; fontweight: bold;">❌ Gagal Hapus: Barang masih digunakan pada transaksi_detail!</p>
    <?php elseif($pesan == 'sukses'): ?>
        <p style="color: green; fontweight: bold;">✅ Data barang berhasil dihapus!</p>
    <?php endif; ?>

    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>ID</th><th>Kode</th><th>Nama Barang</th><th>Harga</th><th>Stok</th><th>Aksi</th>
        </tr>
        <?php
        $query = mysqli_query($conn, "SELECT * FROM barang");
        while($d = mysqli_fetch_assoc($query)):
        ?>
        <tr>
            <td><?= $d['id']; ?></td>
            <td><?= $d['kode_barang']; ?></td>
            <td><?= $d['nama_barang']; ?></td>
            <td>Rp <?= number_format($d['harga']); ?></td>
            <td><?= $d['stok']; ?></td>
            <td>
                <a href="barang_hapus.php?id=<?= $d['id']; ?>" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br><a href="transaksi.php">Ke Hal Transaksi</a>
</body>
</html>
