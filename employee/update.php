<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] != "karyashala") {
    header("Location:../index.php");
    exit();
}

require_once("../config/db.php");

/* ---------------- SAVE ---------------- */

if(isset($_POST['save']))
{
    $ic_no = $_POST['ic_no'];
    $date2025 = $_POST['date2025'];
    $date2026 = $_POST['date2026'];

    // Delete old records
    mysqli_query($conn,"DELETE FROM workshops WHERE ic_no='$ic_no' AND YEAR(attended_date)=2025");
    mysqli_query($conn,"DELETE FROM workshops WHERE ic_no='$ic_no' AND YEAR(attended_date)=2026");

    // Insert new 2025 record
    if($date2025!="")
    {
        mysqli_query($conn,"
        INSERT INTO workshops(ic_no,title,attended_date)
        VALUES('$ic_no','Hindi Workshop','$date2025')
        ");
    }

    // Insert new 2026 record
    if($date2026!="")
    {
        mysqli_query($conn,"
        INSERT INTO workshops(ic_no,title,attended_date)
        VALUES('$ic_no','Hindi Workshop','$date2026')
        ");
    }

    echo "<script>
    alert('Attendance Updated Successfully');
    window.location='update.php';
    </script>";
}

/* ---------------- FETCH ---------------- */

$query="

SELECT

e.ic_no,
e.name,
e.designation,

MAX(CASE WHEN YEAR(w.attended_date)=2025 THEN attended_date END) AS d2025,

MAX(CASE WHEN YEAR(w.attended_date)=2026 THEN attended_date END) AS d2026

FROM employee e

LEFT JOIN workshops w

ON e.ic_no=w.ic_no

GROUP BY
e.ic_no,e.name,e.designation

ORDER BY e.ic_no

";

$result=mysqli_query($conn,$query);

?>

<!DOCTYPE html>

<html>

<head>

<meta charset="UTF-8">

<title>Update Attendance</title>

<link rel="stylesheet" href="../css/style.css">

<style>

.modal{
display:none;
position:fixed;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,.5);
}

.modal-content{
background:#fff;
width:450px;
margin:80px auto;
padding:20px;
border-radius:8px;
}

.close{
float:right;
font-size:24px;
cursor:pointer;
color:red;
}

</style>

</head>

<body>

<div class="navbar">

<h2>हिंदी कार्यशाला पोर्टल</h2>

<div>

Welcome,

<b><?php echo $_SESSION['name']; ?></b>

</div>

</div>

<div class="sidebar">

<a href="dashboard.php">Dashboard</a>

<a href="view.php">View</a>

<a href="update.php">Update</a>

<a href="report.php">Get Report</a>

<a href="../logout.php">Logout</a>

</div>

<div class="content">

<h2>Update Workshop Attendance</h2>

<br>

<table>

<tr>

<th>IC No</th>

<th>Name</th>

<th>Designation</th>

<th>Edit</th>

</tr>

<?php

while($row=mysqli_fetch_assoc($result))
{

?>

<tr>

<td><?= $row['ic_no']; ?></td>

<td><?= $row['name']; ?></td>

<td><?= $row['designation']; ?></td>

<td>

<button
onclick="openModal(
'<?= $row['ic_no']; ?>',
'<?= addslashes($row['name']); ?>',
'<?= addslashes($row['designation']); ?>',
'<?= $row['d2025']; ?>',
'<?= $row['d2026']; ?>'
)">
Edit
</button>

</td>

</tr>

<?php

}

?>

</table>

</div>

<!-- Modal -->

<div id="editModal" class="modal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2>Edit Attendance</h2>

<form method="POST">

<label>IC Number</label>

<input
type="text"
name="ic_no"
id="ic_no"
readonly>

<label>Name</label>

<input
type="text"
id="name"
readonly>

<label>Designation</label>

<input
type="text"
id="designation"
readonly>

<label>Workshop 2025</label>

<input
type="date"
name="date2025"
id="date2025">

<label>Workshop 2026</label>

<input
type="date"
name="date2026"
id="date2026">

<br><br>

<button
type="submit"
name="save"
class="btn btn-save">

Save

</button>

</form>

</div>

</div>

<script>

function openModal(ic,name,desig,d2025,d2026)
{
document.getElementById("editModal").style.display="block";

document.getElementById("ic_no").value=ic;
document.getElementById("name").value=name;
document.getElementById("designation").value=desig;

document.getElementById("date2025").value=d2025;
document.getElementById("date2026").value=d2026;
}

function closeModal()
{
document.getElementById("editModal").style.display="none";
}

window.onclick=function(e)
{
let modal=document.getElementById("editModal");

if(e.target==modal)
modal.style.display="none";
}

</script>

</body>

</html>