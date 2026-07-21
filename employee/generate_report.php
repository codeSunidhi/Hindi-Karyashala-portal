<?php
include "../includes/auth.php";
include "../config/db.php";

if(!isset($_GET["ic"])){
    header("Location:view.php");
    exit();
}

$ic=(int)$_GET["ic"];

/* Get latest workshop details */

$stmt=$conn->prepare("
SELECT
workshop_name,
workshop_year
FROM workshops
WHERE employee_ic=?
");

$stmt->bind_param("i",$ic);
$stmt->execute();

$workshop=$stmt->get_result()->fetch_assoc();

if(!$workshop){

    $_SESSION["message"]="Workshop details not found.";

    header("Location:view.php");

    exit();

}

$year=$workshop["workshop_year"];
$reportName=$workshop["workshop_name"]." Report";

/* Check duplicate report */

$check=$conn->prepare("
SELECT report_id
FROM reports
WHERE employee_ic=?
AND workshop_year=?
");

$check->bind_param(
"ii",
$ic,
$year
);

$check->execute();

if($check->get_result()->num_rows>0){

    $_SESSION["message"]="Report already generated for this workshop.";

    header("Location:view.php");

    exit();

}

/* Insert report */

$stmt=$conn->prepare("
INSERT INTO reports(

report_name,
employee_ic,
workshop_year,
generated_date,
status

)

VALUES(

?,
?,
?,
NOW(),
'Pending'

)
");

$stmt->bind_param(

"sii",

$reportName,
$ic,
$year

);

if($stmt->execute()){

    $activity="Generated workshop report for Employee IC ".$ic;

    $log=$conn->prepare("
    INSERT INTO activity_log(
    activity,
    activity_by
    )
    VALUES(?,?)
    ");

    $log->bind_param(

    "si",

    $activity,
    $_SESSION["ic_number"]

    );

    $log->execute();

    $_SESSION["message"]="Report generated successfully and sent for Admin verification.";

}

else{

    $_SESSION["message"]="Unable to generate report.";

}

header("Location:view.php");

exit();

?>