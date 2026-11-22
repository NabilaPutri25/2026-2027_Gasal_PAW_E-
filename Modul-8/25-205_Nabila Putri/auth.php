<?php
require_once "session.php";

// Memeriksa apakah pengguna sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: login.php");
    exit;
}
?>