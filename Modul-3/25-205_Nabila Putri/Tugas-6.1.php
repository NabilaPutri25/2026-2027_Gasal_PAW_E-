<?php
// 1. array_push()
$buah = array("A");
echo "Array awal: A<br>";
array_push($buah, "B");
echo "Hasil array_push: ";
print_r($buah);
echo "<br><br>";

// 2. array_merge()
$buah1 = array("A", "B");
$buah2 = array("C");
echo "Array awal: A, B digabung dengan C<br>";
$gabung = array_merge($buah1, $buah2);
echo "Hasil array_merge: ";
print_r($gabung);
echo "<br><br>";

// 3. array_values()
$data = array("X" => 1, "Y" => 2);
echo "Array awal: X => 1, Y => 2<br>";
$nilai = array_values($data);
echo "Hasil array_values: ";
print_r($nilai);
echo "<br><br>";

// 4. array_search()
$huruf = array("A", "B", "C");
echo "Mencari B pada array A, B, C<br>";
$cari = array_search("B", $huruf);
echo "Hasil array_search: " . $cari;
echo "<br><br>";

// 5. array_filter()
$angka = array(0, 1, false, 2, "", 3, "array");
echo "Array awal: 0, 1, false, 2, kosong, 3, array<br>";
$hasil = array_filter($angka);
echo "Hasil array_filter: ";
print_r($hasil);
echo "<br><br>";

// 6. Sorting array biasa
$angka2 = array(3, 1, 2);
echo "Array awal: 3, 1, 2<br>";
sort($angka2);
echo "Hasil sort: ";
print_r($angka2);
echo "<br>";
rsort($angka2);
echo "Hasil rsort: ";
print_r($angka2);
echo "<br><br>";

// 7. Sorting array asosiatif
$nilai = array(
    "Peter" => 35,
    "Ben" => 37,
    "Joe" => 43
);
echo "Array awal:<br>";
print_r($nilai);
echo "<br><br>";
asort($nilai);
echo "Hasil asort:<br>";
print_r($nilai);
echo "<br><br>";
arsort($nilai);
echo "Hasil arsort:<br>";
print_r($nilai);
echo "<br><br>";
ksort($nilai);
echo "Hasil ksort:<br>";
print_r($nilai);
echo "<br><br>";
krsort($nilai);
echo "Hasil krsort:<br>";
print_r($nilai);
?>