<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$name = $_SESSION['name'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Teacher Dashboard</title>
    <link rel="stylesheet" href="../assets/css/teacher-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="profile">
            <img src="../assets/images.png" alt="Teacher Icon">
            <h3><?= htmlspecialchars($name) ?></h3>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="upload_material.php"><i class="fas fa-upload"></i> Upload Material</a></li>
            <li><a href="give_assignment.php"><i class="fas fa-tasks"></i> Give Assignment</a></li>
            <li><a href="mark_attendance.php"><i class="fas fa-check-square"></i> Mark Attendance</a></li>
            <li><a href="view_submissions.php"><i class="fas fa-tasks"></i> View Submissions</a></li>
            <li><a href="event.php"><i class="fas fa-calendar-alt"></i>View Events</a></li>

            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h2>Welcome, <?= htmlspecialchars($name) ?> 👋</h2>
            <p>Select an option from the sidebar to get started.</p>
        </div>
    </div>
</div>
</body>
</html>
