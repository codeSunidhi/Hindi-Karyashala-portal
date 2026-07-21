<?php
include "../includes/auth.php";
include "../config/db.php";

$reportSql="SELECT
reports.report_id,
reports.report_name,
reports.employee_ic,
employees.name,
employees.designation,
reports.workshop_year,
reports.generated_date,
reports.status
FROM reports
INNER JOIN employees
ON reports.employee_ic=employees.ic_number
WHERE reports.status='Pending'
ORDER BY reports.generated_date DESC";

$reportResult=$conn->query($reportSql);
?>

<!DOCTYPE html>
<html>
<head>

<title>Pending Reports</title>

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
<h1>Pending Reports</h1>
<p>Reports awaiting verification.</p>
</div>

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

<div class="table-card">

<div class="table-top">

<input
type="text"
id="search"
placeholder="Search by IC, Name or Year">

</div>

<table id="employeeTable">

<thead>

<tr>

<th>Report ID</th>

<th>Employee IC</th>

<th>Employee Name</th>

<th>Designation</th>

<th>Workshop Year</th>

<th>Generated Date</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>

<tbody>

<?php while($report=$reportResult->fetch_assoc()){ ?>

<tr>

<td><?php echo htmlspecialchars($report["report_id"]); ?></td>

<td><?php echo htmlspecialchars($report["employee_ic"]); ?></td>

<td><?php echo htmlspecialchars($report["name"]); ?></td>

<td><?php echo htmlspecialchars($report["designation"]); ?></td>

<td><?php echo htmlspecialchars($report["workshop_year"]); ?></td>

<td><?php echo date("d-m-Y",strtotime($report["generated_date"])); ?></td>

<td>

<span class="badge pending">

<?php echo htmlspecialchars($report["status"]); ?>

</span>

</td>

<td>

<button
type="button"
class="report-btn viewBtn"
data-id="<?php echo $report["report_id"]; ?>">

<i class="fa-solid fa-eye"></i>

View

</button>

</td>

</tr>

<?php } ?>

</tbody>

</table>

</div>

</div>

<div id="reportModal" class="modal">

<div class="modal-content">

<span class="close">&times;</span>

<div id="reportContent"></div>

</div>

</div>

</div>

<script src="../js/search.js"></script>
<script src="../js/modal.js"></script>

</body>
</html>