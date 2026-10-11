<?php
$fruits = array("Avocado", "Blueberry", "Cherry");
array_push($fruits, "Apple", "Banana", "Grape", "Mango", "Orange");
unset($fruits[2]);
$fruits = array_values($fruits);
print_r($fruits);
echo "<br>";
echo "Indeks tertinggi: " . (count($fruits) - 1);
?>