<?php
include "koneksi.php";

$tabel = [
    "pelanggan",
    "supplier",
    "barang",
    "user",
    "transaksi",
    "transaksi_detail",
    "pembayaran"
];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h1>Data Penjualan</h1>
    <p>Menampilkan seluruh data dari database penjualan.</p>

    <a href="tambah.php">
        <button type="button">+ Tambah Data</button>
    </a>

    <?php foreach ($tabel as $nama_tabel) { ?>

        <h2>Data <?php echo ucfirst($nama_tabel); ?></h2>

        <?php
        $query = mysqli_query($koneksi, "SELECT * FROM `$nama_tabel`");

        if (!$query) {
            echo "Gagal mengambil data: " . mysqli_error($koneksi);
            continue;
        }
        ?>

        <table border="1" cellpadding="8">
            <tr>
                <?php
                $kolom = mysqli_fetch_fields($query);

                foreach ($kolom as $field) {
                    echo "<th>" . htmlspecialchars($field->name) . "</th>";
                }

                if (
                    $nama_tabel == "pelanggan" ||
                    $nama_tabel == "supplier" ||
                    $nama_tabel == "barang" ||
                    $nama_tabel == "user" ||
                    $nama_tabel == "transaksi" ||
                    $nama_tabel == "transaksi_detail" ||
                    $nama_tabel == "pembayaran"
                ) {
                    echo "<th>Aksi</th>";
                }
                ?>
            </tr>

            <?php while ($data = mysqli_fetch_assoc($query)) { ?>
                <tr>

                    <?php foreach ($data as $isi) { ?>
                        <td>
                            <?php echo htmlspecialchars((string)$isi); ?>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "pelanggan") { ?>
                        <td>
                            <a href="edit.php?id=<?php echo $data['id']; ?>">Edit</a>
                            |
                            <a href="hapus.php?id=<?php echo $data['id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "supplier") { ?>
                        <td>
                            <a href="edit_supplier.php?id=<?php echo $data['id']; ?>">Edit</a>
                            |
                            <a href="hapus_supplier.php?id=<?php echo $data['id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "barang") { ?>
                        <td>
                            <a href="edit_barang.php?id=<?php echo $data['id']; ?>">Edit</a>
                            |
                            <a href="hapus_barang.php?id=<?php echo $data['id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "user") { ?>
                        <td>
                            <a href="edit_user.php?id=<?php echo $data['id_user']; ?>">Edit</a>
                            |
                            <a href="hapus_user.php?id=<?php echo $data['id_user']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "transaksi") { ?>
                        <td>
                            <a href="edit_transaksi.php?id=<?php echo $data['id']; ?>">Edit</a>
                            |
                            <a href="hapus_transaksi.php?id=<?php echo $data['id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "transaksi_detail") { ?>
                        <td>
                            <a href="edit_transaksi_detail.php?transaksi_id=<?php echo $data['transaksi_id']; ?>&barang_id=<?php echo $data['barang_id']; ?>">Edit</a>
                            |
                            <a href="hapus_transaksi_detail.php?transaksi_id=<?php echo $data['transaksi_id']; ?>&barang_id=<?php echo $data['barang_id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                    <?php if ($nama_tabel == "pembayaran") { ?>
                        <td>
                            <a href="edit_pembayaran.php?id=<?php echo $data['id']; ?>">Edit</a>
                            |
                            <a href="hapus_pembayaran.php?id=<?php echo $data['id']; ?>">Hapus</a>
                        </td>
                    <?php } ?>

                </tr>
            <?php } ?>

        </table>

        <br>

    <?php } ?>

</body>
</html>