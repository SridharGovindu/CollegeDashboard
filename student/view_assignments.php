<?php
session_start();
include('../db/config.php');

// Ensure student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get student's branch and semester
$student_query = "SELECT branch, semester FROM students WHERE user_id = $user_id";
$result = mysqli_query($conn, $student_query);

if ($result && mysqli_num_rows($result) === 1) {
    $stu = mysqli_fetch_assoc($result);
    $branch = $stu['branch'];
    $semester = $stu['semester'];
} else {
    die("Student information not found.");
}

$today = date('Y-m-d');

// Fetch assignments active for the student's branch and semester
$assignments_query = "SELECT * FROM assignments
                      WHERE branch = '$branch'
                      AND semester = $semester
                      AND start_date <= '$today'
                      AND end_date >= '$today'
                      ORDER BY created_at DESC";

$assignments = mysqli_query($conn, $assignments_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Assignments</title>
    <link rel="stylesheet" href="../assets/css/student-dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .assignments-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1rem;
            margin-top: 20px;
        }
        .assignment-card {
            background-color: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .assignment-card h3 {
            margin: 0 0 10px;
        }
        .btn-upload {
            display: inline-block;
            padding: 8px 12px;
            background-color: #2e8b57;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 10px;
        }
    </style>
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
            <h2>Active Assignments</h2>
            <?php if (mysqli_num_rows($assignments) > 0): ?>
                <div class="assignments-grid">
                    <?php while ($row = mysqli_fetch_assoc($assignments)): ?>
                        <div class="assignment-card">
                            <h3><?= htmlspecialchars($row['title']) ?></h3>
                            <p><?= htmlspecialchars($row['description']) ?></p>
                            <p><strong>Deadline:</strong> <?= htmlspecialchars($row['end_date']) ?></p>
                            <a href="upload_assignment.php?assignment_id=<?= $row['id'] ?>" class="btn-upload">Upload Assignment</a>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <p>No active assignments found for your branch and semester.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
