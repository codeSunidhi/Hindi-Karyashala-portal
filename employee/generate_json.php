<?php

session_start();

include("../config/db.php");
include("../includes/auth.php");

if($_SESSION['role'] != "Karyashala Admin")
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

/* ===================================
   FETCH EMPLOYEE & WORKSHOP
=================================== */

$sql = "

SELECT

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

ON e.ic_no = w.ic_no

WHERE w.id = ?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$id);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows == 0)
{
    echo "<script>

    alert('Employee not found.');

    window.location='update.php';

    </script>";

    exit();
}

$row = $result->fetch_assoc();

$stmt->close();

/* ===================================
   ATTENDANCE CHECK
=================================== */

if($row['attendance_status'] == "Pending")
{
    echo "<script>

    alert('Please update attendance before generating report.');

    window.location='update.php';

    </script>";

    exit();
}

/* ===================================
   REPORT NAME
=================================== */

$reportName = "Report_" . $row['ic_no'] . "_" . $row['workshop_year'];

/* ===================================
   CHECK DUPLICATE REPORT
=================================== */

$sql = "

SELECT id

FROM reports

WHERE

employee_ic = ?

AND report_year = ?

AND status = 'Pending'

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"ii",

$row['ic_no'],

$row['workshop_year']

);

$stmt->execute();

$check = $stmt->get_result();

if($check->num_rows > 0)
{
    echo "<script>

    alert('A pending report already exists for this employee.');

    window.location='update.php';

    </script>";

    exit();
}

$stmt->close();

/* ===================================
   JSON DATA
=================================== */

$jsonData = array(

"employee"=>array(

"ic_no"=>$row['ic_no'],
"name"=>$row['name'],
"phone"=>$row['phone'],
"designation"=>$row['designation'],
"email"=>$row['email']

),

"workshop"=>array(

"workshop_name"=>$row['workshop_name'],
"workshop_year"=>$row['workshop_year'],
"attended_date"=>$row['attended_date'],
"attendance_status"=>$row['attendance_status'],
"remarks"=>$row['remarks']

),

"generated_by"=>array(

"ic_no"=>$_SESSION['ic_no'],
"name"=>$_SESSION['name']

),

"generated_date"=>date("Y-m-d H:i:s")

);

/* ===================================
   CREATE REPORT FOLDER
=================================== */

if(!is_dir("../reports"))
{
    mkdir("../reports",0777,true);
}

/* ===================================
   FILE NAME
=================================== */

$fileName =

"report_"

.$row['ic_no']

."_"

.$row['workshop_year']

."_"

.date("YmdHis")

.".json";

$filePath = "../reports/".$fileName;

/* ===================================
   SAVE JSON
=================================== */

file_put_contents(

$filePath,

json_encode(

$jsonData,

JSON_PRETTY_PRINT

)

);

/* ===================================
   INSERT REPORT
=================================== */

$sql = "

INSERT INTO reports

(

report_name,

report_year,

employee_ic,

generated_by,

status,

json_path

)

VALUES

(

?,

?,

?,

?,

'Pending',

?

)

";

$stmt = $conn->prepare($sql);

$stmt->bind_param(

"siiis",

$reportName,

$row['workshop_year'],

$row['ic_no'],

$_SESSION['ic_no'],

$filePath

);

$stmt->execute();

$stmt->close();

/* ===================================
   ACTIVITY LOG
=================================== */

$activity =

"Generated report for "

.$row['name'];

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

$_SESSION['ic_no']

);

$stmt->execute();

$stmt->close();

$conn->close();

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Report Generated</title>

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

JSON Report Generated Successfully

</h2>

<p>

<b>Employee :</b>

<?php echo $row['name']; ?>

</p>

<p>

<b>Report :</b>

<?php echo $fileName; ?>

</p>

<br>

<a

href="update.php"

class="btn-save">

Back to Update Page

</a>

</div>

</div>

</body>

</html>