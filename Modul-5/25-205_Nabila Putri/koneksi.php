<?php
$koneksi = mysqli_connect("localhost", "root", "", "penjualan", 3307);

if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>