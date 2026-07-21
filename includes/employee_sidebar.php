<div class="sidebar">

    <div class="menu-title">
        Main Menu
    </div>

    <a href="../employee/dashboard.php"
       class="<?php if(basename($_SERVER['PHP_SELF'])=="dashboard.php") echo "active"; ?>">

        <i class="fa-solid fa-gauge"></i>

        Dashboard

    </a>

    <div class="menu-title">
        Workshop Management
    </div>

    <a href="../employee/view.php"
       class="<?php
       if(
            basename($_SERVER['PHP_SELF'])=="view.php" ||
            basename($_SERVER['PHP_SELF'])=="update_workshop.php"
       ) echo "active";
       ?>">

        <i class="fa-solid fa-users"></i>

        View Employees

    </a>

    <div class="menu-title">
        Account
    </div>

    <a href="../logout.php">

        <i class="fa-solid fa-right-from-bracket"></i>

        Logout

    </a>

</div>