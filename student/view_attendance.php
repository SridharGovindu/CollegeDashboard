<?php
session_start();
include('../db/config.php');

// Ensure student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch all attendance records into an array
$attendance_data = [];
$attendance_query = "SELECT * FROM attendance WHERE student_id = $user_id ORDER BY date DESC";
$attendance_result = mysqli_query($conn, $attendance_query);

while ($row = mysqli_fetch_assoc($attendance_result)) {
    $attendance_data[] = $row;
}

// Initialize summary array
$attendance_summary = [];

// Process summary
foreach ($attendance_data as $row) {
    $subject = $row['subject'];
    $status = strtolower($row['status']);

    if (!isset($attendance_summary[$subject])) {
        $attendance_summary[$subject] = ['total_classes' => 0, 'attended_classes' => 0];
    }

    $attendance_summary[$subject]['total_classes']++;

    if ($status === 'present') {
        $attendance_summary[$subject]['attended_classes']++;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Attendance</title>
    <link rel="stylesheet" href="../assets/css/student-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="profile">
            <img src="../assets/students.jpg" alt="Student Icon">
            <h3><?= htmlspecialchars($_SESSION['name']) ?></h3>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="download_materials.php"><i class="fas fa-book"></i> Study Materials</a></li>
            <li><a href="view_assignments.php"><i class="fas fa-tasks"></i> Assignments</a></li>
            <li><a href="view_attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h2>Your Attendance</h2>

            <?php if (count($attendance_data) > 0): ?>
                <!-- Attendance Record Table -->
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Subject</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_data as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['date']) ?></td>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><?= ucfirst(htmlspecialchars($row['status'])) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

                <br><br>

                <!-- Attendance Summary Table -->
                <h3>Attendance Percentage by Subject</h3>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>Total Classes</th>
                            <th>Attended Classes</th>
                            <th>Attendance Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendance_summary as $subject => $data): ?>
                            <?php
                                $total_classes = $data['total_classes'];
                                $attended_classes = $data['attended_classes'];
                                $attendance_percentage = ($total_classes > 0) ? ($attended_classes / $total_classes) * 100 : 0;
                            ?>
                            <tr>
                                <td><?= htmlspecialchars($subject) ?></td>
                                <td><?= $total_classes ?></td>
                                <td><?= $attended_classes ?></td>
                                <td><?= number_format($attendance_percentage, 2) ?>%</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            <?php else: ?>
                <p>No attendance records found.</p>
            <?php endif; ?>

        </div>
    </div>
</div>
</body>
</html>
