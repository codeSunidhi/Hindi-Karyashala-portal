<?php
include "../includes/auth.php";
include "../config/db.php";

$employeeSql="SELECT
employees.ic_number,
employees.name,
employees.phone,
employees.designation,
employees.email,
workshops.workshop_name,
workshops.workshop_year,
workshops.attendance_date,
workshops.attendance_status
FROM employees
LEFT JOIN workshops
ON employees.ic_number=workshops.employee_ic
WHERE employees.ic_number NOT IN(1001,1002)
ORDER BY employees.ic_number";

$employeeResult=$conn->query($employeeSql);
?>

<!DOCTYPE html>
<html>
<head>

<title>View Employees</title>

<link rel="stylesheet" href="../css/layout.css">
<link rel="stylesheet" href="../css/table.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>
<body>

<div class="overlay">

<?php
include "../includes/navbar.php";
include "../includes/sidebar.php";
?>

<div class="main">

<div class="page-header">
<h1>Employees</h1>
<p>View and manage employee workshop records.</p>
</div>

<div class="table-card">

<?php
if(isset($_SESSION["message"])){
?>
<p class="success-message">
<?php
echo $_SESSION["message"];
unset($_SESSION["message"]);
?>
</p>
<?php
}
?>

<div class="table-top">

<input
type="text"
id="search"
placeholder="Search by IC, Name, Designation or Year">

</div>

<table id="employeeTable">

<thead>

<tr>

<th>IC</th>

<th>Name</th>

<th>Phone</th>

<th>Designation</th>

<th>Workshop</th>

<th>Year</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($employee=$employeeResult->fetch_assoc()){ ?>

<tr>

<td><?php echo htmlspecialchars($employee["ic_number"]); ?></td>
<td><?php echo htmlspecialchars($employee["name"]); ?></td>
<td><?php echo htmlspecialchars($employee["phone"]); ?></td>
<td><?php echo htmlspecialchars($employee["designation"]); ?></td>
<td><?php echo htmlspecialchars($employee["workshop_name"]); ?></td>
<td><?php echo htmlspecialchars($employee["workshop_year"]); ?></td>

<td>

<span class="badge <?php echo strtolower($employee["attendance_status"]); ?>">
<?php echo htmlspecialchars($employee["attendance_status"]); ?>
</span>

</td>

<td><?php echo htmlspecialchars($employee["attendance_date"]); ?></td>

<td>
<a href="update.php?ic=<?php echo $employee["ic_number"]; ?>" class="edit-btn">
    <i class="fa-solid fa-pen"></i>
</a>
</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

</div>

<script src="../js/search.js"></script>

</body>
</html>