<?php
include "../includes/auth.php";
include "../config/db.php";

if(!isset($_GET["id"])){
    header("Location:reports.php");
    exit();
}

$id=(int)$_GET["id"];

$stmt=$conn->prepare("
SELECT report_id,employee_ic
FROM reports
WHERE report_id=?
AND status='Pending'
LIMIT 1
");

$stmt->bind_param("i",$id);
$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows==0){
    $_SESSION["message"]="Report not found or already verified.";
    header("Location:reports.php");
    exit();
}

$data=$result->fetch_assoc();

$verify=$conn->prepare("
UPDATE reports
SET
status='Verified',
verified_by=?,
verified_date=NOW()
WHERE report_id=?
");

$verify->bind_param(
"ii",
$_SESSION["ic_number"],
$id
);

if($verify->execute()){

    $activity="Verified report for Employee IC ".$data["employee_ic"];

    $log=$conn->prepare("
    INSERT INTO activity_log
    (activity,activity_by)
    VALUES(?,?)
    ");

    $log->bind_param(
    "si",
    $activity,
    $_SESSION["ic_number"]
    );

    $log->execute();

    $_SESSION["message"]="Report verified successfully.";

}else{

    $_SESSION["message"]="Unable to verify report.";

}

header("Location:reports.php");
exit();
?>