<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != "Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("Location:update.php");
    exit();
}

/* ==========================
   GET FORM DATA
========================== */

$id = intval($_POST['id']);

$attended_date = empty($_POST['attended_date']) ? NULL : $_POST['attended_date'];

$attendance_status = $_POST['attendance_status'];

$remarks = trim($_POST['remarks']);

$updated_by = $_SESSION['ic_no'];

/* ==========================
   VALIDATE DATE
========================== */

if (!empty($attended_date) && $attended_date > date("Y-m-d"))
{
    die("Attendance date cannot be greater than today.");
}

/* ==========================
   UPDATE WORKSHOP
========================== */

$sql = "

UPDATE workshops

SET

attended_date=?,

attendance_status=?,

remarks=?,

updated_by=?

WHERE id=?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"sssii",

$attended_date,

$attendance_status,

$remarks,

$updated_by,

$id

);

if(!$stmt->execute())
{
    die("Update Failed : ".$stmt->error);
}

$stmt->close();

/* ==========================
   ACTIVITY LOG
========================== */

$activity = "Workshop attendance updated";

$sql = "

INSERT INTO activity_log

(activity,activity_by)

VALUES

(?,?)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("si",$activity,$updated_by);

$stmt->execute();

$stmt->close();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Successful</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<?php include("../includes/sidebar.php"); ?>

<div class="content">

<div class="success-card">

<i class="fa-solid fa-circle-check success-icon"></i>

<h2>

Workshop Updated Successfully

</h2>

<p>

Attendance details have been updated successfully.

</p>

<br>

<a

href="generate_json.php?id=<?php echo $id; ?>"

class="btn-report">

Generate JSON Report

</a>

<br><br>

<a

href="update.php"

class="btn-back">

Back to Employee List

</a>

</div>

</div>

</body>

</html>

<?php

$conn->close();

?>