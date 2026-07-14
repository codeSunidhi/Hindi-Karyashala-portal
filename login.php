<?php

session_start();

include("config/db.php");

if($_SERVER["REQUEST_METHOD"]!="POST")
{
    header("Location:index.php");
    exit();
}

$ic_no = trim($_POST['ic_no']);
$password = trim($_POST['password']);

if(empty($ic_no) || empty($password))
{
    echo "<script>

    alert('Please enter IC Number and Password.');

    window.location='index.php';

    </script>";

    exit();
}

/* ==========================================
   LOGIN QUERY
========================================== */

$sql = "

SELECT

e.ic_no,
e.name,
e.phone,
e.designation,
e.email,

r.password,
r.role

FROM employee e

INNER JOIN roles r

ON e.ic_no = r.ic_no

WHERE e.ic_no = ?

";

$stmt = $conn->prepare($sql);

$stmt->bind_param("i",$ic_no);

$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows==0)
{
    echo "<script>

    alert('Invalid IC Number.');

    window.location='index.php';

    </script>";

    exit();
}

$user = $result->fetch_assoc();

$stmt->close();

/* ==========================================
   PASSWORD CHECK
========================================== */

if($password != $user['password'])
{
    echo "<script>

    alert('Incorrect Password.');

    window.location='index.php';

    </script>";

    exit();
}

/* ==========================================
   CREATE SESSION
========================================== */

$_SESSION['loggedin'] = true;

$_SESSION['ic_no'] = $user['ic_no'];

$_SESSION['name'] = $user['name'];

$_SESSION['phone'] = $user['phone'];

$_SESSION['designation'] = $user['designation'];

$_SESSION['email'] = $user['email'];

$_SESSION['role'] = $user['role'];

/* ==========================================
   ACTIVITY LOG
========================================== */

$activity = $user['name']." logged into the system";

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

$user['ic_no']

);

$stmt->execute();

$stmt->close();

/* ==========================================
   REDIRECT
========================================== */

if($user['role']=="Admin")
{
    header("Location:admin/dashboard.php");
}
else
{
    header("Location:employee/dashboard.php");
}

exit();

?>