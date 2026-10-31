<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi,
    "SELECT * FROM pembayaran WHERE id = $id");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pembayaran tidak ditemukan!");
}

if (isset($_POST['simpan'])) {

    $waktu_bayar = mysqli_real_escape_string(
        $koneksi, $_POST['waktu_bayar']
    );

    $metode = mysqli_real_escape_string(
        $koneksi, $_POST['metode']
    );

    mysqli_query($koneksi,
        "UPDATE pembayaran
         SET waktu_bayar = '$waktu_bayar',
             metode = '$metode'
         WHERE id = $id");

    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pembayaran</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Pembayaran</h2>

<form method="POST">

    <label>Waktu Bayar</label><br>
    <input type="datetime-local" name="waktu_bayar"
        value="<?php echo $data['waktu_bayar']
            ? date('Y-m-d\TH:i', strtotime($data['waktu_bayar']))
            : ''; ?>">
    <br><br>

    <label>Metode Pembayaran</label><br>
    <select name="metode" required>
        <option value="Tunai"
            <?php if ($data['metode'] == 'Tunai') echo 'selected'; ?>>
            Tunai
        </option>

        <option value="Transfer"
            <?php if ($data['metode'] == 'Transfer') echo 'selected'; ?>>
            Transfer
        </option>

        <option value="QRIS"
            <?php if ($data['metode'] == 'QRIS') echo 'selected'; ?>>
            QRIS
        </option>
    </select>
    <br><br>

    <button type="submit" name="simpan">
        Simpan Perubahan
    </button>

    <a href="index.php">Batal</a>

</form>

</body>
</html>