<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data pelanggan tidak ditemukan!");
}

if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $jenis_kelamin = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $telp = mysqli_real_escape_string($koneksi, $_POST['telp']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $query = "UPDATE pelanggan SET
              nama='$nama',
              jenis_kelamin='$jenis_kelamin',
              telp='$telp',
              alamat='$alamat'
              WHERE id=$id";

    if (mysqli_query($koneksi, $query)) {
        header("Location: index.php");
        exit;
    } else {
        echo "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Pelanggan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Edit Data Pelanggan</h2>

    <form method="POST">
        Nama:
        <input type="text" name="nama"
        value="<?php echo htmlspecialchars($data['nama']); ?>" required>
        <br><br>

        Jenis Kelamin:
        <select name="jenis_kelamin">
            <option value="L" <?php if ($data['jenis_kelamin'] == 'L') echo 'selected'; ?>>
                Laki-laki
            </option>
            <option value="P" <?php if ($data['jenis_kelamin'] == 'P') echo 'selected'; ?>>
                Perempuan
            </option>
        </select>
        <br><br>

        Telepon:
        <input type="text" name="telp"
        value="<?php echo htmlspecialchars($data['telp']); ?>">
        <br><br>

        Alamat:
        <textarea name="alamat"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
        <br><br>

        <button type="submit" name="simpan">Simpan Perubahan</button>
        <a href="index.php">Kembali</a>
    </form>

</body>
</html>
