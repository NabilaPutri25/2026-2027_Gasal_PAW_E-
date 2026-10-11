<?php
$mahasiswa = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665"),
    array("Dina", "220404", "0812345670"),
    array("Evan", "220405", "0812345671"),
    array("Farah", "220406", "0812345672"),
    array("Gina", "220407", "0812345673"),
    array("Hadi", "220408", "0812345674")
);
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Name</th><th>NIM</th><th>Mobile</th></tr>";
for ($i = 0; $i < count($mahasiswa); $i++) {
    echo "<tr>";
    echo "<td>" . $mahasiswa[$i][0] . "</td>";
    echo "<td>" . $mahasiswa[$i][1] . "</td>";
    echo "<td>" . $mahasiswa[$i][2] . "</td>";
    echo "</tr>";
}
echo "</table>";
?>