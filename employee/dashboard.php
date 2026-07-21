<?php
include "../includes/auth.php";
include "../config/db.php";

/* Total Employees */
$totalEmployees = $conn->query("
SELECT COUNT(*) total
FROM employees
WHERE ic_number NOT IN (1001,1002)
")->fetch_assoc()['total'];

/* Attendance Counts */
$attended = $conn->query("
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Attended'
")->fetch_assoc()['total'];

$pending = $conn->query("
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Pending'
")->fetch_assoc()['total'];

$absent = $conn->query("
SELECT COUNT(*) total
FROM workshops
WHERE attendance_status='Absent'
")->fetch_assoc()['total'];

/* Recent Activities */
$activity = $conn->query("
SELECT
activity,
activity_date
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

<link rel="stylesheet" href="../css/layout.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>

<body>

<div class="overlay">

<?php include "../includes/navbar.php"; ?>

<?php include "../includes/employee_sidebar.php"; ?>

<div class="main">

<div class="page-header">

<h1>Karyashala Dashboard</h1>

<p>Manage workshop attendance and reports.</p>

</div>

<div class="cards">

<div class="card employees">

<div class="card-icon">

<i class="fa-solid fa-users"></i>

</div>

<h3>Total Employees</h3>

<h2><?php echo $totalEmployees; ?></h2>

<p>Registered Employees</p>

</div>

<div class="card attended">

<div class="card-icon">

<i class="fa-solid fa-user-check"></i>

</div>

<h3>Attended</h3>

<h2><?php echo $attended; ?></h2>

<p>Workshop Completed</p>

</div>

<div class="card pending">

<div class="card-icon">

<i class="fa-solid fa-clock"></i>

</div>

<h3>Pending</h3>

<h2><?php echo $pending; ?></h2>

<p>Attendance Pending</p>

</div>

<div class="card absent">

<div class="card-icon">

<i class="fa-solid fa-user-xmark"></i>

</div>

<h3>Absent</h3>

<h2><?php echo $absent; ?></h2>

<p>Employees Absent</p>

</div>

</div>

<div class="chart-grid">

<div class="section">

<h2>Attendance Overview</h2>

<canvas id="pieChart"></canvas>

</div>

<div class="section">

<h2>Attendance Statistics</h2>

<canvas id="barChart"></canvas>

</div>

</div>

<div class="section">

<h2>Recent Activities</h2>

<?php

if($activity->num_rows>0){

while($row=$activity->fetch_assoc()){

?>

<div class="activity-item">

<div class="activity-dot"></div>

<div>

<h4><?php echo htmlspecialchars($row["activity"]); ?></h4>

<p><?php echo date("d-m-Y h:i A",strtotime($row["activity_date"])); ?></p>

</div>

</div>

<?php

}

}

else{

echo "<p class='empty-text'>No recent activity found.</p>";

}

?>

</div>

</div>

</div>

<script>

new Chart(document.getElementById("pieChart"),{

type:"pie",

data:{

labels:["Attended","Pending","Absent"],

datasets:[{

data:[

<?php echo $attended; ?>,

<?php echo $pending; ?>,

<?php echo $absent; ?>

],

backgroundColor:[

"#22C55E",

"#F59E0B",

"#EF4444"

]

}]

},

options:{

responsive:true,

maintainAspectRatio:false

}

});

new Chart(document.getElementById("barChart"),{

type:"bar",

data:{

labels:["Attended","Pending","Absent"],

datasets:[{

label:"Employees",

data:[

<?php echo $attended; ?>,

<?php echo $pending; ?>,

<?php echo $absent; ?>

],

backgroundColor:[

"#22C55E",

"#F59E0B",

"#EF4444"

]

}]

},

options:{

responsive:true,

maintainAspectRatio:false,

plugins:{

legend:{

display:false

}

}

}

});

</script>

</body>

</html>