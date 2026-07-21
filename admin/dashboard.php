<?php
include "../includes/auth.php";
include "../config/db.php";

$totalEmployees = $conn->query("SELECT COUNT(*) total FROM employees WHERE ic_number NOT IN (1001,1002)")->fetch_assoc()['total'];

$pendingReports = $conn->query("SELECT COUNT(*) total FROM reports WHERE status='Pending'")->fetch_assoc()['total'];

$verifiedReports = $conn->query("SELECT COUNT(*) total FROM reports WHERE status='Verified'")->fetch_assoc()['total'];

$attended = $conn->query("SELECT COUNT(*) total FROM workshops WHERE attendance_status='Attended'")->fetch_assoc()['total'];

$pendingAttendance = $conn->query("SELECT COUNT(*) total FROM workshops WHERE attendance_status='Pending'")->fetch_assoc()['total'];

$absent = $conn->query("SELECT COUNT(*) total FROM workshops WHERE attendance_status='Absent'")->fetch_assoc()['total'];

$attendance = [];
$result = $conn->query("SELECT attendance_status, COUNT(*) total FROM workshops GROUP BY attendance_status");

while ($row = $result->fetch_assoc()) {
    $attendance[$row["attendance_status"]] = $row["total"];
}

$attendedCount = $attendance["Attended"] ?? 0;
$pendingCount = $attendance["Pending"] ?? 0;
$absentCount = $attendance["Absent"] ?? 0;

$years = [];
$yearTotals = [];

$result = $conn->query("SELECT workshop_year, COUNT(*) total FROM workshops GROUP BY workshop_year ORDER BY workshop_year");

while ($row = $result->fetch_assoc()) {
    $years[] = $row["workshop_year"];
    $yearTotals[] = $row["total"];
}

$recentReports = $conn->query("
    SELECT reports.report_name,
           employees.name,
           reports.workshop_year,
           reports.status,
           reports.generated_date
    FROM reports
    INNER JOIN employees
    ON reports.employee_ic = employees.ic_number
    ORDER BY reports.generated_date DESC
    LIMIT 5
");

$activities = $conn->query("
    SELECT activity,
           activity_date
    FROM activity_log
    ORDER BY activity_date DESC
    LIMIT 5
");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="../css/layout.css">
    <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>

<div class="overlay">

<?php
include "../includes/navbar.php";
include "../includes/sidebar.php";
?>

<div class="main">

<div class="page-header">
    <h1>Admin Dashboard</h1>
    <p>
        Monitor employee workshops, attendance records,
        reports, and verification activities from one place.
    </p>
</div>

<div class="cards">

<div class="card employees">
    <div class="card-icon">
        <i class="fa-solid fa-users"></i>
    </div>
    <h3>Total Employees</h3>
    <h2><?php echo $totalEmployees; ?></h2>
    <p>Registered Employees</p>
</div>

<div class="card pending-report">
    <div class="card-icon">
        <i class="fa-solid fa-file-circle-exclamation"></i>
    </div>
    <h3>Pending Reports</h3>
    <h2><?php echo $pendingReports; ?></h2>
    <p>Awaiting Verification</p>
</div>

<div class="card verified">
    <div class="card-icon">
        <i class="fa-solid fa-circle-check"></i>
    </div>
    <h3>Verified Reports</h3>
    <h2><?php echo $verifiedReports; ?></h2>
    <p>Completed Reports</p>
</div>

<div class="card attended">
    <div class="card-icon">
        <i class="fa-solid fa-user-check"></i>
    </div>
    <h3>Attended</h3>
    <h2><?php echo $attended; ?></h2>
    <p>Workshop Attendance</p>
</div>

<div class="card pending">
    <div class="card-icon">
        <i class="fa-solid fa-hourglass-half"></i>
    </div>
    <h3>Pending Attendance</h3>
    <h2><?php echo $pendingAttendance; ?></h2>
    <p>Waiting For Update</p>
</div>

<div class="card absent">
    <div class="card-icon">
        <i class="fa-solid fa-user-xmark"></i>
    </div>
    <h3>Absent</h3>
    <h2><?php echo $absent; ?></h2>
    <p>Workshop Absence</p>
</div>

</div>
<div class="chart-grid">

    <div class="section">
        <h2>Attendance Overview</h2>
        <div class="pie-wrapper">
    <canvas id="attendanceChart"></canvas>
</div>
    </div>

    <div class="section">
        <h2>Workshop Statistics</h2>
        <canvas id="yearChart"></canvas>
    </div>

</div>
<div class="dashboard-grid">

    <div class="section">

        <h2>Recent Reports</h2>

        <table class="dashboard-table">

            <tr>
                <th>Employee</th>
                <th>Year</th>
                <th>Status</th>
            </tr>

            <?php if ($recentReports->num_rows > 0) { ?>

                <?php while ($row = $recentReports->fetch_assoc()) { ?>

                    <tr>
                        <td><?php echo htmlspecialchars($row["name"]); ?></td>
                        <td><?php echo htmlspecialchars($row["workshop_year"]); ?></td>
                        <td>
                            <span class="<?php echo strtolower($row["status"]); ?>">
                                <?php echo htmlspecialchars($row["status"]); ?>
                            </span>
                        </td>
                    </tr>

                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="3">No reports available.</td>
                </tr>

            <?php } ?>

        </table>

    </div>

    <div class="section">

        <h2>Latest Activities</h2>

        <?php if ($activities->num_rows > 0) { ?>

            <?php while ($row = $activities->fetch_assoc()) { ?>

                <div class="activity-item">

                    <div class="activity-dot"></div>

                    <div>

                        <h4><?php echo htmlspecialchars($row["activity"]); ?></h4>

                        <p><?php echo date("d M Y h:i A", strtotime($row["activity_date"])); ?></p>

                    </div>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p class="empty-text">No activities available.</p>

        <?php } ?>

    </div>

</div>
</div>

</div>

<script>
const attendanceChart = document.getElementById("attendanceChart");

new Chart(attendanceChart,{
    type:"pie",
    data:{
        labels:["Attended","Pending","Absent"],
        datasets:[{
            data:[
                <?php echo $attendedCount; ?>,
                <?php echo $pendingCount; ?>,
                <?php echo $absentCount; ?>
            ],
            backgroundColor:[
                "#22C55E",
                "#F59E0B",
                "#EF4444"
            ],
            radius:"100%"
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{
                labels:{
                    color:"#fff"
                }
            }
        }
    }
});

const yearChart=document.getElementById("yearChart");

new Chart(yearChart,{
    type:"bar",
    data:{
        labels:<?php echo json_encode($years); ?>,
        datasets:[{
            label:"Employees",
            data:<?php echo json_encode($yearTotals); ?>,
            backgroundColor:"#3B82F6",
            borderRadius:8
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{
                labels:{
                    color:"#fff"
                }
            }
        },
        scales:{
            x:{
                ticks:{
                    color:"#fff"
                },
                grid:{
                    color:"rgba(255,255,255,.1)"
                }
            },
            y:{
                beginAtZero:true,
                ticks:{
                    color:"#fff"
                },
                grid:{
                    color:"rgba(255,255,255,.1)"
                }
            }
        }
    }
});
</script>

</body>

</html>