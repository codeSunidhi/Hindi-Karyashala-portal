<?php
include "../includes/auth.php";
include "../config/db.php";

$message = "";

if (isset($_SESSION["message"])) {
    $message = $_SESSION["message"];
    unset($_SESSION["message"]);
}

$result = $conn->query("SELECT MAX(ic_number) AS last_ic FROM employees");
$row = $result->fetch_assoc();
$nextIC = $row["last_ic"] + 1;

$_SESSION["csrf_token"] = bin2hex(random_bytes(32));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Employee</title>

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
    <h1>Add Employee</h1>
    <p>Register a new employee in the Hindi Karyashala Portal.</p>
</div>

<div class="form-card">

<form action="save_employee.php" method="POST" onsubmit="return validateEmployee()">

<input type="hidden" name="csrf_token" value="<?php echo $_SESSION["csrf_token"]; ?>">

<div class="form-grid">

<div class="form-group">
<label>IC Number</label>
<input type="text" name="ic_number" id="ic_number" value="<?php echo $nextIC; ?>" readonly>
</div>

<div class="form-group">
<label>Employee Name</label>
<input type="text" name="name" id="name">
</div>

<div class="form-group">
<label>Phone Number</label>
<input type="text" name="phone" id="phone" maxlength="10">
</div>

<div class="form-group">
<label>Designation</label>
<input type="text" name="designation" id="designation">
</div>

<div class="form-group full">
<label>Email Address</label>
<input type="email" name="email" id="email">
</div>

<div class="full section-title">
<h3>Workshop Information</h3>
</div>

<div class="form-group">
<label>Workshop Name</label>
<input type="text" name="workshop_name" id="workshop_name">
</div>

<div class="form-group">
<label>Workshop Year</label>
<input type="number" name="workshop_year" id="workshop_year" min="2020" max="<?php echo date('Y')+1; ?>">
</div>

<div class="form-group">
<label>Attendance Date</label>
<input type="date" name="attendance_date" id="attendance_date">
</div>

<div class="form-group">
<label>Attendance Status</label>
<select name="attendance_status" id="attendance_status">
    <option value="">Select Status</option>
    <option value="Pending">Pending</option>
    <option value="Attended">Attended</option>
    <option value="Absent">Absent</option>
</select>
</div>

<div class="form-group full">
<label>Remarks</label>
<textarea name="remarks" id="remarks" rows="4" maxlength="250"></textarea>
</div>

</div>

<?php
if ($message != "") {
    echo "<p class='message'>$message</p>";
}
?>

<p id="error" class="error"></p>

<div class="buttons">
<button class="save-btn" type="submit">
<i class="fa-solid fa-floppy-disk"></i>
Save Employee
</button>
</div>

</form>

</div>

</div>

</div>

<script>

function validateEmployee(){

    let name=document.getElementById("name").value.trim();
    let phone=document.getElementById("phone").value.trim();
    let designation=document.getElementById("designation").value.trim();
    let email=document.getElementById("email").value.trim();
    let workshop=document.getElementById("workshop_name").value.trim();
    let year=document.getElementById("workshop_year").value.trim();
    let attendanceDate=document.getElementById("attendance_date").value;
    let attendance=document.getElementById("attendance_status").value;
    let remarks=document.getElementById("remarks").value.trim();
    let error=document.getElementById("error");

    error.innerHTML="";

    if(name==""){
        error.innerHTML="Please enter employee name.";
        return false;
    }

    if(!/^[A-Za-z ]+$/.test(name)){
        error.innerHTML="Name should contain only letters.";
        return false;
    }

    if(phone==""){
        error.innerHTML="Please enter phone number.";
        return false;
    }

    if(!/^[0-9]{10}$/.test(phone)){
        error.innerHTML="Phone number must be 10 digits.";
        return false;
    }

    if(designation==""){
        error.innerHTML="Please enter designation.";
        return false;
    }

    if(email==""){
        error.innerHTML="Please enter email.";
        return false;
    }

    let pattern=/^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if(!pattern.test(email)){
        error.innerHTML="Invalid email address.";
        return false;
    }

    if(workshop==""){
    error.innerHTML="Please enter workshop name.";
    return false;
}

if(year==""){
    error.innerHTML="Please enter workshop year.";
    return false;
}

let currentYear=new Date().getFullYear();

if(year<2020 || year>currentYear+1){
    error.innerHTML="Invalid workshop year.";
    return false;
}

if(attendanceDate==""){
    error.innerHTML="Please select attendance date.";
    return false;
}

if(attendance==""){
    error.innerHTML="Please select attendance status.";
    return false;
}

if(remarks.length>250){
    error.innerHTML="Remarks cannot exceed 250 characters.";
    return false;
}

    return true;
}

</script>

</body>
</html>