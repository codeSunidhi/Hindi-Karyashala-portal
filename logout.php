<?php

session_start();

include("config/db.php");

if(isset($_SESSION['loggedin']))
{
    $activity = $_SESSION['name']." logged out";

    $sql = "

    INSERT INTO activity_log

    (

    activity,

    activity_by

    )

    VALUES

    (

    ?,

    ?

    )

    ";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(

    "si",

    $activity,

    $_SESSION['ic_no']

    );

    $stmt->execute();

    $stmt->close();
}

session_unset();

session_destroy();

header("Location:index.php");

exit();

?>