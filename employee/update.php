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

w.id,

e.ic_no,
e.name,
e.phone,
e.designation,
e.email,

w.workshop_name,
w.workshop_year,
w.attended_date,
w.attendance_status,
w.remarks,

(

SELECT status

FROM reports r

WHERE r.report_name=CONCAT('Report_',e.ic_no,'_',w.workshop_year)

LIMIT 1

) report_status

FROM employee e

INNER JOIN workshops w

ON e.ic_no=w.ic_no

LEFT JOIN roles ro

ON e.ic_no=ro.ic_no

WHERE ro.role IS NULL

ORDER BY e.ic_no;

";

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Employee</title>

<link rel="stylesheet" href="../css/dashboard.css">
<link rel="stylesheet" href="../css/table.css">
<link rel="stylesheet" href="../css/modal.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<?php include("../includes/sidebar.php"); ?>

<div class="content">

<h2>

<i class="fa-solid fa-user-pen"></i>

Update Employee

</h2>

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

<th>IC</th>

<th>Name</th>

<th>Workshop</th>

<th>Year</th>

<th>Status</th>

<th>Report</th>

<th>Action</th>

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

<td><?= $row['workshop_name']; ?></td>

<td><?= $row['workshop_year']; ?></td>

<td><?= $row['attendance_status']; ?></td>

<td>

<?php

if($row['report_status']=="Verified")
{

echo "<span class='badge-blue'>Verified</span>";

}

elseif($row['report_status']=="Pending")
{

echo "<span class='badge-orange'>Pending</span>";

}

else

{

echo "<span class='badge-grey'>Not Generated</span>";

}

?>

</td>

<td>

<button

class="btn-edit"

onclick="window.location='fetch_employee.php?id=<?= $row['id']; ?>'">

Edit

</button>

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

<script src="../js/search.js"></script>

</body>

</html>