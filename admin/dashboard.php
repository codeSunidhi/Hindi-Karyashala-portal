<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role']!="Admin"
)
{
    header("Location:../index.php");
    exit();
}

/* ===========================
   REPORT COUNTS
=========================== */

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reports
WHERE status='Pending'
"));

$verified = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM reports
WHERE status='Verified'
"));

$employees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM employee
"));

/* ===========================
   ATTENDANCE COUNTS
=========================== */

$attended = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Attended'
"));

$pendingAttendance = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Pending'
"));

$absent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Absent'
"));

/* ===========================
   RECENT ACTIVITY
=========================== */

$activities = mysqli_query($conn,"
SELECT activity,activity_date
FROM activity_log
ORDER BY activity_date DESC
LIMIT 5
");

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Administrator Dashboard</title>

<link rel="stylesheet" href="../css/sidebar.css">
<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<?php include("../includes/sidebar.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="content">

<div class="glass-header">

<div>

<h2>

<i class="fa-solid fa-user-shield"></i>

Administrator Dashboard

</h2>

<p>

Welcome,

<b><?php echo $_SESSION['name']; ?></b>

</p>

</div>

</div>

<!-- DASHBOARD CARDS -->

<div class="card-container">

<div class="dashboard-card">

<i class="fa-solid fa-users"></i>

<h3><?php echo $employees['total']; ?></h3>

<p>Total Employees</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-file-circle-check"></i>

<h3><?php echo $verified['total']; ?></h3>

<p>Verified Reports</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-file-circle-exclamation"></i>

<h3><?php echo $pending['total']; ?></h3>

<p>Pending Reports</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-book"></i>

<h3><?php echo $verified['total']+$pending['total']; ?></h3>

<p>Total Reports</p>

</div>

</div>

<!-- QUICK ACTIONS -->

<div class="quick-actions">

<a href="../employee/view.php" class="action-btn">

<i class="fa-solid fa-users"></i>

View Employees

</a>

<a href="../employee/update.php" class="action-btn">

<i class="fa-solid fa-user-pen"></i>

Update Employees

</a>

<a href="add_employee.php" class="action-btn">

<i class="fa-solid fa-user-plus"></i>

Add Employee

</a>

<a href="reports.php" class="action-btn">

<i class="fa-solid fa-file-lines"></i>

Pending Reports

</a>

<a href="history.php" class="action-btn">

<i class="fa-solid fa-clock-rotate-left"></i>

History

</a>

</div>

<br>

<div class="dashboard-middle">

<div class="activity-box">

<h3>

<i class="fa-solid fa-clock-rotate-left"></i>

Recent Activities

</h3>

<table class="activity-table">

<tr>

<th>Activity</th>

<th>Date</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($activities))
{

?>

<tr>

<td><?php echo htmlspecialchars($row['activity']); ?></td>

<td><?php echo date("d M Y H:i",strtotime($row['activity_date'])); ?></td>

</tr>

<?php

}

?>

</table>

</div>

<div class="chart-box">

<h3>Workshop Attendance</h3>

<canvas id="attendanceChart"></canvas>

</div>

</div>

<br>

<div class="dashboard-middle">

<div class="chart-box">

<h3>Report Status</h3>

<canvas id="reportChart"></canvas>

</div>

<div class="chart-box">

<h3>Workshop Attendance Count</h3>

<canvas id="barChart"></canvas>

</div>

</div>

<script>

new Chart(

document.getElementById("reportChart"),

{

type:"doughnut",

data:{

labels:[

"Verified",

"Pending"

],

datasets:[{

data:[

<?php echo $verified['total']; ?>,

<?php echo $pending['total']; ?>

],

backgroundColor:[

"#22c55e",

"#f59e0b"

],

borderWidth:0

}]

},

options:{

responsive:true,

plugins:{

legend:{

position:"bottom",

labels:{

color:"white"

}

}

}

}

}

);

</script>

</body>

</html>