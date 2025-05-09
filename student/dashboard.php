<?php
session_start();
include('../db/config.php');

// Ensure only students can access this page
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT u.name, s.branch, s.semester 
          FROM users u 
          JOIN students s ON u.id = s.user_id 
          WHERE u.id = $user_id";

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) === 1) {
    $row = mysqli_fetch_assoc($result);
    $name = $row['name'];
    $branch = $row['branch'];
    $semester = $row['semester'];
} else {
    $name = "Student";
    $branch = "N/A";
    $semester = "N/A";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../assets/css/student-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="profile">
            <img src="../assets/students.jpg" alt="Student Icon">
            <h3><?= htmlspecialchars($name) ?></h3>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="download_materials.php"><i class="fas fa-book"></i> Study Materials</a></li>
            <li><a href="view_assignments.php"><i class="fas fa-tasks"></i> Assignments</a></li>
            <li><a href="view_attendance.php"><i class="fas fa-calendar-check"></i> Attendance</a></li>
            <li><a href="event.php"><i class="fas fa-calendar-alt"></i>View Events</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h2>Welcome, <?= htmlspecialchars($name) ?>!</h2>
            <p><strong>Branch:</strong> <?= htmlspecialchars($branch) ?></p>
            <p><strong>Semester:</strong> <?= htmlspecialchars($semester) ?></p>
        </div>
    </div>
</div>
</body>
</html>
