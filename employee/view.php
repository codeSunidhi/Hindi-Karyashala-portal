<?php

session_start();

if(!isset($_SESSION['loggedin']))
{
    header("Location:../index.php");
    exit();
}

require_once("../config/db.php");

$query = "

SELECT

e.ic_no,
e.name,
e.designation,
e.email,

MAX(
CASE
WHEN YEAR(w.attended_date)=2025
THEN '✔'
ELSE ''
END
) AS workshop2025,

MAX(
CASE
WHEN YEAR(w.attended_date)=2026
THEN '✔'
ELSE ''
END
) AS workshop2026

FROM employee e

LEFT JOIN workshops w

ON e.ic_no=w.ic_no

GROUP BY

e.ic_no,
e.name,
e.designation,
e.email

ORDER BY e.ic_no

";

$result=mysqli_query($conn,$query);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>View Employees</title>

<link rel="stylesheet" href="../css/style.css">

<script src="../js/validation.js"></script>

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

<a href="view.php">View</a>

<a href="update.php">Update</a>

<a href="report.php">Get Report</a>

<a href="../logout.php">Logout</a>

</div>

<div class="content">

<h2>Employee Workshop Details</h2>

<br>

<input

type="text"

id="search"

class="search-box"

placeholder="Search Employee..."

onkeyup="searchTable();"

>

<br><br>

<table id="employeeTable">

<tr>

<th>IC No</th>

<th>Name</th>

<th>Designation</th>

<th>Email</th>

<th>2025</th>

<th>2026</th>

<th>Action</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td>

<?php echo $row['ic_no']; ?>

</td>

<td>

<?php echo $row['name']; ?>

</td>

<td>

<?php echo $row['designation']; ?>

</td>

<td>

<?php echo $row['email']; ?>

</td>

<td align="center">

<?php echo $row['workshop2025']; ?>

</td>

<td align="center">

<?php echo $row['workshop2026']; ?>

</td>

<td>

<a

href="update.php?ic=<?php echo $row['ic_no']; ?>"

class="btn btn-edit">

Edit

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>