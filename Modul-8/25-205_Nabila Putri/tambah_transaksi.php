<?php
include "koneksi.php";

$pesan = "";

// PROSES SIMPAN TRANSAKSI
if (isset($_POST['simpan_transaksi'])) {

    $pelanggan = $_POST['pelanggan_id'];
    $qty = $_POST['qty'];
    $metode = $_POST['metode'];

    $total = 0;
    $daftar_barang = [];

    // Memeriksa barang yang dipilih
    foreach ($qty as $id_barang => $jumlah) {

        $jumlah = (int)$jumlah;

        if ($jumlah > 0) {

            $id_barang = (int)$id_barang;

            $query = mysqli_query(
                $koneksi,
                "SELECT * FROM barang WHERE id = $id_barang"
            );

            $barang = mysqli_fetch_assoc($query);

            if (!$barang || $jumlah > $barang['stok']) {
                die("Stok barang tidak mencukupi!");
            }

            $subtotal = $barang['harga'] * $jumlah;
            $total += $subtotal;

            $daftar_barang[] = [
                "id" => $id_barang,
                "harga" => $barang['harga'],
                "qty" => $jumlah
            ];
        }
    }

    if (count($daftar_barang) == 0) {
        die("Pilih minimal satu barang!");
    }

    // Memulai transaksi database
    mysqli_begin_transaction($koneksi);

    try {

        // Menyimpan data transaksi
        $query = mysqli_query(
            $koneksi,
            "INSERT INTO transaksi
            (waktu_transaksi, keterangan, total, pelanggan_id)
            VALUES
            (NOW(), 'Pembelian barang', '$total', '$pelanggan')"
        );

        if (!$query) {
            throw new Exception("Gagal menyimpan transaksi.");
        }

        $id_transaksi = mysqli_insert_id($koneksi);

        // Menyimpan detail dan mengurangi stok
        foreach ($daftar_barang as $item) {

            $id_barang = $item['id'];
            $harga = $item['harga'];
            $jumlah = $item['qty'];

            $query_detail = mysqli_query(
                $koneksi,
                "INSERT INTO transaksi_detail
                (transaksi_id, barang_id, harga, qty)
                VALUES
                ('$id_transaksi', '$id_barang', '$harga', '$jumlah')"
            );

            if (!$query_detail) {
                throw new Exception("Gagal menyimpan detail transaksi.");
            }

            $query_stok = mysqli_query(
                $koneksi,
                "UPDATE barang
                SET stok = stok - $jumlah
                WHERE id = $id_barang AND stok >= $jumlah"
            );

            if (!$query_stok || mysqli_affected_rows($koneksi) != 1) {
                throw new Exception("Gagal memperbarui stok barang.");
            }
        }

        // Menyimpan pembayaran
        $query_bayar = mysqli_query(
            $koneksi,
            "INSERT INTO pembayaran
            (waktu_bayar, total, metode, transaksi_id)
            VALUES
            (NOW(), '$total', '$metode', '$id_transaksi')"
        );

        if (!$query_bayar) {
            throw new Exception("Gagal menyimpan pembayaran.");
        }

        // Menyelesaikan transaksi database
        mysqli_commit($koneksi);

        echo "<script>
            alert('Transaksi berhasil disimpan!');
            window.location='tambah_transaksi.php';
        </script>";
        exit;

    } catch (Exception $e) {

        mysqli_rollback($koneksi);
        die($e->getMessage());
    }
}

// Mengambil data pelanggan
$query_pelanggan = mysqli_query(
    $koneksi,
    "SELECT * FROM pelanggan"
);

// Mengambil data barang
$query_barang = mysqli_query(
    $koneksi,
    "SELECT * FROM barang"
);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tambah Transaksi</title>
</head>
<body>

    <h2>Form Tambah Transaksi</h2>

    <form method="POST">

        <label>Pilih Pelanggan:</label><br>

        <select name="pelanggan_id" required>
            <option value="">-- Pilih Pelanggan --</option>

            <?php while ($p = mysqli_fetch_assoc($query_pelanggan)) { ?>
                <option value="<?php echo $p['id']; ?>">
                    <?php echo $p['nama']; ?>
                </option>
            <?php } ?>

        </select>

        <br><br>

        <h3>Pilih Barang</h3>

        <?php while ($b = mysqli_fetch_assoc($query_barang)) { ?>

            <label>
                <?php echo $b['nama_barang']; ?>
                - Rp<?php echo number_format($b['harga'], 0, ',', '.'); ?>
                (Stok: <?php echo $b['stok']; ?>)
            </label>

            <input
                type="number"
                class="jumlah"
                data-harga="<?php echo $b['harga']; ?>"
                name="qty[<?php echo $b['id']; ?>]"
                min="0"
                max="<?php echo $b['stok']; ?>"
                value="0"
            >

            <br><br>

        <?php } ?>

        <h3>Total Belanja: Rp<span id="total">0</span></h3>

        <label>Metode Pembayaran:</label><br>

        <select name="metode" required>
            <option value="">-- Pilih Pembayaran --</option>
            <option value="Tunai">Tunai</option>
            <option value="Transfer">Transfer</option>
            <option value="EDC">EDC</option>
        </select>

        <br><br>

        <button type="submit" name="simpan_transaksi">
            Simpan Transaksi
        </button>

    </form>

    <script>
        function hitungTotal() {
            let total = 0;

            document.querySelectorAll(".jumlah").forEach(function(input) {
                let harga = Number(input.dataset.harga);
                let jumlah = Number(input.value);

                total += harga * jumlah;
            });

            document.getElementById("total").innerText =
                total.toLocaleString("id-ID");
        }

        document.querySelectorAll(".jumlah").forEach(function(input) {
            input.addEventListener("input", hitungTotal);
        });
    </script>

    <br>
    <a href="index.php">Kembali ke Halaman Utama</a>

</body>
</html>