<?php
require_once "session.php";
include "koneksi.php";

// Jika sudah login, langsung ke halaman utama
if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
    exit;
}

$pesan = "";

if (isset($_POST['login'])) {

    $username = mysqli_real_escape_string(
        $koneksi, $_POST['username']
    );

    $password = md5($_POST['password']);

    $query = mysqli_query(
        $koneksi,
        "SELECT * FROM user WHERE username='$username'"
    );

    $user = mysqli_fetch_assoc($query);

    if ($user && $user['password'] == $password) {

        session_regenerate_id(true);

        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
        $_SESSION['level'] = $user['level'];

        header("Location: index.php");
        exit;

    } else {
        $pesan = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Sistem Penjualan</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <h2>Login Sistem Penjualan</h2>

    <?php if ($pesan != "") { ?>
        <p style="color:red;">
            <?php echo $pesan; ?>
        </p>
    <?php } ?>

    <form method="POST">

        Username:<br>
        <input type="text" name="username" required>
        <br><br>

        Password:<br>
        <input type="password" name="password" required>
        <br><br>

        <button type="submit" name="login">
            Login
        </button>

    </form>

</body>
</html>