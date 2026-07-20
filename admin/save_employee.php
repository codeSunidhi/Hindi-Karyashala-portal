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

if($_SERVER["REQUEST_METHOD"]!="POST")
{
    header("Location:add_employee.php");
    exit();
}

/* ===========================
   GET FORM DATA
=========================== */

$ic_no = intval($_POST['ic_no']);

$name = trim($_POST['name']);

$phone = trim($_POST['phone']);

$designation = trim($_POST['designation']);

$email = trim($_POST['email']);

$password = trim($_POST['password']);

$role = $_POST['role'];

$workshop_name = trim($_POST['workshop_name']);

$workshop_year = intval($_POST['workshop_year']);

$attendance_status = $_POST['attendance_status'];

$remarks = trim($_POST['remarks']);

$updated_by = $_SESSION['ic_no'];

/* ===========================
   CHECK DUPLICATE IC
=========================== */

$sql = "SELECT ic_no FROM employee WHERE ic_no=?";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$ic_no);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows>0)
{
?>
<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Duplicate Employee</title>

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

<div class="success-card">

<i class="fa-solid fa-circle-exclamation success-icon"
style="color:#ef4444;"></i>

<h2>

Employee Already Exists

</h2>

<p>

An employee with this IC Number already exists.

</p>

<br>

<a

href="add_employee.php"

class="btn-back"

>

Back

</a>

</div>

</div>

</body>

</html>

<?php

exit();

}

$stmt->close();

/* ===========================
   INSERT INTO EMPLOYEE
=========================== */

$sql = "

INSERT INTO employee

(

ic_no,

name,

phone,

designation,

email

)

VALUES

(

?,?,?,?,?

)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"issss",

$ic_no,

$name,

$phone,

$designation,

$email

);

$stmt->execute();

$stmt->close();
/* ===========================
   INSERT INTO ROLES
=========================== */

$sql = "

INSERT INTO roles

(

ic_no,

password,

role

)

VALUES

(

?,?,?

)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"iss",

$ic_no,

$password,

$role

);

$stmt->execute();

$stmt->close();

/* ===========================
   INSERT INTO WORKSHOPS
=========================== */

$sql = "

INSERT INTO workshops

(

ic_no,

workshop_name,

workshop_year,

attended_date,

attendance_status,

remarks,

updated_by

)

VALUES

(

?,?,?,?,?,?,?

)

";

$stmt = $conn->prepare($sql);

$attended_date = NULL;

$stmt->bind_param(

"isisssi",

$ic_no,

$workshop_name,

$workshop_year,

$attended_date,

$attendance_status,

$remarks,

$updated_by

);

$stmt->execute();

$stmt->close();

/* ===========================
   ACTIVITY LOG
=========================== */

$activity = $_SESSION['name']." added employee IC ".$ic_no;

$sql = "

INSERT INTO activity_log

(

activity,

activity_by

)

VALUES

(

?,?

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

$conn->close();

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<title>Employee Added</title>

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

<div class="success-card">

<i class="fa-solid fa-circle-check success-icon"></i>

<h2>

Employee Added Successfully

</h2>

<p>

<b><?php echo htmlspecialchars($name); ?></b>

has been added to the system.

</p>

<br>

<div class="button-group">

<a

href="add_employee.php"

class="btn-save"

>

<i class="fa-solid fa-user-plus"></i>

Add Another Employee

</a>

<a

href="../employee/view.php"

class="btn-back"

>

<i class="fa-solid fa-users"></i>

View Employees

</a>

</div>

</div>

</div>

</body>

</html>