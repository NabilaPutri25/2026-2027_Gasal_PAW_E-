<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM transaksi WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data transaksi tidak ditemukan!");
}

$pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");

if (isset($_POST['simpan'])) {
    $waktu = mysqli_real_escape_string($koneksi, $_POST['waktu_transaksi']);
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $pelanggan_id = (int) $_POST['pelanggan_id'];

    $update = "UPDATE transaksi SET
               waktu_transaksi='$waktu',
               keterangan='$keterangan',
               pelanggan_id=$pelanggan_id
               WHERE id=$id";

    if (mysqli_query($koneksi, $update)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengubah transaksi: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Transaksi</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Data Transaksi</h2>

<form method="POST">

    Pelanggan:
    <select name="pelanggan_id" required>
        <?php while ($p = mysqli_fetch_assoc($pelanggan)) { ?>
            <option value="<?php echo $p['id']; ?>"
                <?php if ($data['pelanggan_id'] == $p['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($p['nama']); ?>
            </option>
        <?php } ?>
    </select>

    <br><br>

    Waktu Transaksi:
    <input type="datetime-local" name="waktu_transaksi"
        value="<?php echo $data['waktu_transaksi'] ? date('Y-m-d\TH:i', strtotime($data['waktu_transaksi'])) : ''; ?>"
        required>

    <br><br>

    Keterangan:
    <textarea name="keterangan"><?php echo htmlspecialchars($data['keterangan'] ?? ''); ?></textarea>

    <br><br>

    <p>Total transaksi tidak diubah melalui halaman ini.</p>

    <button type="submit" name="simpan">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>

</form>

</body>
</html>