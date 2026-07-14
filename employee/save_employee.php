<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role'] != "Karyashala Admin")
{
    header("Location:../index.php");
    exit();
}

if($_SERVER["REQUEST_METHOD"] != "POST")
{
    header("Location:update.php");
    exit();
}

/* ==========================
   GET FORM DATA
========================== */

$id = intval($_POST['id']);

$phone = trim($_POST['phone']);

$designation = trim($_POST['designation']);

$email = trim($_POST['email']);

$attended_date = $_POST['attended_date'];

$attendance_status = $_POST['attendance_status'];

$remarks = trim($_POST['remarks']);

$updated_by = $_SESSION['ic_no'];

/* ==========================
   VALIDATE DATE
========================== */

if(!empty($attended_date))
{
    if($attended_date > date("Y-m-d"))
    {
        echo "<script>

        alert('Attendance date cannot be greater than today.');

        history.back();

        </script>";

        exit();
    }
}

/* ==========================
   GET EMPLOYEE IC
========================== */

$sql = "SELECT ic_no FROM workshops WHERE id=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

$row = $result->fetch_assoc();

$employee_ic = $row['ic_no'];

$stmt->close();

/* ==========================
   UPDATE EMPLOYEE
========================== */

$sql = "

UPDATE employee

SET

phone=?,

designation=?,

email=?

WHERE ic_no=?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"sssi",

$phone,

$designation,

$email,

$employee_ic

);

$stmt->execute();

$stmt->close();

/* ==========================
   UPDATE WORKSHOP
========================== */

$sql = "

UPDATE workshops

SET

attended_date=?,

attendance_status=?,

remarks=?,

updated_by=?

WHERE id=?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"sssii",

$attended_date,

$attendance_status,

$remarks,

$updated_by,

$id

);

$stmt->execute();

$stmt->close();

/* ==========================
   ACTIVITY LOG
========================== */

$activity = $_SESSION['name']." updated employee IC ".$employee_ic;

$sql = "

INSERT INTO activity_log

(

activity,

activity_by

)

VALUES

(

?,

?

)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"si",

$activity,

$updated_by

);

$stmt->execute();

$stmt->close();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Successful</title>

<link rel="stylesheet" href="../css/dashboard.css">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<?php include("../includes/sidebar.php"); ?>

<div class="content">

<div class="success-card">

<i class="fa-solid fa-circle-check success-icon"></i>

<h2>

Employee Updated Successfully

</h2>

<p>

All changes have been saved.

</p>

<br>

<a

href="update_save.php?id=<?php echo $id; ?>"

class="btn-report">

Generate JSON Report

</a>

<br><br>

<a

href="update.php"

class="btn-back">

Back to Employee List

</a>

</div>

</div>

</body>

</html>

<?php

$conn->close();

?>