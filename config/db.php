<?php

$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "karyashala";

// Create Connection
$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}

$conn->set_charset("utf8");

?>