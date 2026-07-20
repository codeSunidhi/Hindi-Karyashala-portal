<?php

$currentPage = basename($_SERVER['PHP_SELF']);

$currentFolder = basename(dirname($_SERVER['PHP_SELF']));

if($currentFolder=="admin")
{
    $adminPath = "";
    $employeePath = "../employee/";
}
else
{
    $adminPath = "../admin/";
    $employeePath = "";
}

?>

<div class="sidebar">

    <div class="sidebar-logo">

        <i class="fa-solid fa-book-open" style="font-size:55px;color:white;"></i>

        <h2>Karyashala</h2>

    </div>

    <ul>

<?php

if($_SESSION['role']=="Admin")
{

?>

        <li>

            <a href="dashboard.php"
            class="<?php if($currentPage=="dashboard.php") echo "active"; ?>">

                <i class="fa-solid fa-house"></i>

                <span>Dashboard</span>

            </a>

        </li>

        <li>

            <a href="view.php"
            class="<?php if($currentPage=="view.php") echo "active"; ?>">

                <i class="fa-solid fa-users"></i>

                <span>View Employees</span>

            </a>

        </li>

        <li>

            <a href="update.php"
            class="<?php if($currentPage=="update.php") echo "active"; ?>">

                <i class="fa-solid fa-user-pen"></i>

                <span>Update Employees</span>

            </a>

        </li>

        <li>

            <a href="add_employee.php"
            class="<?php if($currentPage=="add_employee.php") echo "active"; ?>">

                <i class="fa-solid fa-user-plus"></i>

                <span>Add Employee</span>

            </a>

        </li>

        <li>

            <a href="reports.php"
            class="<?php if($currentPage=="reports.php") echo "active"; ?>">

                <i class="fa-solid fa-file-lines"></i>

                <span>Reports</span>

            </a>

        </li>

        <li>

            <a href="history.php"
            class="<?php if($currentPage=="history.php") echo "active"; ?>">

                <i class="fa-solid fa-clock-rotate-left"></i>

                <span>History</span>

            </a>

        </li>

<?php

}

else

{

?>

        <li>

            <a href="dashboard.php"
            class="<?php if($currentPage=="dashboard.php") echo "active"; ?>">

                <i class="fa-solid fa-house"></i>

                <span>Dashboard</span>

            </a>

        </li>

        <li>

            <a href="view.php"
            class="<?php if($currentPage=="view.php") echo "active"; ?>">

                <i class="fa-solid fa-users"></i>

                <span>View Employees</span>

            </a>

        </li>

        <li>

            <a href="update.php"
            class="<?php if($currentPage=="update.php") echo "active"; ?>">

                <i class="fa-solid fa-user-pen"></i>

                <span>Update</span>

            </a>

        </li>

<?php

}

?>

    </ul>

    <div class="sidebar-footer">

        © 2026

    </div>

</div>