<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role']!="Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location:view.php");
    exit();
}

$id=intval($_GET['id']);

$sql="

SELECT

e.ic_no,
e.name,
e.phone,
e.designation,
e.email,

w.workshop_name,
w.workshop_year,
w.attended_date,
w.attendance_status,
w.remarks

FROM employee e

INNER JOIN workshops w
ON e.ic_no=w.ic_no

WHERE w.id=?

";

$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0)
{
    die("Employee not found.");
}

$row=$result->fetch_assoc();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Employee Details</title>

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

<i class="fa-solid fa-id-card"></i>

Employee Details

</h2>

<p>

Complete workshop information of the selected employee.

</p>

</div>

</div>

<div class="dashboard-middle">

<!-- Employee Card -->

<div class="activity-box">

<h3>

<i class="fa-solid fa-user"></i>

Employee Information

</h3>

<table class="activity-table">

<tr>
<td><strong>IC Number</strong></td>
<td><?php echo $row['ic_no']; ?></td>
</tr>

<tr>
<td><strong>Name</strong></td>
<td><?php echo $row['name']; ?></td>
</tr>

<tr>
<td><strong>Designation</strong></td>
<td><?php echo $row['designation']; ?></td>
</tr>

<tr>
<td><strong>Phone</strong></td>
<td><?php echo $row['phone']; ?></td>
</tr>

<tr>
<td><strong>Email</strong></td>
<td><?php echo $row['email']; ?></td>
</tr>

</table>

</div>

<!-- Workshop Card -->

<div class="activity-box">

<h3>

<i class="fa-solid fa-book-open"></i>

Workshop Information

</h3>

<table class="activity-table">

<tr>
<td><strong>Workshop</strong></td>
<td><?php echo $row['workshop_name']; ?></td>
</tr>

<tr>
<td><strong>Year</strong></td>
<td><?php echo $row['workshop_year']; ?></td>
</tr>

<tr>
<td><strong>Attended Date</strong></td>
<td>

<?php

if(empty($row['attended_date']))
{
    echo "<span class='status-orange'>Not Updated</span>";
}
else
{
    echo $row['attended_date'];
}

?>

</td>
</tr>

<tr>
<td><strong>Status</strong></td>
<td>

<?php

if($row['attendance_status']=="Attended")
{
    echo "<span class='status-green'><i class='fa-solid fa-circle-check'></i> Attended</span>";
}
elseif($row['attendance_status']=="Pending")
{
    echo "<span class='status-orange'><i class='fa-solid fa-clock'></i> Pending</span>";
}
else
{
    echo "<span class='status-red'><i class='fa-solid fa-circle-xmark'></i> Absent</span>";
}

?>

</td>
</tr>

<tr>
<td><strong>Remarks</strong></td>
<td>

<?php

if(trim($row['remarks'])=="")
{
    echo "-";
}
else
{
    echo nl2br(htmlspecialchars($row['remarks']));
}

?>

</td>
</tr>

</table>

</div>

</div>

<br>

<a href="view.php" class="action-btn">

<i class="fa-solid fa-arrow-left"></i>

Back to Employees

</a>

</div>

</body>

</html>

<?php

$stmt->close();
$conn->close();

?>