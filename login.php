<?php
session_start();

if (isset($_SESSION['role'])) {

    if ($_SESSION['role'] == "Admin") {
        header("Location: admin/dashboard.php");
        exit();
    }

    if ($_SESSION['role'] == "Karyashala Admin") {
        header("Location: employee/dashboard.php");
        exit();
    }
}

include "config/db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $ic = trim($_POST["ic_no"]);
    $password = trim($_POST["password"]);

    if ($ic == "" || $password == "") {
        $error = "Please enter IC Number and Password.";
    } else {

        $stmt = $conn->prepare("SELECT * FROM roles WHERE ic_number=?");
        $stmt->bind_param("i", $ic);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $row = $result->fetch_assoc();

            if ($password == $row["password"]) {

                session_regenerate_id(true);

                $_SESSION["ic_number"] = $row["ic_number"];
                $_SESSION["role"] = $row["role"];

                if ($row["role"] == "Admin") {
                    header("Location: admin/dashboard.php");
                    exit();
                }

                header("Location: employee/dashboard.php");
                exit();
            }

            $error = "Invalid IC Number or Password.";
        } else {
            $error = "Invalid IC Number or Password.";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Hindi Karyashala Portal</title>

    <link rel="stylesheet" href="css/login.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

</head>

<body>

<div class="overlay">

<div class="login-card">

<div class="logo">

<i class="fa-solid fa-book-open-reader"></i>

<h2>Hindi Karyashala Portal</h2>

<p>Workshop Management System</p>

</div>

<form method="POST">

<div class="input-group">

<label>IC Number</label>

<input
type="text"
name="ic_no"
id="ic_no"
maxlength="4"
placeholder="Enter IC Number">

</div>

<div class="input-group">

<label>Password</label>

<input
type="password"
name="password"
id="password"
placeholder="Enter Password">

</div>

<?php

if($error!="")
{
    echo "<div class='error'>$error</div>";
}

?>

<button type="submit">

<i class="fa-solid fa-right-to-bracket"></i>

Login

</button>

</form>

<div class="footer">

<p>

Authorized users only.

</p>

</div>

</div>

<div class="welcome">

<h1>Welcome to Hindi Karyashala Portal</h1>

<p>

Manage employee workshop attendance,
generate reports,
verify submissions,
and monitor activities through one secure platform.

</p>

</div>

</div>

</body>

</html>