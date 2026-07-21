<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
include "../includes/auth.php";
include "../config/db.php";

if(!isset($_GET["ic"])){
    header("Location:view.php");
    exit();
}

$ic=(int)$_GET["ic"];

$updated=false;

if(isset($_POST["update"])){

    $workshop_name=$_POST["workshop_name"];
    $workshop_year=$_POST["workshop_year"];
    $attendance_date=$_POST["attendance_date"];
    $attendance_status=$_POST["attendance_status"];
    $remarks=$_POST["remarks"];

    $stmt=$conn->prepare("
    UPDATE workshops
    SET
    workshop_name=?,
    workshop_year=?,
    attendance_date=?,
    attendance_status=?,
    remarks=?,
    updated_by=?
    WHERE employee_ic=?
    ");

    $stmt->bind_param(
        "sisssii",
        $workshop_name,
        $workshop_year,
        $attendance_date,
        $attendance_status,
        $remarks,
        $_SESSION["ic_number"],
        $ic
    );

    if($stmt->execute()){

        $activity="Updated workshop details of Employee IC ".$ic;

        $log=$conn->prepare("
        INSERT INTO activity_log(activity,activity_by)
        VALUES(?,?)
        ");

        $log->bind_param(
            "si",
            $activity,
            $_SESSION["ic_number"]
        );

        $log->execute();

        $updated=true;
    }
}

$stmt=$conn->prepare("
SELECT

employees.ic_number,
employees.name,
employees.phone,
employees.designation,
employees.email,

workshops.workshop_name,
workshops.workshop_year,
workshops.attendance_date,
workshops.attendance_status,
workshops.remarks

FROM employees

INNER JOIN workshops
ON employees.ic_number=workshops.employee_ic

WHERE employees.ic_number=?
");

$stmt->bind_param("i",$ic);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){

die("Employee not found.");

}

$row=$result->fetch_assoc();
?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Workshop</title>

<link rel="stylesheet" href="../css/layout.css">
<link rel="stylesheet" href="../css/form.css">
<link rel="stylesheet" href="../css/modal.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="overlay">

<?php include "../includes/navbar.php"; ?>

<?php include "../includes/employee_sidebar.php"; ?>

<div class="main">

<div class="page-header">

<h1>Update Workshop</h1>

<p>Update workshop details for the employee.</p>

</div>

<div class="form-card">

<form method="POST">

<div class="form-grid">

<div class="form-group">

<label>IC Number</label>

<input
type="text"
value="<?php echo $row["ic_number"]; ?>"
readonly>

</div>

<div class="form-group">

<label>Name</label>

<input
type="text"
value="<?php echo htmlspecialchars($row["name"]); ?>"
readonly>

</div>

<div class="form-group">

<label>Phone</label>

<input
type="text"
value="<?php echo htmlspecialchars($row["phone"]); ?>"
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

<label>Email</label>

<input
type="text"
value="<?php echo htmlspecialchars($row["email"]); ?>"
readonly>

</div>

<div class="form-group">

<label>Workshop Name</label>

<input
type="text"
name="workshop_name"
value="<?php echo htmlspecialchars($row["workshop_name"]); ?>"
required>

</div>

<div class="form-group">

<label>Workshop Year</label>

<input
type="number"
name="workshop_year"
value="<?php echo $row["workshop_year"]; ?>"
required>

</div>

<div class="form-group">

<label>Attendance Date</label>

<input
type="date"
name="attendance_date"
value="<?php echo $row["attendance_date"]; ?>">

</div>

<div class="form-group">

<label>Attendance Status</label>

<select name="attendance_status">

<option value="Pending"
<?php if($row["attendance_status"]=="Pending") echo "selected"; ?>>

Pending

</option>

<option value="Attended"
<?php if($row["attendance_status"]=="Attended") echo "selected"; ?>>

Attended

</option>

<option value="Absent"
<?php if($row["attendance_status"]=="Absent") echo "selected"; ?>>

Absent

</option>

</select>

</div>

<div class="form-group full-width">

<label>Remarks</label>

<textarea
name="remarks"
rows="5"><?php echo htmlspecialchars($row["remarks"]); ?></textarea>

</div>

</div>

<div class="form-buttons">

<button
type="submit"
name="update"
class="save-btn">

<i class="fa-solid fa-floppy-disk"></i>

Save Changes

</button>

<button
type="button"
id="generateBtn"
class="report-btn"

<?php if(!$updated){ ?>

disabled
style="opacity:.5;cursor:not-allowed;"

<?php } ?>

>

<i class="fa-solid fa-file-lines"></i>

Generate Report

</button>

<a
href="view.php"
class="cancel-btn">

Cancel

</a>

</div>

</form>

</div>

</div>

</div>
<div id="generateModal" class="modal">

    <div class="modal-content">

        <span class="close">&times;</span>

        <h2 style="margin-bottom:20px;">
            Generate Workshop Report
        </h2>

        <table class="modal-table">

            <tr>
                <td>Employee IC</td>
                <td><?php echo $row["ic_number"]; ?></td>
            </tr>

            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($row["name"]); ?></td>
            </tr>

            <tr>
                <td>Workshop</td>
                <td><?php echo htmlspecialchars($row["workshop_name"]); ?></td>
            </tr>

            <tr>
                <td>Workshop Year</td>
                <td><?php echo htmlspecialchars($row["workshop_year"]); ?></td>
            </tr>

            <tr>
                <td>Attendance</td>
                <td><?php echo htmlspecialchars($row["attendance_status"]); ?></td>
            </tr>

            <tr>
                <td>Remarks</td>
                <td><?php echo htmlspecialchars($row["remarks"]); ?></td>
            </tr>

        </table>

        <p style="margin-top:25px;color:#555;font-size:15px;">
            Are you sure you want to generate this report?
            <br>
            This report will be sent to the Administrator for verification.
        </p>

        <div class="modal-buttons">

            <a
            href="generate_report.php?ic=<?php echo $row["ic_number"]; ?>"
            class="save-btn">

                <i class="fa-solid fa-file-lines"></i>

                Generate Report

            </a>

            <button
            type="button"
            class="cancel-btn"
            id="closeModal">

                Cancel

            </button>

        </div>

    </div>

</div>
<script>

const modal=document.getElementById("generateModal");

const btn=document.getElementById("generateBtn");

const close=document.querySelector(".close");

const cancel=document.getElementById("closeModal");

if(btn){

btn.onclick=function(){

if(btn.disabled){

return;

}

modal.style.display="block";

}

}

close.onclick=function(){

modal.style.display="none";

}

cancel.onclick=function(){

modal.style.display="none";

}

window.onclick=function(e){

if(e.target==modal){

modal.style.display="none";

}

}

</script>
</body>

</html>