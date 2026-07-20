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
    header("Location:reports.php");
    exit();
}

$id = intval($_GET['id']);

/* Get Report */

$sql="

SELECT

r.id,
r.report_name,
e.name

FROM reports r

INNER JOIN employee e
ON r.employee_ic=e.ic_no

WHERE r.id=?

";

$stmt=$conn->prepare($sql);
$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0)
{
    die("Report not found.");
}

$row=$result->fetch_assoc();

$stmt->close();

/* Verify Report */

$sql="

UPDATE reports

SET

status='Verified',

verified_by=?,

verified_date=NOW()

WHERE id=?

";

$stmt=$conn->prepare($sql);

$stmt->bind_param(

"ii",

$_SESSION['ic_no'],

$id

);

$stmt->execute();

$stmt->close();

/* Activity Log */

$activity="Admin verified report of ".$row['name'];

$stmt=$conn->prepare("

INSERT INTO activity_log

(activity,activity_by)

VALUES (?,?)

");

$stmt->bind_param(

"si",

$activity,

$_SESSION['ic_no']

);

$stmt->execute();

$stmt->close();

$conn->close();

/* Redirect */

header("Location:reports.php?verified=1");

exit();

?>