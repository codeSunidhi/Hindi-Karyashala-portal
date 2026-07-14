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

$id = intval($_GET['id']);

/* ===========================================
   GET REPORT DETAILS
=========================================== */

$sql="

SELECT

r.id,
r.report_name,
r.employee_ic,
e.name

FROM reports r

INNER JOIN employee e

ON r.employee_ic=e.ic_no

WHERE r.id=?

";

$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0)
{
    die("Report not found.");
}

$row=$result->fetch_assoc();

$stmt->close();

/* ===========================================
   VERIFY REPORT
=========================================== */

$sql="

UPDATE reports

SET

status='Verified',

verified_by=?,

verified_date=NOW()

WHERE id=?

";

$stmt=$conn->prepare($sql);

$stmt->bind_param(

"ii",

$_SESSION['ic_no'],

$id

);

$stmt->execute();

$stmt->close();

/* ===========================================
   ACTIVITY LOG
=========================================== */

$activity="Admin verified report of ".$row['name'];

$stmt=$conn->prepare(

"

INSERT INTO activity_log

(activity,activity_by)

VALUES

(?,?)

"

);

$stmt->bind_param(

"si",

$activity,

$_SESSION['ic_no']

);

$stmt->execute();

$stmt->close();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Report Verified</title>

<link rel="stylesheet" href="../css/sidebar.css">
<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include("../includes/sidebar.php"); ?>

<?php include("../includes/navbar.php"); ?>

<div class="content">

<div class="activity-box" style="text-align:center; max-width:700px; margin:auto;">

<i class="fa-solid fa-circle-check"
style="font-size:80px;color:#22c55e;margin-bottom:20px;"></i>

<h2 style="color:white;">

Report Verified Successfully

</h2>

<br>

<p style="font-size:18px;color:white;">

Employee

<br><br>

<b>

<?php echo $row['name']; ?>

</b>

</p>

<br>

<p style="color:#e5e7eb;">

Report :

<b>

<?php echo $row['report_name']; ?>

</b>

</p>

<br><br>

<a

href="reports.php"

class="action-btn"

>

Back to Pending Reports

</a>

</div>

</div>

</body>

</html>

<?php

$conn->close();

?>