<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Admin")
{
    header("Location:../index.php");
    exit();
}

$sql="

SELECT

r.id,
r.report_name,
r.report_year,
r.generated_date,
r.verified_date,
r.json_path,

e.name,
e.ic_no,

v.name AS verified_by_name

FROM reports r

INNER JOIN employee e

ON r.employee_ic=e.ic_no

LEFT JOIN employee v

ON r.verified_by=v.ic_no

WHERE r.status='Verified'

ORDER BY r.verified_date DESC

";

$result=mysqli_query($conn,$sql);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Verified Report History</title>

<link rel="stylesheet" href="../css/sidebar.css">
<link rel="stylesheet" href="../css/navbar.css">
<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

<script src="../js/search.js"></script>

</head>

<body>

<?php include("../includes/sidebar.php"); ?>
<?php include("../includes/navbar.php"); ?>

<div class="content">

<h2>

<i class="fa-solid fa-clock-rotate-left"></i>

Verified Report History

</h2>

<p>

All reports verified by the Administrator.

</p>

<input

type="text"

id="searchBox"

class="search-box"

placeholder="Search employee, report or year..."

>

<br><br>

<div class="activity-box">

<table

class="activity-table"

id="employeeTable"

>

<tr>

<th>IC No</th>

<th>Employee</th>

<th>Report</th>

<th>Year</th>

<th>Verified By</th>

<th>Date</th>

<th>JSON</th>

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

<?php echo $row['report_name']; ?>

</td>

<td>

<?php echo $row['report_year']; ?>

</td>

<td>

<?php echo $row['verified_by_name']; ?>

</td>

<td>

<?php echo date("d M Y",strtotime($row['verified_date'])); ?>

</td>

<td>

<a

href="../<?php echo $row['json_path']; ?>"

target="_blank"

class="action-btn"

>

<i class="fa-solid fa-file-code"></i>

View JSON

</a>

</td>

</tr>

<?php

}

?>

</table>

</div>

</div>

</body>

</html>

<?php

$conn->close();

?>