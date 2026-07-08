<div class="sidebar">

<?php

if($_SESSION['role']=="employee")
{

?>

<a href="dashboard.php">Dashboard</a>

<a href="view.php">View</a>

<a href="update.php">Update</a>

<a href="report.php">Get Report</a>

<a href="../logout.php">Logout</a>

<?php

}
else
{

?>

<a href="dashboard.php">Dashboard</a>

<a href="view.php">View</a>

<a href="update.php">Update</a>

<a href="report.php">Get Report</a>

<a href="approve.php">Approve Reports</a>

<a href="history.php">Report History</a>

<a href="../logout.php">Logout</a>

<?php

}

?>

</div>