<?php
$host = "localhost";
$user = "root";
$pass = ""; // XAMPP default on Mac
$dbname = "phpassignment1";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
