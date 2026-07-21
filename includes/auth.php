<?php
session_start();

if (!isset($_SESSION["ic_number"]) || !isset($_SESSION["role"])) {
    header("Location: ../login.php");
    exit();
}

$currentFolder = basename(dirname($_SERVER["PHP_SELF"]));

if ($currentFolder == "admin" && $_SESSION["role"] != "Admin") {
    header("Location: ../login.php");
    exit();
}

if ($currentFolder == "employee" && $_SESSION["role"] != "Karyashala Admin") {
    header("Location: ../login.php");
    exit();
}