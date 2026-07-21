<?php
include "../includes/auth.php";
include "../config/db.php";

if(!isset($_GET["id"])){
exit();
}

$id=(int)$_GET["id"];

$stmt=$conn->prepare("SELECT
r.report_id,
r.report_name,
r.employee_ic,
r.workshop_year,
r.generated_date,
e.name,
e.phone,
e.designation,
e.email,
w.workshop_name,
w.attendance_date,
w.attendance_status,
w.remarks
FROM reports r
INNER JOIN employees e
ON r.employee_ic=e.ic_number
INNER JOIN workshops w
ON r.employee_ic=w.employee_ic
AND r.workshop_year=w.workshop_year
WHERE r.report_id=?");

$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){
echo "<h3>Report not found.</h3>";
exit();
}

$row=$result->fetch_assoc();
?>

<div class="modal-header">

<h2>
<i class="fa-solid fa-file-circle-check"></i>
Workshop Report Details
</h2>

<p>Please review the employee workshop details before verification.</p>

</div>

<table class="modal-table">

<tr>
<td><strong>Employee IC</strong></td>
<td><?php echo htmlspecialchars($row["employee_ic"]); ?></td>
</tr>

<tr>
<td><strong>Name</strong></td>
<td><?php echo htmlspecialchars($row["name"]); ?></td>
</tr>

<tr>
<td><strong>Phone</strong></td>
<td><?php echo htmlspecialchars($row["phone"]); ?></td>
</tr>

<tr>
<td><strong>Designation</strong></td>
<td><?php echo htmlspecialchars($row["designation"]); ?></td>
</tr>

<tr>
<td><strong>Email</strong></td>
<td><?php echo htmlspecialchars($row["email"]); ?></td>
</tr>

<tr>
<td><strong>Workshop Name</strong></td>
<td><?php echo htmlspecialchars($row["workshop_name"]); ?></td>
</tr>

<tr>
<td><strong>Workshop Year</strong></td>
<td><?php echo htmlspecialchars($row["workshop_year"]); ?></td>
</tr>

<tr>
<td><strong>Attendance Date</strong></td>
<td><?php echo htmlspecialchars($row["attendance_date"]); ?></td>
</tr>

<tr>
<td><strong>Attendance Status</strong></td>
<td><?php echo htmlspecialchars($row["attendance_status"]); ?></td>
</tr>

<tr>
<td><strong>Remarks</strong></td>
<td><?php echo htmlspecialchars($row["remarks"]); ?></td>
</tr>

<tr>
<td><strong>Generated Date</strong></td>
<td><?php echo date("d-m-Y",strtotime($row["generated_date"])); ?></td>
</tr>

</table>

<div class="modal-buttons">

<a
href="verify.php?id=<?php echo $row["report_id"]; ?>"
class="save-btn">

<i class="fa-solid fa-check"></i>
Verify Report

</a>

<button
type="button"
class="cancel-btn"
onclick="document.getElementById('reportModal').style.display='none'">

Close

</button>

</div>