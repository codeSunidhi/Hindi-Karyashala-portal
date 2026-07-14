<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

$sql="

SELECT

e.ic_no,
e.name,
e.designation,

w.id,
w.workshop_name,
w.workshop_year,
w.attendance_status

FROM employee e

INNER JOIN workshops w
ON e.ic_no=w.ic_no

LEFT JOIN roles r
ON e.ic_no=r.ic_no

WHERE r.role IS NULL

ORDER BY e.ic_no

";

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Employees</title>

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

<div class="glass-header">

<div>

<h2>

<i class="fa-solid fa-user-pen"></i>

Update Workshop Attendance

</h2>

<p>

Select an employee to update workshop attendance.

</p>

</div>

</div>

<div class="glass-search">

<i class="fa-solid fa-magnifying-glass"></i>

<input

type="text"

id="searchBox"

placeholder="Search IC No, Employee Name..."

>

</div>

<div class="glass-table">

<table id="employeeTable">

<thead>

<tr>

<th>IC No</th>

<th>Employee</th>

<th>Workshop</th>

<th>Year</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php

while($row=mysqli_fetch_assoc($result))

{

?>

<tr>

<td>

<b><?php echo $row['ic_no']; ?></b>

</td>

<td>

<div class="employee-info">

<i class="fa-solid fa-user-circle"></i>

<div>

<strong>

<?php echo $row['name']; ?>

</strong>

<br>

<small>

<?php echo $row['designation']; ?>

</small>

</div>

</div>

</td>

<td>

<?php echo $row['workshop_name']; ?>

</td>

<td>

<?php echo $row['workshop_year']; ?>

</td>

<td>

<?php

if($row['attendance_status']=="Attended")
{
?>

<span class="status-green">

<i class="fa-solid fa-circle-check"></i>

Attended

</span>

<?php
}
elseif($row['attendance_status']=="Pending")
{
?>

<span class="status-orange">

<i class="fa-solid fa-clock"></i>

Pending

</span>

<?php
}
else
{
?>

<span class="status-red">

<i class="fa-solid fa-circle-xmark"></i>

Absent

</span>

<?php
}

?>

</td>

<td>

<a

href="fetch_employee.php?id=<?php echo $row['id']; ?>"

class="action-btn"

>

<i class="fa-solid fa-pen-to-square"></i>

Update

</a>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>

<script src="../js/search.js"></script>

</body>

</html>

<?php

$conn->close();

?>