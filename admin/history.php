<?php
include "../includes/auth.php";
include "../config/db.php";

$historySql="SELECT
reports.report_id,
reports.employee_ic,
employees.name,
reports.report_name,
reports.workshop_year,
reports.generated_date,
reports.verified_date,
v.name AS verified_by
FROM reports
INNER JOIN employees
ON reports.employee_ic=employees.ic_number
LEFT JOIN employees v
ON reports.verified_by=v.ic_number
WHERE reports.status='Verified'
ORDER BY reports.verified_date DESC";

$historyResult=$conn->query($historySql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Verification History</title>

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

<h1>Verification History</h1>

<p>View all verified workshop reports.</p>

</div>

<div class="table-card">

<div class="table-top">

<input
type="text"
id="search"
placeholder="Search by IC, Employee or Year">

</div>

<table id="employeeTable">

<thead>

<tr>

<th>Report ID</th>

<th>IC Number</th>

<th>Employee</th>

<th>Report</th>

<th>Workshop Year</th>

<th>Generated Date</th>

<th>Verified By</th>

<th>Verified Date</th>

</tr>

</thead>

<tbody>

<?php while($history=$historyResult->fetch_assoc()){ ?>

<tr>

<td><?php echo htmlspecialchars($history["report_id"]); ?></td>

<td><?php echo htmlspecialchars($history["employee_ic"]); ?></td>

<td><?php echo htmlspecialchars($history["name"]); ?></td>

<td><?php echo htmlspecialchars($history["report_name"]); ?></td>

<td><?php echo htmlspecialchars($history["workshop_year"]); ?></td>

<td><?php echo date("d-m-Y",strtotime($history["generated_date"])); ?></td>

<td><?php echo htmlspecialchars($history["verified_by"]); ?></td>

<td>
<?php
if($history["verified_date"]!=""){
echo date("d-m-Y",strtotime($history["verified_date"]));
}else{
echo "-";
}
?>
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