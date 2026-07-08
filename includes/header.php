<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['loggedin'])) {
    header("Location: ../index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Hindi Karyashala Portal</title>

<link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="navbar">

    <h2>हिंदी कार्यशाला पोर्टल</h2>

    <div class="user-name">

        Welcome,

        <strong><?php echo $_SESSION['name']; ?></strong>

        |

        <?php echo ucfirst($_SESSION['role']); ?>

    </div>

</div>