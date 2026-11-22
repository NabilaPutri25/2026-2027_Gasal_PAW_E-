<?php
require_once "auth.php";
include "koneksi.php";

$level_user = $_SESSION['level'];

// Mengatur pilihan tabel sesuai hak akses login
if ($level_user == 1) {
    $tabel = [
        "pelanggan",
        "supplier",
        "barang",
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

$pilihan = "";

// Cek dari GET (saat dropdown dipilih) atau POST (saat form disubmit)
if (isset($_GET['tabel'])) {
    $pilihan = $_GET['tabel'];
} elseif (isset($_POST['tabel'])) {
    $pilihan = $_POST['tabel'];
}

// Jika level 2 mencoba mengakses tabel data master lewat URL, otomatis ditolak[cite: 6]
if (!in_array($pilihan, $tabel)) {
    $pilihan = "";
}

// TAMBAH PELANGGAN (Khusus Level 1)[cite: 6]
if (isset($_POST['simpan_pelanggan']) && $pilihan == "pelanggan" && $level_user == 1) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = "INSERT INTO pelanggan (nama, jenis_kelamin, telp, alamat) VALUES ('$nama', '$jenis_kelamin', '$telp', '$alamat')";
    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=pelanggan");
    exit;
}

// TAMBAH SUPPLIER (Khusus Level 1)[cite: 6]
if (isset($_POST['simpan_supplier']) && $pilihan == "supplier" && $level_user == 1) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = "INSERT INTO supplier (nama, telp, alamat) VALUES ('$nama', '$telp', '$alamat')";
    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=supplier");
    exit;
}

// TAMBAH BARANG (Khusus Level 1)[cite: 6]
if (isset($_POST['simpan_barang']) && $pilihan == "barang" && $level_user == 1) {
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode_barang']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama_barang']);
    $harga = mysqli_real_escape_string($koneksi, $_POST['harga']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $supplier = mysqli_real_escape_string($koneksi, $_POST['supplier_id']);

    $query = "INSERT INTO barang (kode_barang, nama_barang, harga, stok, supplier_id) VALUES ('$kode', '$nama', '$harga', '$stok', '$supplier')";
    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=barang");
    exit;
}

// TAMBAH USER (Khusus Level 1 - Disesuaikan dengan struktur login.php)[cite: 6]
if (isset($_POST['simpan_user']) && $pilihan == "user" && $level_user == 1) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = md5($_POST['password']);
    $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    $query = "INSERT INTO user (username, password, nama_lengkap, alamat, hp, level) VALUES ('$username', '$password', '$nama_lengkap', '$alamat', '$hp', '$level')";
    mysqli_query($koneksi, $query);

    header("Location: tambah.php?tabel=user");
    exit;
}

// TAMBAH PEMBAYARAN
if (isset($_POST['simpan_pembayaran']) && $pilihan == "pembayaran") {
    $waktu_bayar = str_replace("T", " ", $_POST['waktu_bayar']);
    $total = mysqli_real_escape_string($koneksi, $_POST['total']);
    $metode = mysqli_real_escape_string($koneksi, $_POST['metode']);
    $transaksi_id = mysqli_real_escape_string($koneksi, $_POST['transaksi_id']);

    $query = "INSERT INTO pembayaran (waktu_bayar, total, metode, transaksi_id) VALUES ('$waktu_bayar', '$total', '$metode', '$transaksi_id')";
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

    <!-- Form pemilihan tabel menggunakan GET -->
    <form method="GET">
        <label>Pilih Tabel:</label>
        <select name="tabel" required>
            <option value="">-- Pilih Tabel --</option>
            <?php foreach ($tabel as $nama_tabel) { ?>
                <option value="<?php echo $nama_tabel; ?>" <?php if ($pilihan == $nama_tabel) echo "selected"; ?>>
                    <?php echo ucfirst($nama_tabel); ?>
                </option>
            <?php } ?>
        </select>
        <button type="submit">Pilih</button>
    </form>

    <?php if ($pilihan != "") { ?>

        <h3>Tambah Data <?php echo ucfirst($pilihan); ?></h3>

        <!-- FORM PELANGGAN -->
        <?php if ($pilihan == "pelanggan" && $level_user == 1) { ?>
            <form method="POST">
                <input type="hidden" name="tabel" value="pelanggan">
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

                <button type="submit" name="simpan_pelanggan">Simpan Pelanggan</button>
            </form>
        <?php } ?>

        <!-- FORM SUPPLIER -->
        <?php if ($pilihan == "supplier" && $level_user == 1) { ?>
            <form method="POST">
                <input type="hidden" name="tabel" value="supplier">
                Nama Supplier:<br>
                <input type="text" name="nama" required><br><br>

                Telepon:<br>
                <input type="text" name="telp"><br><br>

                Alamat:<br>
                <textarea name="alamat"></textarea><br><br>

                <button type="submit" name="simpan_supplier">Simpan Supplier</button>
            </form>
        <?php } ?>

        <!-- FORM BARANG -->
        <?php if ($pilihan == "barang" && $level_user == 1) { ?>
            <form method="POST">
                <input type="hidden" name="tabel" value="barang">
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
                    <option value="">-- Pilih Supplier --</option>
                    <?php
                    $data_supplier = mysqli_query($koneksi, "SELECT * FROM supplier");
                    while ($s = mysqli_fetch_assoc($data_supplier)) {
                    ?>
                        <option value="<?php echo $s['id']; ?>">
                            <?php echo htmlspecialchars($s['nama']); ?>
                        </option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="simpan_barang">Simpan Barang</button>
            </form>
        <?php } ?>

        <!-- FORM USER -->
        <?php if ($pilihan == "user" && $level_user == 1) { ?>
            <form method="POST">
                <input type="hidden" name="tabel" value="user">
                Username:<br>
                <input type="text" name="username" required><br><br>

                Password:<br>
                <input type="password" name="password" required><br><br>

                Nama Lengkap:<br>
                <input type="text" name="nama_lengkap" required><br><br>

                Alamat:<br>
                <textarea name="alamat"></textarea><br><br>

                HP:<br>
                <input type="text" name="hp"><br><br>

                Level:<br>
                <select name="level" required>
                    <option value="1">1 - Owner</option>
                    <option value="2">2 - Kasir</option>
                </select><br><br>

                <button type="submit" name="simpan_user">Simpan User</button>
            </form>
        <?php } ?>

        <!-- FORM PEMBAYARAN -->
        <?php if ($pilihan == "pembayaran") { ?>
            <form method="POST">
                <input type="hidden" name="tabel" value="pembayaran">
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
                    $data_transaksi = mysqli_query($koneksi, "SELECT id_transaksi FROM transaksi ORDER BY id_transaksi DESC");
                    while ($t = mysqli_fetch_assoc($data_transaksi)) {
                    ?>
                        <option value="<?php echo $t['id_transaksi']; ?>">
                            Transaksi <?php echo $t['id_transaksi']; ?>
                        </option>
                    <?php } ?>
                </select><br><br>

                <button type="submit" name="simpan_pembayaran">Simpan Pembayaran</button>
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