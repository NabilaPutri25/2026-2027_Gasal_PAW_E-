<?php
$height = array(
	"Andy" => "176",
	"Barry" => "165",
	"Charlie" => "170"
);
$height["Dina"] = "160";
$height["Evan"] = "172";
$height["Farah"] = "168";
$height["Gina"] = "175";
$height["Hadi"] = "180";
foreach ($height as $nama => $tinggi){
	echo $nama . " memiliki tinggi " . $tinggi . " cm<br>";
}
?>