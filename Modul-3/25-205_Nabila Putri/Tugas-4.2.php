<?php
$weight = array(
	"Andy" => "65",
    "Barry" => "70",
    "Charlie" => "60"
);
$nama = array_keys($weight);
for ($i = 0; $i < count($weight); $i++) {
    echo $nama[$i] . " memiliki berat " . $weight[$nama[$i]] . " kg<br>";
}
?>