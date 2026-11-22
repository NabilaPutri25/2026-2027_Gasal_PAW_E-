<?php
$host = "localhost";
$user = "root";
$pass = ""; // sesuaikan password jika ada
$db   = "penjualan";

$koneksi = mysqli_connect($host, $user, $pass, $db);
// atau jika ingin menyertakan port secara eksplisit:
// $koneksi = mysqli_connect($host, $user, $pass, $db, 3306);
?>