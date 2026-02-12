<?php
$conn = new mysqli("localhost", "root", "", "phpassignment1");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
