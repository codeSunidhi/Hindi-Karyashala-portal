<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

/* ===============================
   DASHBOARD COUNTS
=============================== */

$totalEmployees = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM employee e
LEFT JOIN roles r ON e.ic_no=r.ic_no
WHERE r.ic_no IS NULL
"));

$attended = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM workshops
WHERE attendance_status='Attended'
"));

$pending = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM workshops
WHERE attendance_status='Pending'
"));

$absent = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT COUNT(*) AS total
FROM workshops
WHERE attendance_status='Absent'
"));

$activities = mysqli_query($conn,"
SELECT activity,activity_date
FROM activity_log
ORDER BY activity_date DESC
LIMIT 5
");

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Karyashala Dashboard</title>

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

<h2>
<i class="fa-solid fa-gauge-high"></i>
Dashboard
</h2>

<p>
Welcome back,
<b><?php echo $_SESSION['name']; ?></b>
</p>

<!-- CARDS -->

<div class="card-container">

<div class="dashboard-card">

<i class="fa-solid fa-users"></i>

<h3><?php echo $totalEmployees['total']; ?></h3>

<p>Total Employees</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-user-check"></i>

<h3><?php echo $attended['total']; ?></h3>

<p>Attended</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-user-clock"></i>

<h3><?php echo $pending['total']; ?></h3>

<p>Pending</p>

</div>

<div class="dashboard-card">

<i class="fa-solid fa-user-xmark"></i>

<h3><?php echo $absent['total']; ?></h3>

<p>Absent</p>

</div>

</div>

<!-- QUICK ACTION -->

<div class="quick-actions">

<a href="view.php" class="action-btn">

<i class="fa-solid fa-users"></i>

View Employees

</a>

<a href="update.php" class="action-btn">

<i class="fa-solid fa-user-pen"></i>

Update Attendance

</a>

</div>

<br>

<!-- MAIN SECTION -->

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

<td><?php echo $row['activity']; ?></td>

<td><?php echo date("d M Y H:i",strtotime($row['activity_date'])); ?></td>

</tr>

<?php

}

?>

</table>

</div>

<div class="chart-box">

<h3>

Attendance

</h3>

<canvas id="attendanceChart"></canvas>

</div>

</div>

</div>

<script>

new Chart(

document.getElementById("attendanceChart"),

{

type:"doughnut",

data:{

labels:[

"Attended",

"Pending",

"Absent"

],

datasets:[{

data:[

<?php echo $attended['total']; ?>,

<?php echo $pending['total']; ?>,

<?php echo $absent['total']; ?>

],

backgroundColor:[

"#22c55e",

"#fbbf24",

"#ef4444"

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

color:"white",

font:{

size:13

}

}

}

}

}

}

);

</script>

</body>

</html>