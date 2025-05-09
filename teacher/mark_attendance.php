<?php
session_start();
include('../db/config.php');

// Check if the user is a teacher
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$success = '';
$error = '';
$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // If attendance is being marked
    if (isset($_POST['mark_attendance'])) {
        $date = date('Y-m-d');
        $attendanceData = $_POST['attendance'] ?? [];
        $subject = $_POST['subject']; // Get the manually entered subject

        if (empty($subject)) {
            $error = "Subject name cannot be empty.";
        } else {
            foreach ($attendanceData as $student_id => $status) {
                $query = "INSERT INTO attendance (student_id, status, date, subject)
                          VALUES ($student_id, '$status', '$date', '$subject')";
                mysqli_query($conn, $query);
            }

            $success = "Attendance marked successfully.";
        }
    }

    // If filter is applied
    if (isset($_POST['filter'])) {
        $branch = $_POST['branch'];
        $semester = $_POST['semester'];

        $students = mysqli_query($conn, "
            SELECT u.id, u.name, s.branch, s.semester
            FROM users u
            JOIN students s ON u.id = s.user_id
            WHERE s.branch = '$branch' AND s.semester = '$semester'
        ");
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Mark Attendance</title>
    <link rel="stylesheet" href="../assets/css/teacher-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="sidebar">
        <div class="profile">
            <img src="../assets/images.png" alt="Teacher Icon">
            <h3><?= htmlspecialchars($_SESSION['name']) ?></h3>
        </div>
        <ul>
            <li><a href="dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="upload_material.php"><i class="fas fa-upload"></i> Upload Material</a></li>
            <li><a href="give_assignment.php"><i class="fas fa-tasks"></i> Give Assignment</a></li>
            <li><a href="mark_attendance.php"><i class="fas fa-check-square"></i> Mark Attendance</a></li>
            <li><a href="view_submissions.php"><i class="fas fa-tasks"></i> View Submissions</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h2>Mark Attendance</h2>

            <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

            <!-- Filter Form -->
            <form method="POST">
                <label>Branch:</label>
                <select name="branch" required>
                    <option value="">Select Branch</option>
                    <?php foreach (['CSE', 'ECE', 'EEE', 'MECH', 'CIVIL'] as $b): ?>
                        <option value="<?= $b ?>" <?= isset($_POST['branch']) && $_POST['branch'] == $b ? 'selected' : '' ?>><?= $b ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Semester:</label>
                <select name="semester" required>
                    <option value="">Select Semester</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>" <?= isset($_POST['semester']) && $_POST['semester'] == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit" name="filter">Load Students</button>
            </form>

            <!-- Attendance Form -->
            <?php if (!empty($students) && mysqli_num_rows($students) > 0): ?>
                <form method="POST">
                    <!-- Manually enter subject -->
                    <label>Subject Name:</label>
                    <input type="text" name="subject" placeholder="Enter subject name" required>

                    <table class="styled-table">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($student = mysqli_fetch_assoc($students)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['name']) ?></td>
                                    <td>
                                        <select name="attendance[<?= $student['id'] ?>]">
                                            <option value="Present">Present</option>
                                            <option value="Absent">Absent</option>
                                        </select>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <button type="submit" name="mark_attendance">Submit Attendance</button>
                </form>
            <?php elseif (isset($_POST['filter'])): ?>
                <p>No students found for the selected branch and semester.</p>
            <?php endif; ?>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
