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
end($height);
echo "Data terakhir: " . key($height) . " = " . current($height) . " cm";
unset($height["Farah"]);
echo "<br>";
end($height);
echo "Data terakhir setelah penghapusan: " . key($height) . " = " . current($height) . " cm";
?>