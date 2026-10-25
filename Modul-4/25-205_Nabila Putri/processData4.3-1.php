<!DOCTYPE html>
<html>
<head>
    <title>Hasil Register</title>
</head>
<body>
<h2>Hasil Data Pribadi</h2>

<?php
foreach ($_POST as $key => $value) {
    echo $key . ": " . $value . "<br>";
}
?>

</body>
</html>
