<?php
session_start();

if(!isset($_SESSION['loggedin']))
{
    header("Location:../index.php");
    exit();
}

if($_SESSION['role']!="admin")
{
    header("Location:../index.php");
    exit();
}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Admin Dashboard</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="navbar">

    <h2>हिंदी कार्यशाला पोर्टल</h2>

    <div>

        Welcome,
        <b><?php echo $_SESSION['name']; ?></b>

    </div>

</div>

<div class="sidebar">

    <a href="dashboard.php">Dashboard</a>

    <a href="view.php">View Employees</a>

    <a href="update.php">Update Attendance</a>

    <a href="report.php">Generate Report</a>

    <a href="approve.php">Approve Reports</a>

    <a href="history.php">Report History</a>

    <a href="../logout.php">Logout</a>

</div>

<div class="content">

<h2>Admin Dashboard</h2>

<hr><br>

<div style="display:flex;gap:25px;flex-wrap:wrap;">

<div style="background:#fff;padding:20px;width:220px;border-radius:8px;box-shadow:0 0 10px #ccc;">

<h3>Employees</h3>

<?php

require_once("../config/db.php");

$q=mysqli_query($conn,"SELECT COUNT(*) total FROM employee");

$r=mysqli_fetch_assoc($q);

echo "<h1>".$r['total']."</h1>";

?>

</div>

<div style="background:#fff;padding:20px;width:220px;border-radius:8px;box-shadow:0 0 10px #ccc;">

<h3>Workshops</h3>

<?php

$q=mysqli_query($conn,"SELECT COUNT(*) total FROM workshops");

$r=mysqli_fetch_assoc($q);

echo "<h1>".$r['total']."</h1>";

?>

</div>

<div style="background:#fff;padding:20px;width:220px;border-radius:8px;box-shadow:0 0 10px #ccc;">

<h3>Reports</h3>

<?php

$q=mysqli_query($conn,"SELECT COUNT(*) total FROM reports");

$r=mysqli_fetch_assoc($q);

echo "<h1>".$r['total']."</h1>";

?>

</div>

</div>

<br><br>

<h3>Quick Actions</h3>

<br>

<a href="view.php" class="btn btn-edit">View Employees</a>

<a href="update.php" class="btn btn-save">Update Attendance</a>

<a href="report.php" class="btn btn-report">Generate Report</a>

<a href="approve.php" class="btn btn-delete">Approve Reports</a>

</div>

</body>

</html>