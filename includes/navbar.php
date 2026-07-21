<?php
$stmt = $conn->prepare("SELECT name FROM employees WHERE ic_number=?");
$stmt->bind_param("i", $_SESSION["ic_number"]);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>

<nav class="navbar">

    <div class="logo">
        <img src="../images/logo.png" alt="Logo">

        <div>
            <h2>Hindi Karyashala Portal</h2>
            <p>Workshop Management System</p>
        </div>
    </div>

    <div class="user">

        <div class="user-info">
            <h4><?php echo htmlspecialchars($user["name"]); ?></h4>
            <span><?php echo htmlspecialchars($_SESSION["role"]); ?></span>
        </div>

        <a href="../logout.php" class="logout">
            <i class="fa-solid fa-right-from-bracket"></i>
            Logout
        </a>

    </div>

</nav>