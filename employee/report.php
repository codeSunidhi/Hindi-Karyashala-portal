<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

/* ==========================
   FILTER
========================== */

$year = "";

if(isset($_GET['year']))
{
    $year = $_GET['year'];
}

$sql = "

SELECT

w.id,

e.ic_no,

e.name,

e.designation,

e.email,

w.workshop_year,

w.attended_date,

w.attendance_status,

w.remarks

FROM employee e

INNER JOIN workshops w

ON e.ic_no = w.ic_no

LEFT JOIN roles r

ON e.ic_no = r.ic_no

WHERE r.role IS NULL

";

if($year!="")
{
    $sql .= " AND w.workshop_year='$year'";
}

$sql .= " ORDER BY e.ic_no";

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Generate Report</title>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/table.css">

<link rel="stylesheet"

href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<?php include("../includes/sidebar.php"); ?>

<div class="content">

<h2>

<i class="fa-solid fa-file-lines"></i>

Workshop Report

</h2>

<br>

<form method="GET">

<select name="year">

<option value="">All Years</option>

<option value="2025">2025</option>

<option value="2026">2026</option>

</select>

<button class="btn-save">

Filter

</button>

</form>

<br>

<input

type="text"

id="searchBox"

class="search-box"

placeholder="Search Employee">

<br><br>

<table id="employeeTable">

<thead>

<tr>

<th>IC No.</th>

<th>Name</th>

<th>Designation</th>

<th>Email</th>

<th>Workshop</th>

<th>Date</th>

<th>Status</th>

<th>Remarks</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?= $row['ic_no']; ?></td>

<td><?= $row['name']; ?></td>

<td><?= $row['designation']; ?></td>

<td><?= $row['email']; ?></td>

<td><?= $row['workshop_year']; ?></td>

<td><?= $row['attended_date']; ?></td>

<td><?= $row['attendance_status']; ?></td>

<td><?= $row['remarks']; ?></td>

</tr>

<?php

}

?>

</tbody>

</table>

<br>

<form action="generate_json.php" method="POST">

<input

type="hidden"

name="year"

value="<?php echo $year; ?>">

<button class="btn-report">

<i class="fa-solid fa-file-export"></i>

Generate JSON Report

</button>

</form>

</div>

<script src="../js/search.js"></script>

</body>

</html>