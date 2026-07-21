<?php
include "../includes/auth.php";
include "../config/db.php";

if($_SERVER["REQUEST_METHOD"]!="POST"){
    header("Location:add_employee.php");
    exit();
}

if(
    !isset($_POST["csrf_token"]) ||
    $_POST["csrf_token"]!=$_SESSION["csrf_token"]
){
    die("Invalid CSRF Token");
}

$ic=trim($_POST["ic_number"]);
$name=trim($_POST["name"]);
$phone=trim($_POST["phone"]);
$designation=trim($_POST["designation"]);
$email=trim($_POST["email"]);

$workshop_name=trim($_POST["workshop_name"]);
$workshop_year=trim($_POST["workshop_year"]);
$attendance_date=$_POST["attendance_date"];
$attendance_status=$_POST["attendance_status"];
$remarks=trim($_POST["remarks"]);

if(
    empty($ic)||
    empty($name)||
    empty($phone)||
    empty($designation)||
    empty($email)||
    empty($workshop_name)||
    empty($workshop_year)||
    empty($attendance_date)||
    empty($attendance_status)
){
    $_SESSION["message"]="Please fill all required fields.";
    header("Location:add_employee.php");
    exit();
}

if(!preg_match("/^[A-Za-z ]+$/",$name)){
    $_SESSION["message"]="Invalid employee name.";
    header("Location:add_employee.php");
    exit();
}

if(!preg_match("/^[0-9]{10}$/",$phone)){
    $_SESSION["message"]="Phone number must be 10 digits.";
    header("Location:add_employee.php");
    exit();
}

if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
    $_SESSION["message"]="Invalid email address.";
    header("Location:add_employee.php");
    exit();
}

$status=["Pending","Attended","Absent"];

if(!in_array($attendance_status,$status)){
    $_SESSION["message"]="Invalid attendance status.";
    header("Location:add_employee.php");
    exit();
}

$stmt=$conn->prepare("SELECT ic_number FROM employees WHERE ic_number=? OR email=?");
$stmt->bind_param("is",$ic,$email);
$stmt->execute();

if($stmt->get_result()->num_rows>0){
    $_SESSION["message"]="IC Number or Email already exists.";
    header("Location:add_employee.php");
    exit();
}

$conn->begin_transaction();

try{

    $stmt=$conn->prepare("INSERT INTO employees(ic_number,name,phone,designation,email) VALUES(?,?,?,?,?)");
    $stmt->bind_param("issss",$ic,$name,$phone,$designation,$email);
    $stmt->execute();

    $updatedBy=$_SESSION["ic_number"];

    $stmt=$conn->prepare("INSERT INTO workshops(employee_ic,workshop_name,workshop_year,attendance_date,attendance_status,remarks,updated_by) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param(
        "isisssi",
        $ic,
        $workshop_name,
        $workshop_year,
        $attendance_date,
        $attendance_status,
        $remarks,
        $updatedBy
    );
    $stmt->execute();

    $activity="Added employee ".$name." and workshop details.";

    $stmt=$conn->prepare("INSERT INTO activity_log(activity,activity_by) VALUES(?,?)");
    $stmt->bind_param("si",$activity,$updatedBy);
    $stmt->execute();

    $conn->commit();

    $_SESSION["message"]="Employee added successfully.";

}catch(Exception $e){

    $conn->rollback();

    $_SESSION["message"]="Unable to save employee.";

}

header("Location:add_employee.php");
exit();