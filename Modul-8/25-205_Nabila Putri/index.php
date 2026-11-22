<?php
require_once "auth.php";
include "koneksi.php";

$level = $_SESSION['level'];
$nama = $_SESSION['nama_lengkap'];

// Mengatur tabel berdasarkan hak akses
if ($level == 1) {
    $tabel = [
        "barang",
        "supplier",
        "pelanggan",
        "user",
        "transaksi",
        "transaksi_detail",
        "pembayaran"
    ];
} else {
    $tabel = [
        "transaksi",
        "transaksi_detail",
        "pembayaran"
    ];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Database Penjualan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<div class="sidebar">

    <h2><i class="fas fa-cash-register"></i> SISTEM PENJUALAN</h2>

    <a href="index.php"><i class="fas fa-home"></i> Home</a>

    <?php if ($level == 1) { ?>
        <a href="#barang"><i class="fas fa-database"></i> Data Master</a>
        <div class="submenu">
            <a href="#barang">Data Barang</a>
            <a href="#supplier">Data Supplier</a>
            <a href="#pelanggan">Data Pelanggan</a>
            <a href="#user">Data User</a>
        </div>
    <?php } ?>

    <a href="#transaksi"><i class="fas fa-desktop"></i> Transaksi</a>

    <a href="laporan.php"><i class="fas fa-chart-bar"></i> Laporan</a>

    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>

</div>

<div class="main-content">

    <div class="topbar">
        <div class="brand-title">SISTEM PENJUALAN</div>
        <div class="user-info">
            <i class="fas fa-user-circle"></i> Selamat datang, <b><?php echo htmlspecialchars($nama); ?></b>
        </div>
    </div>

    <div class="container-isi">

        <h1>Data Penjualan</h1>
        <p>Menampilkan seluruh data dari database penjualan.</p>

        <a href="tambah.php">
            <button type="button">+ Tambah Data</button>
        </a>

        <?php foreach ($tabel as $nama_tabel) { ?>

            <h2 id="<?php echo $nama_tabel; ?>">
                Data <?php echo ucfirst($nama_tabel); ?>
            </h2>

            <?php
            $query = mysqli_query(
                $koneksi,
                "SELECT * FROM `$nama_tabel`"
            );

            if (!$query) {
                echo "Gagal mengambil data: " . mysqli_error($koneksi);
                continue;
            }
            ?>

            <table>

                <tr>
                    <?php
                    $kolom = mysqli_fetch_fields($query);

                    foreach ($kolom as $field) {
                        echo "<th>" .
                            htmlspecialchars($field->name) .
                            "</th>";
                    }

                    echo "<th>Aksi</th>";
                    ?>
                </tr>

                <?php while ($data = mysqli_fetch_assoc($query)) { ?>

                    <tr>

                        <?php foreach ($data as $isi) { ?>
                            <td>
                                <?php
                                echo htmlspecialchars((string)$isi);
                                ?>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "pelanggan") { ?>
                            <td>
                                <a href="edit.php?id=<?php echo $data['id']; ?>">Edit</a> |
                                <a href="hapus.php?id=<?php echo $data['id']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "supplier") { ?>
                            <td>
                                <a href="edit_supplier.php?id=<?php echo $data['id']; ?>">Edit</a> |
                                <a href="hapus_supplier.php?id=<?php echo $data['id']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "barang") { ?>
                            <td>
                                <a href="edit_barang.php?id=<?php echo $data['id']; ?>">Edit</a> |
                                <a href="hapus_barang.php?id=<?php echo $data['id']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "user") { ?>
                            <td>
                                <a href="edit_user.php?id=<?php echo $data['id_user']; ?>">Edit</a> |
                                <a href="hapus_user.php?id=<?php echo $data['id_user']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "transaksi") { ?>
                            <td>
                                <a href="edit_transaksi.php?id=<?php echo $data['id_transaksi']; ?>">Edit</a> |
                                <a href="hapus_transaksi.php?id=<?php echo $data['id_transaksi']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "transaksi_detail") { ?>
                            <td>
                                <a href="edit_transaksi_detail.php?id=<?php echo $data['id_detail']; ?>">Edit</a> |
                                <a href="hapus_transaksi_detail.php?id=<?php echo $data['id_detail']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                        <?php if ($nama_tabel == "pembayaran") { ?>
                            <td>
                                <a href="edit_pembayaran.php?id=<?php echo $data['id_pembayaran']; ?>">Edit</a> |
                                <a href="hapus_pembayaran.php?id=<?php echo $data['id_pembayaran']; ?>">Hapus</a>
                            </td>
                        <?php } ?>

                    </tr>

                <?php } ?>

            </table>

            <br>

        <?php } ?>

    </div>

</div>

</body>
</html>
