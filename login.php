<?php

session_start();
require_once("config/db.php");

// If accessed directly
if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit();
}

// Get Form Data
$ic_no = trim($_POST['ic_no']);
$password = trim($_POST['password']);
$role = trim($_POST['role']);

// Validate
if (empty($ic_no) || empty($password) || empty($role)) {
    echo "<script>
            alert('Please fill all fields.');
            window.location='index.php';
          </script>";
    exit();
}

// Decide which table to use
switch($role)
{
    case "admin":
        $table = "admin";
        $dashboard = "admin/dashboard.php";
        break;

    case "karyashala":
        $table = "karyashala_admin";
        $dashboard = "employee/dashboard.php";
        break;

    default:
        echo "<script>
                alert('Invalid Role Selected');
                window.location='index.php';
              </script>";
        exit();
}

// Login Query
$sql = "SELECT * FROM $table WHERE ic_no = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ic_no);
$stmt->execute();

$result = $stmt->get_result();

if($result->num_rows > 0)
{
    $user = $result->fetch_assoc();

    // Plain-text password comparison
    // (We'll convert to password_hash later)
    if($password == $user['password'])
    {
        $_SESSION['loggedin'] = true;
        $_SESSION['ic_no'] = $user['ic_no'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $role;

        header("Location: $dashboard");
        exit();
    }
    else
    {
        echo "<script>
                alert('Incorrect Password');
                window.location='index.php';
              </script>";
    }
}
else
{
    echo "<script>
            alert('IC Number not found.');
            window.location='index.php';
          </script>";
}

$stmt->close();
$conn->close();

?>