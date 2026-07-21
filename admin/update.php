<?php
include "../includes/auth.php";
include "../config/db.php";

if(!isset($_GET["ic"])){
    header("Location:view.php");
    exit();
}

$ic=(int)$_GET["ic"];

$sql="SELECT
employees.ic_number,
employees.name,
employees.designation,
employees.phone,
employees.email,
workshops.workshop_name,
workshops.workshop_year,
workshops.attendance_date,
workshops.attendance_status,
workshops.remarks
FROM employees
LEFT JOIN workshops
ON employees.ic_number=workshops.employee_ic
WHERE employees.ic_number=$ic
LIMIT 1";

$result=$conn->query($sql);

if($result->num_rows==0){
    header("Location:view.php");
    exit();
}

$row=$result->fetch_assoc();

$_SESSION["csrf_token"]=bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>
<head>

<title>Update Employee</title>

<link rel="stylesheet" href="../css/layout.css">
<link rel="stylesheet" href="../css/form.css">

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
<h1>Update Workshop</h1>
<p>Update employee workshop attendance details.</p>
</div>

<div class="form-card">

<form action="update_process.php" method="POST" onsubmit="return validateUpdate();">

<input
type="hidden"
name="csrf_token"
value="<?php echo $_SESSION["csrf_token"]; ?>">

<input
type="hidden"
name="ic_number"
value="<?php echo $row["ic_number"]; ?>">

<div class="form-grid">

<div class="form-group">
<label>IC Number</label>
<input
type="text"
value="<?php echo $row["ic_number"]; ?>"
readonly>
</div>

<div class="form-group">
<label>Employee Name</label>
<input
type="text"
value="<?php echo htmlspecialchars($row["name"]); ?>"
readonly>
</div>

<div class="form-group">
<label>Designation</label>
<input
type="text"
value="<?php echo htmlspecialchars($row["designation"]); ?>"
readonly>
</div>

<div class="form-group">
<label>Workshop Name</label>
<input
type="text"
name="workshop_name"
id="workshop_name"
value="<?php echo htmlspecialchars($row["workshop_name"]); ?>">
</div>

<div class="form-group">
<label>Workshop Year</label>
<input
type="number"
name="workshop_year"
id="workshop_year"
min="2024"
max="2100"
value="<?php echo $row["workshop_year"]; ?>">
</div>

<div class="form-group">
<label>Attendance Date</label>
<input
type="date"
name="attendance_date"
id="attendance_date"
value="<?php echo $row["attendance_date"]; ?>">
</div>

<div class="form-group full">
<label>Attendance Status</label>

<select
name="attendance_status"
id="attendance_status">

<option value="">Select Status</option>

<option value="Attended"
<?php if($row["attendance_status"]=="Attended") echo "selected"; ?>>
Attended
</option>

<option value="Pending"
<?php if($row["attendance_status"]=="Pending") echo "selected"; ?>>
Pending
</option>

<option value="Absent"
<?php if($row["attendance_status"]=="Absent") echo "selected"; ?>>
Absent
</option>

</select>

</div>

<div class="form-group full">
<label>Remarks</label>

<textarea
name="remarks"
id="remarks"
rows="4"><?php echo htmlspecialchars($row["remarks"]); ?></textarea>

</div>

</div>

<p id="error" class="error"></p>

<div class="buttons">

<button
type="submit"
class="save-btn">

<i class="fa-solid fa-floppy-disk"></i>

Update Details

</button>

</div>

</form>

</div>

</div>

</div>

<script>

function validateUpdate(){

let workshop=document.getElementById("workshop_name").value.trim();
let year=document.getElementById("workshop_year").value.trim();
let date=document.getElementById("attendance_date").value;
let status=document.getElementById("attendance_status").value;
let remarks=document.getElementById("remarks").value.trim();
let error=document.getElementById("error");

error.innerHTML="";

if(workshop==""){
error.innerHTML="Please enter workshop name.";
return false;
}

if(year==""){
error.innerHTML="Please enter workshop year.";
return false;
}

if(status==""){
error.innerHTML="Please select attendance status.";
return false;
}

if(status!="Pending" && date==""){
error.innerHTML="Please select attendance date.";
return false;
}

if(remarks.length>255){
error.innerHTML="Remarks cannot exceed 255 characters.";
return false;
}

return true;

}

</script>

</body>
</html>