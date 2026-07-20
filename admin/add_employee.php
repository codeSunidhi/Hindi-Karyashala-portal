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

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Add Employee</title>

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

<i class="fa-solid fa-user-plus"></i>

Add New Employee

</h2>

<p>

Create a new employee account and workshop record.

</p>

</div>

</div>

<div class="form-card">

<form action="save_employee.php" method="POST">

<div class="form-grid">

<!-- ===========================
     EMPLOYEE INFORMATION
=========================== -->

<div class="form-group">

<label>

IC Number

</label>

<input

type="number"

name="ic_no"

required

placeholder="Enter IC Number"

>

</div>

<div class="form-group">

<label>

Employee Name

</label>

<input

type="text"

name="name"

required

placeholder="Enter Full Name"

>

</div>

<div class="form-group">

<label>

Phone Number

</label>

<input

type="text"

name="phone"

required

placeholder="Enter Phone Number"

>

</div>

<div class="form-group">

<label>

Designation

</label>

<input

type="text"

name="designation"

required

placeholder="Enter Designation"

>

</div>

<div class="form-group full-width">

<label>

Email Address

</label>

<input

type="email"

name="email"

required

placeholder="Enter Email Address"

>

</div>

<!-- ===========================
     LOGIN DETAILS
=========================== -->

<div class="form-group">

<label>

Password

</label>

<input

type="text"

name="password"

required

placeholder="Create Password"

>

</div>

<div class="form-group">

<label>

Role

</label>

<select name="role" required>

<option value="Employee">

Employee

</option>

<option value="Karyashala Admin">

Karyashala Admin

</option>

<option value="Admin">

Admin

</option>

</select>

</div>
<!-- ===========================
     WORKSHOP INFORMATION
=========================== -->

<div class="form-group">

<label>

Workshop Name

</label>

<input

type="text"

name="workshop_name"

required

placeholder="Enter Workshop Name"

>

</div>

<div class="form-group">

<label>

Workshop Year

</label>

<input

type="number"

name="workshop_year"

value="<?php echo date('Y'); ?>"

required

>

</div>

<div class="form-group">

<label>

Attendance Status

</label>

<select name="attendance_status">

<option value="Pending" selected>

Pending

</option>

<option value="Attended">

Attended

</option>

<option value="Absent">

Absent

</option>

</select>

</div>

<div class="form-group full-width">

<label>

Remarks

</label>

<textarea

name="remarks"

rows="4"

placeholder="Enter remarks (optional)"

></textarea>

</div>

</div>

<br>

<div class="button-group">

<button

type="submit"

class="btn-save"

>

<i class="fa-solid fa-floppy-disk"></i>

Save Employee

</button>

<a

href="dashboard.php"

class="btn-back"

>

<i class="fa-solid fa-arrow-left"></i>

Cancel

</a>

</div>

</form>

</div>

</div>

</body>

</html>