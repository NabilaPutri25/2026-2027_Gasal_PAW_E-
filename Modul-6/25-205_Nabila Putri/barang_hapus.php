<?php
include 'koneksi.php';

$id = $_GET['id'];

// Cek apakah barang sudah digunakan di transaksi_detail
$cek = mysqli_query($conn, "SELECT * FROM transaksi_detail WHERE barang_id = '$id'");

if (mysqli_num_rows($cek) > 0) {
    // Barang sudah dipakai, tidak boleh dihapus
    header("Location: barang.php?pesan=terpakai");
} else {
    // Barang belum dipakai, aman dihapus
    mysqli_query($conn, "DELETE FROM barang WHERE id = '$id'");
    header("Location: barang.php?pesan=sukses");
}
?>