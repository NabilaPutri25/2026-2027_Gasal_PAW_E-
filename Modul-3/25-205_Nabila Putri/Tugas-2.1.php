<?php
$fruits = array("Avocado", "Blueberry", "Cherry");
$dataBaru = array("Apple", "Banana", "Grape", "Mango", "Orange");
for ($i = 0; $i < count($dataBaru); $i++){
	$fruits[] = $dataBaru[$i];
}
echo "Jumlah data: " . count($fruits);
echo "<br>";
for ($i = 0; $i < count($fruits); $i++){
	echo $fruits[$i] . "<br>";
}
?>