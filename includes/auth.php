<?php

if(session_status() == PHP_SESSION_NONE)
{
    session_start();
}

/* ==========================================
   CHECK LOGIN
========================================== */

if(
    !isset($_SESSION['loggedin']) ||
    $_SESSION['loggedin'] !== true
)
{
    header("Location:../index.php");
    exit();
}

/* ==========================================
   CHECK REQUIRED SESSION VALUES
========================================== */

$required = array(

    "ic_no",
    "name",
    "role"

);

foreach($required as $value)
{
    if(!isset($_SESSION[$value]))
    {
        session_destroy();

        header("Location:../index.php");

        exit();
    }
}

?>