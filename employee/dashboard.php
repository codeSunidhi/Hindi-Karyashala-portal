<?php
session_start();

if(!isset($_SESSION['loggedin']))
{
    header("Location:../index.php");
    exit();
}

if($_SESSION['role']!="karyashala")
{
    header("Location:../index.php");
    exit();
}

require_once("../config/db.php");
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Karyashala Dashboard</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<!-- ================= NAVBAR ================= -->

<div class="navbar">

    <h2>हिंदी कार्यशाला पोर्टल</h2>

    <div>

        Welcome,

        <b><?php echo $_SESSION['name']; ?></b>

    </div>

</div>

<!-- ================= SIDEBAR ================= -->

<div class="sidebar">

    <a href="dashboard.php"> Dashboard</a>

    <a href="view.php"> View Employees</a>

    <a href="update.php"> Update Attendance</a>

    <a href="report.php"> Get Report</a>

    <a href="../logout.php"> Logout</a>

</div>

<!-- ================= CONTENT ================= -->

<div class="content">

<h2>Karyashala Admin Dashboard</h2>

<hr><br>

<div style="display:flex;gap:20px;flex-wrap:wrap;">

<!-- Total Employees -->

<div style="background:white;
padding:20px;
width:220px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;">

<h3>Total Employees</h3>

<?php

$q=mysqli_query($conn,"SELECT COUNT(*) total FROM employee");

$r=mysqli_fetch_assoc($q);

?>

<h1><?php echo $r['total']; ?></h1>

</div>

<!-- Workshop Records -->

<div style="background:white;
padding:20px;
width:220px;
border-radius:8px;
box-shadow:0px 0px 10px #ccc;">

<h3>Workshop Records</h3>

<?php

$q=mysqli_query($conn,"SELECT COUNT(*) total FROM workshops");

$r=mysqli_fetch_assoc($q);

?>

<h1><?php echo $r['total']; ?></h1>

</div>

</div>

<br><br>

<h3>Quick Menu</h3>

<br>

<a href="view.php" class="btn btn-edit">

View Employees

</a>

<a href="update.php" class="btn btn-save">

Update Attendance

</a>

<a href="report.php" class="btn btn-report">

Generate Report

</a>

</div>

</body>

</html>