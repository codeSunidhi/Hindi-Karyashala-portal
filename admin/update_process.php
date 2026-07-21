<?php
include "../includes/auth.php";
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]!="POST"){
    header("Location:view.php");
    exit();
}

if(
!isset($_POST["csrf_token"]) ||
$_POST["csrf_token"]!=$_SESSION["csrf_token"]
){
    die("Invalid CSRF Token");
}

$ic=(int)$_POST["ic_number"];
$workshop=trim($_POST["workshop_name"]);
$year=(int)$_POST["workshop_year"];
$date=$_POST["attendance_date"];
$status=$_POST["attendance_status"];
$remarks=trim($_POST["remarks"]);

$allowed=["Pending","Attended","Absent"];

if(
$workshop=="" ||
$year<2024 ||
!in_array($status,$allowed)
){
    $_SESSION["message"]="Invalid input.";
    header("Location:update.php?ic=".$ic);
    exit();
}

if($status!="Pending" && $date==""){
    $_SESSION["message"]="Attendance date is required.";
    header("Location:update.php?ic=".$ic);
    exit();
}

$stmt=$conn->prepare("
UPDATE workshops
SET
workshop_name=?,
workshop_year=?,
attendance_date=?,
attendance_status=?,
remarks=?,
updated_by=?,
updated_at=NOW()
WHERE employee_ic=?
");

$stmt->bind_param(
"sisssii",
$workshop,
$year,
$date,
$status,
$remarks,
$_SESSION["ic_number"],
$ic
);

if($stmt->execute()){

    $activity="Updated workshop details for Employee IC ".$ic;

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

    $_SESSION["message"]="Workshop details updated successfully.";

}else{

    $_SESSION["message"]="Unable to update record.";

}

$stmt->close();

header("Location:view.php");
exit();
?>