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

$pilihan = "";

if (isset($_GET['tabel'])) {
    $pilihan = $_GET['tabel'];

    if (!in_array($pilihan, $tabel)) {
        $pilihan = "";
    }
}

// TAMBAH PELANGGAN
if (isset($_POST['simpan_pelanggan']) && $pilihan == "pelanggan") {
    $nama = $_POST['nama'];
    $jenis_kelamin = $_POST['jenis_kelamin'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO pelanggan
              (nama, jenis_kelamin, telp, alamat)
              VALUES ('$nama', '$jenis_kelamin', '$telp', '$alamat')";

    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=pelanggan");
    exit;
}

// TAMBAH SUPPLIER
if (isset($_POST['simpan_supplier']) && $pilihan == "supplier") {
    $nama = $_POST['nama'];
    $telp = $_POST['telp'];
    $alamat = $_POST['alamat'];

    $query = "INSERT INTO supplier (nama, telp, alamat)
              VALUES ('$nama', '$telp', '$alamat')";

    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=supplier");
    exit;
}

// TAMBAH BARANG
if (isset($_POST['simpan_barang']) && $pilihan == "barang") {
    $kode = $_POST['kode_barang'];
    $nama = $_POST['nama_barang'];
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $supplier = $_POST['supplier_id'];

    $query = "INSERT INTO barang
              (kode_barang, nama_barang, harga, stok, supplier_id)
              VALUES ('$kode', '$nama', '$harga', '$stok', '$supplier')";

    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=barang");
    exit;
}

// TAMBAH USER
if (isset($_POST['simpan_user']) && $pilihan == "user") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $nama = $_POST['nama'];
    $alamat = $_POST['alamat'];
    $hp = $_POST['hp'];
    $level = $_POST['level'];

    $query = "INSERT INTO user
              (username, password, nama, alamat, hp, level)
              VALUES
              ('$username', '$password', '$nama', '$alamat', '$hp', '$level')";

    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=user");
    exit;
}

// TAMBAH PEMBAYARAN
if (isset($_POST['simpan_pembayaran']) && $pilihan == "pembayaran") {
    $waktu_bayar = str_replace("T", " ", $_POST['waktu_bayar']);
    $total = $_POST['total'];
    $metode = $_POST['metode'];
    $transaksi_id = $_POST['transaksi_id'];

    $query = "INSERT INTO pembayaran
              (waktu_bayar, total, metode, transaksi_id)
              VALUES
              ('$waktu_bayar', '$total', '$metode', '$transaksi_id')";

    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=pembayaran");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Data</title>
</head>
<body>

    <h2>Tambah Data Database Penjualan</h2>

    <form method="GET">
        <label>Pilih Tabel:</label>

        <select name="tabel" required>
            <option value="">-- Pilih Tabel --</option>

            <?php foreach ($tabel as $nama_tabel) { ?>
                <option value="<?php echo $nama_tabel; ?>"
                <?php if ($pilihan == $nama_tabel) echo "selected"; ?>>
                    <?php echo ucfirst($nama_tabel); ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit">Pilih</button>
    </form>

    <?php if ($pilihan != "") { ?>

        <h3>Tambah Data <?php echo ucfirst($pilihan); ?></h3>

        <!-- FORM PELANGGAN -->
        <?php if ($pilihan == "pelanggan") { ?>
            <form method="POST">
                Nama Pelanggan:<br>
                <input type="text" name="nama" required><br><br>

                Jenis Kelamin:<br>
                <select name="jenis_kelamin" required>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                </select><br><br>

                Telepon:<br>
                <input type="text" name="telp"><br><br>

                Alamat:<br>
                <textarea name="alamat"></textarea><br><br>

                <button type="submit" name="simpan_pelanggan">
                    Simpan Pelanggan
                </button>
            </form>
        <?php } ?>

        <!-- FORM SUPPLIER -->
        <?php if ($pilihan == "supplier") { ?>
            <form method="POST">
                Nama Supplier:<br>
                <input type="text" name="nama" required><br><br>

                Telepon:<br>
                <input type="text" name="telp"><br><br>

                Alamat:<br>
                <textarea name="alamat"></textarea><br><br>

                <button type="submit" name="simpan_supplier">
                    Simpan Supplier
                </button>
            </form>
        <?php } ?>

        <!-- FORM BARANG -->
        <?php if ($pilihan == "barang") { ?>
            <form method="POST">
                Kode Barang:<br>
                <input type="text" name="kode_barang" required><br><br>

                Nama Barang:<br>
                <input type="text" name="nama_barang" required><br><br>

                Harga:<br>
                <input type="number" name="harga" required><br><br>

                Stok:<br>
                <input type="number" name="stok" required><br><br>

                Supplier:<br>
                <select name="supplier_id" required>
                    <?php
                    $data_supplier = mysqli_query($koneksi, "SELECT * FROM supplier");

                    while ($s = mysqli_fetch_assoc($data_supplier)) {
                    ?>
                        <option value="<?php echo $s['id']; ?>">
                            <?php echo $s['nama']; ?>
                        </option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="simpan_barang">
                    Simpan Barang
                </button>
            </form>
        <?php } ?>

        <!-- FORM USER -->
        <?php if ($pilihan == "user") { ?>
            <form method="POST">
                Username:<br>
                <input type="text" name="username" required><br><br>

                Password:<br>
                <input type="password" name="password" required><br><br>

                Nama Lengkap:<br>
                <input type="text" name="nama" required><br><br>

                Alamat:<br>
                <textarea name="alamat"></textarea><br><br>

                HP:<br>
                <input type="text" name="hp"><br><br>

                Level:<br>
                <select name="level" required>
                    <option value="admin">Admin</option>
                    <option value="kasir">Kasir</option>
                </select><br><br>

                <button type="submit" name="simpan_user">
                    Simpan User
                </button>
            </form>
        <?php } ?>

        <!-- FORM PEMBAYARAN -->
        <?php if ($pilihan == "pembayaran") { ?>
            <form method="POST">
                Waktu Bayar:<br>
                <input type="datetime-local" name="waktu_bayar" required><br><br>

                Total Pembayaran:<br>
                <input type="number" name="total" min="0" required><br><br>

                Metode Pembayaran:<br>
                <select name="metode" required>
                    <option value="Tunai">Tunai</option>
                    <option value="Transfer">Transfer</option>
                    <option value="QRIS">QRIS</option>
                </select><br><br>

                Transaksi:<br>
                <select name="transaksi_id" required>
                    <option value="">-- Pilih Transaksi --</option>

<?php
$data_transaksi = mysqli_query(
    $koneksi,
    "SELECT id FROM transaksi ORDER BY id DESC"
);

while ($t = mysqli_fetch_assoc($data_transaksi)) {
?>
    <option value="<?php echo $t['id']; ?>">
        Transaksi <?php echo $t['id']; ?>
    </option>
<?php } ?>

</select><br><br>

<button type="submit" name="simpan_pembayaran">
    Simpan Pembayaran
</button>
</form>
<?php } ?>

<!-- FORM TRANSAKSI -->
<?php if ($pilihan == "transaksi") { ?>
    <p>Untuk menambahkan transaksi, gunakan halaman berikut:</p>
    <a href="tambah_transaksi.php">Tambah Transaksi</a>
<?php } ?>

<!-- FORM TRANSAKSI DETAIL -->
<?php if ($pilihan == "transaksi_detail") { ?>
    <p>Detail transaksi ditambahkan melalui halaman transaksi.</p>
    <a href="tambah_transaksi.php">Tambah Detail Transaksi</a>
<?php } ?>

<?php } ?>

<br>
<a href="index.php">Kembali ke Halaman Utama</a>

</body>
</html>