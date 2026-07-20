<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if(
    !isset($_SESSION['role']) ||
    $_SESSION['role']!="Admin"
)
{
    header("Location:../index.php");
    exit();
}

if(!isset($_GET['id']))
{
    header("Location:update.php");
    exit();
}

$id = intval($_GET['id']);

$sql = "

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
w.remarks

FROM employee e

INNER JOIN workshops w

ON e.ic_no=w.ic_no

WHERE w.id=?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0)
{
    die("Employee not found.");
}

$row = $result->fetch_assoc();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Update Employee</title>

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

Modify workshop attendance details for the selected employee.

</p>

</div>

</div>

<div class="form-card">

<form action="save_employee.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<div class="form-grid">

<div class="form-group">

<label>IC Number</label>

<input
type="text"
value="<?php echo $row['ic_no']; ?>"
readonly>

</div>

<div class="form-group">

<label>Employee Name</label>

<input
type="text"
value="<?php echo $row['name']; ?>"
readonly>

</div>

<div class="form-group">

<label>Phone Number</label>

<input
type="text"
value="<?php echo $row['phone']; ?>"
readonly>

</div>

<div class="form-group">

<label>Designation</label>

<input
type="text"
value="<?php echo $row['designation']; ?>"
readonly>

</div>

<div class="form-group full-width">

<label>Email Address</label>

<input
type="email"
value="<?php echo $row['email']; ?>"
readonly>

</div>

<div class="form-group">

<label>Workshop</label>

<input
type="text"
value="<?php echo $row['workshop_name']; ?>"
readonly>

</div>

<div class="form-group">

<label>Workshop Year</label>

<input
type="text"
value="<?php echo $row['workshop_year']; ?>"
readonly>

</div>

<div class="form-group">

<label>Attendance Date</label>

<input

type="date"

name="attended_date"

value="<?php echo $row['attended_date']; ?>"

max="<?php echo date('Y-m-d'); ?>">

</div>

<div class="form-group">

<label>Attendance Status</label>

<select name="attendance_status">

<option value="Pending"
<?php if($row['attendance_status']=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Attended"
<?php if($row['attendance_status']=="Attended") echo "selected"; ?>>

Attended

</option>

<option value="Absent"
<?php if($row['attendance_status']=="Absent") echo "selected"; ?>>

Absent

</option>

</select>

</div>

<div class="form-group full-width">

<label>Remarks</label>

<textarea

name="remarks"

rows="5"

placeholder="Enter remarks..."><?php echo htmlspecialchars($row['remarks']); ?></textarea>

</div>

</div>

<div class="button-group">

<button
type="submit"
class="btn-save">

<i class="fa-solid fa-floppy-disk"></i>

Save Changes

</button>

<a

href="generate_json.php?id=<?php echo $row['id']; ?>"

class="btn-report">

<i class="fa-solid fa-file-code"></i>

Generate JSON Report

</a>

<a

href="update.php"

class="btn-back">

<i class="fa-solid fa-arrow-left"></i>

Back

</a>

</div>

</form>

</div>

</div>


<a
href="generate_json.php?id=<?php echo $row['id']; ?>"
class="action-btn"
style="background:#16a34a;">

<i class="fa-solid fa-file-code"></i>

Generate JSON Report

</a>

<a
href="update.php"
class="action-btn"
style="background:#6b7280;">

<i class="fa-solid fa-arrow-left"></i>

Back

</a>

</div>

</form>

</div>

</div>

</body>

</html>

<?php

$stmt->close();

$conn->close();

?>