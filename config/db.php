<?php

/* ==========================================
   DATABASE CONFIGURATION
========================================== */

$host = "localhost";
$user = "root";
$password = "";
$database = "karyashala";

/* ==========================================
   CREATE CONNECTION
========================================== */

$conn = new mysqli($host, $user, $password, $database);

/* ==========================================
   CHECK CONNECTION
========================================== */

if ($conn->connect_error)
{
    die("Database Connection Failed : " . $conn->connect_error);
}

/* ==========================================
   SET CHARACTER SET
========================================== */

$conn->set_charset("utf8mb4");

/* ==========================================
   SET TIMEZONE
========================================== */

date_default_timezone_set("Asia/Kolkata");

?>