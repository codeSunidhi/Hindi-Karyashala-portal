<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != "karyashala") {
    header("Location:../index.php");
    exit();
}

require_once("../config/db.php");

if(isset($_POST['generate']))
{

$query="

SELECT

e.ic_no,
e.name,
e.designation,
e.email,

MAX(CASE WHEN YEAR(w.attended_date)=2025 THEN attended_date END) AS workshop_2025,

MAX(CASE WHEN YEAR(w.attended_date)=2026 THEN attended_date END) AS workshop_2026

FROM employee e

LEFT JOIN workshops w

ON e.ic_no=w.ic_no

GROUP BY
e.ic_no,e.name,e.designation,e.email

ORDER BY e.ic_no

";

$result=mysqli_query($conn,$query);

$data=array();

while($row=mysqli_fetch_assoc($result))
{
    $data[]=$row;
}

if(!is_dir("../reports"))
{
    mkdir("../reports");
}

$filename="report_".date("Ymd_His").".json";

file_put_contents(
"../reports/".$filename,
json_encode($data,JSON_PRETTY_PRINT)
);

mysqli_query($conn,"
INSERT INTO reports
(report_name,generated_by,approved)
VALUES
('$filename','".$_SESSION['ic_no']."','No')
");

$msg="Report Generated Successfully.";

}
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Generate Report</title>

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

<a href="view.php">View</a>

<a href="update.php">Update</a>

<a href="report.php">Get Report</a>

<a href="../logout.php">Logout</a>

</div>

<div class="content">

<h2>Generate Workshop Report</h2>

<hr><br>

<form method="POST">

<button
type="submit"
name="generate"
class="btn btn-report">

Generate JSON Report

</button>

</form>

<br>

<?php

if(isset($msg))
{

echo "<h3 style='color:green;'>$msg</h3>";

}

?>

<br>

<h3>Generated Reports</h3>

<br>

<table>

<tr>

<th>Report Name</th>

<th>Status</th>

</tr>

<?php

$q=mysqli_query($conn,"
SELECT *
FROM reports
ORDER BY id DESC
");

while($r=mysqli_fetch_assoc($q))
{

?>

<tr>

<td>

<a
href="../reports/<?php echo $r['report_name']; ?>"
target="_blank">

<?php echo $r['report_name']; ?>

</a>

</td>

<td>

<?php echo $r['approved']; ?>

</td>

</tr>

<?php

}

?>

</table>

</div>

</body>

</html>