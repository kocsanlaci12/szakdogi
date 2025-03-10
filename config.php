<?php
$servername = "localhost";
$username = "root"; // Adatbázis felhasználónév
$password = ""; // Adatbázis jelszó (ha van)
$dbname = "registerusers"; // Adatbázis neve

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Kapcsolódási hiba: " . $conn->connect_error);
}
?>
