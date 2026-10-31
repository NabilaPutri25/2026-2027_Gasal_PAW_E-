<?php
include "koneksi.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    die("Data user tidak ditemukan!");
}

if (isset($_POST['simpan'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
    $hp = mysqli_real_escape_string($koneksi, $_POST['hp']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    $query = "UPDATE user SET
              username='$username',
              password='$password',
              nama='$nama',
              alamat='$alamat',
              hp='$hp',
              level='$level'
              WHERE id_user=$id";

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
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<h2>Edit Data User</h2>

<form method="POST">

    Username:
    <input type="text" name="username"
    value="<?php echo htmlspecialchars($data['username']); ?>" required>
    <br><br>

    Password:
    <input type="text" name="password"
    value="<?php echo htmlspecialchars($data['password']); ?>" required>
    <br><br>

    Nama:
    <input type="text" name="nama"
    value="<?php echo htmlspecialchars($data['nama']); ?>" required>
    <br><br>

    Alamat:
    <textarea name="alamat"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
    <br><br>

    HP:
    <input type="text" name="hp"
    value="<?php echo htmlspecialchars($data['hp']); ?>">
    <br><br>

    Level:
    <select name="level">
        <option value="admin" <?php if ($data['level'] == 'admin') echo 'selected'; ?>>Admin</option>
        <option value="kasir" <?php if ($data['level'] == 'kasir') echo 'selected'; ?>>Kasir</option>
    </select>
    <br><br>

    <button type="submit" name="simpan">Simpan Perubahan</button>
    <a href="index.php">Kembali</a>

</form>

</body>
</html>