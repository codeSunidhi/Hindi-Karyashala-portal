<?php
$page = basename($_SERVER["PHP_SELF"]);
$isAdmin = $_SESSION["role"] == "Admin";
?>

<div class="sidebar">

    <a href="dashboard.php" class="<?php echo $page=="dashboard.php" ? "active" : ""; ?>">
        <i class="fa-solid fa-house"></i>
        Dashboard
    </a>

<?php if($isAdmin){ ?>

    <p class="menu-title">Employee Management</p>

    <a href="add_employee.php" class="<?php echo $page=="add_employee.php" ? "active" : ""; ?>">
        <i class="fa-solid fa-user-plus"></i>
        Add Employee
    </a>

<?php } ?>

    <a href="view.php" class="<?php echo $page=="view.php" ? "active" : ""; ?>">
        <i class="fa-solid fa-users"></i>
        View Employees
    </a>

<?php if($isAdmin){ ?>

    <p class="menu-title">Reports</p>

    <a href="reports.php" class="<?php echo $page=="reports.php" ? "active" : ""; ?>">
        <i class="fa-solid fa-file-lines"></i>
        Pending Reports
    </a>

    <a href="history.php" class="<?php echo $page=="history.php" ? "active" : ""; ?>">
        <i class="fa-solid fa-clock-rotate-left"></i>
        Verification History
    </a>

<?php } ?>

</div>