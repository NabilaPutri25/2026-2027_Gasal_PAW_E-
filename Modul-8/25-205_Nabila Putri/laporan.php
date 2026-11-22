<?php
require_once "auth.php";
include "koneksi.php";

$level = $_SESSION['level'];
$nama = $_SESSION['nama_lengkap'];

// Ambil semua kolom tabel transaksi secara otomatis
$cek_kolom = mysqli_query($koneksi, "SHOW COLUMNS FROM transaksi");
$daftar_kolom = [];
$kolom_tanggal = "";

if ($cek_kolom) {
    while ($col = mysqli_fetch_assoc($cek_kolom)) {
        $daftar_kolom[] = $col['Field'];
        // Cari kolom bertipe date atau datetime secara otomatis
        if ($kolom_tanggal == "" && (strpos($col['Type'], 'date') !== false || strpos($col['Type'], 'time') !== false)) {
            $kolom_tanggal = $col['Field'];
        }
    }
}

$tgl_mulai = isset($_GET['tgl_mulai']) ? $_GET['tgl_mulai'] : "";
$tgl_selesai = isset($_GET['tgl_selesai']) ? $_GET['tgl_selesai'] : "";

$where = "";
if ($tgl_mulai != "" && $tgl_selesai != "" && $kolom_tanggal != "") {
    $tgl_mulai_aman = mysqli_real_escape_string($koneksi, $tgl_mulai);
    $tgl_selesai_aman = mysqli_real_escape_string($koneksi, $tgl_selesai);
    $where = "WHERE DATE(`$kolom_tanggal`) BETWEEN '$tgl_mulai_aman' AND '$tgl_selesai_aman'";
}

$query_laporan = mysqli_query($koneksi, "SELECT * FROM transaksi $where");

$total_pendapatan = 0;
$jumlah_transaksi = 0;
$data_laporan = [];

if ($query_laporan) {
    while ($row = mysqli_fetch_assoc($query_laporan)) {
        $data_laporan[] = $row;
        // Hitung total dari kolom 'total' atau 'total_harga' jika ada
        if (isset($row['total'])) {
            $total_pendapatan += (float)$row['total'];
        } elseif (isset($row['total_harga'])) {
            $total_pendapatan += (float)$row['total_harga'];
        }
        $jumlah_transaksi++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css?v=5">
</head>
<body>

<div class="sidebar">

    <h2><i class="fas fa-cash-register"></i> SISTEM PENJUALAN</h2>

    <a href="index.php"><i class="fas fa-home"></i> Home</a>

    <?php if ($level == 1) { ?>
        <a href="index.php#barang"><i class="fas fa-database"></i> Data Master</a>
        <div class="submenu">
            <a href="index.php#barang">Data Barang</a>
            <a href="index.php#supplier">Data Supplier</a>
            <a href="index.php#pelanggan">Data Pelanggan</a>
            <a href="index.php#user">Data User</a>
        </div>
    <?php } ?>

    <a href="index.php#transaksi"><i class="fas fa-desktop"></i> Transaksi</a>

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

        <h1>Laporan Penjualan</h1>
        <p>Rekap data transaksi penjualan berdasarkan periode tanggal.</p>

        <?php if ($kolom_tanggal != "") { ?>
            <form method="GET" style="margin-bottom: 20px; background: #fff; padding: 15px; border-radius: 5px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <label>Dari Tanggal:</label>
                <input type="date" name="tgl_mulai" value="<?php echo htmlspecialchars($tgl_mulai); ?>" style="padding: 6px; margin-right: 10px;">

                <label>Sampai Tanggal:</label>
                <input type="date" name="tgl_selesai" value="<?php echo htmlspecialchars($tgl_selesai); ?>" style="padding: 6px; margin-right: 10px;">

                <button type="submit" style="margin-bottom: 0;">Filter Laporan</button>
                <a href="laporan.php" style="margin-left: 10px; text-decoration: none; color: #4e73df; font-weight: 600;">Reset</a>
            </form>
        <?php } ?>

        <div style="display: flex; gap: 20px; margin-bottom: 20px;">
            <div style="background: #fff; padding: 20px; border-left: 4px solid #4e73df; border-radius: 5px; flex: 1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 12px; font-weight: 700; color: #4e73df; text-transform: uppercase;">Total Transaksi</div>
                <div style="font-size: 22px; font-weight: 700; color: #5a5c69; margin-top: 5px;"><?php echo $jumlah_transaksi; ?> Transaksi</div>
            </div>
            <div style="background: #fff; padding: 20px; border-left: 4px solid #1cc88a; border-radius: 5px; flex: 1; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
                <div style="font-size: 12px; font-weight: 700; color: #1cc88a; text-transform: uppercase;">Total Pendapatan</div>
                <div style="font-size: 22px; font-weight: 700; color: #5a5c69; margin-top: 5px;">Rp <?php echo number_format($total_pendapatan, 0, ',', '.'); ?></div>
            </div>
        </div>

        <table>
            <tr>
                <th>No</th>
                <?php foreach ($daftar_kolom as $kolom) { ?>
                    <th><?php echo htmlspecialchars($kolom); ?></th>
                <?php } ?>
            </tr>
            <?php if (count($data_laporan) > 0) { ?>
                <?php $no = 1; foreach ($data_laporan as $row) { ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <?php foreach ($daftar_kolom as $kolom) { ?>
                            <td><?php echo htmlspecialchars((string)$row[$kolom]); ?></td>
                        <?php } ?>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="<?php echo count($daftar_kolom) + 1; ?>" style="text-align: center;">Tidak ada data transaksi pada periode ini.</td>
                </tr>
            <?php } ?>
        </table>

    </div>

</div>

</body>
</html>