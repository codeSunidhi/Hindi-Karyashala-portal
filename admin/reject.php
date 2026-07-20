<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Admin")
{
    header("Location:../index.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location:reports.php");
    exit();
}

$id=intval($_GET['id']);

$sql="

UPDATE reports

SET

status='Rejected'

WHERE id=?

";

$stmt=$conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$stmt->close();

/* Activity */

$activity=$_SESSION['name']." rejected a report";

$stmt=$conn->prepare("

INSERT INTO activity_log(activity,activity_by)

VALUES(?,?)

");

$stmt->bind_param(

"si",

$activity,

$_SESSION['ic_no']

);

$stmt->execute();

$stmt->close();

header("Location:reports.php");

exit();

?>