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

// Handle subject selection
$selected_subject = isset($_POST['subject']) ? $_POST['subject'] : '';

// Fetch all subjects for the dropdown
$subjects_query = "SELECT DISTINCT subject FROM study_materials WHERE branch = '$branch' AND semester = $semester";
$subjects_result = mysqli_query($conn, $subjects_query);

// If a subject is selected, fetch materials for that subject
$materials_query = "SELECT * FROM study_materials WHERE branch = '$branch' AND semester = $semester";
if ($selected_subject) {
    $materials_query .= " AND subject = '$selected_subject'";
}
$materials_query .= " ORDER BY uploaded_at DESC";
$materials = mysqli_query($conn, $materials_query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Download Study Materials</title>
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
            <h2>Study Materials - <?= htmlspecialchars($branch) ?> / Semester <?= htmlspecialchars($semester) ?></h2>
            
            <!-- Subject Selection Dropdown -->
            <form method="POST" action="download_materials.php">
                <label for="subject">Choose a subject:</label>
                <select name="subject" id="subject">
                    <option value="">Select Subject</option>
                    <?php while ($row = mysqli_fetch_assoc($subjects_result)): ?>
                        <option value="<?= htmlspecialchars($row['subject']) ?>" <?= $selected_subject == $row['subject'] ? 'selected' : '' ?>><?= htmlspecialchars($row['subject']) ?></option>
                    <?php endwhile; ?>
                </select>
                <button type="submit">Filter</button>
            </form>

            <!-- Display Materials (Only if a subject is selected) -->
            <?php if ($selected_subject && mysqli_num_rows($materials) > 0): ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <th>File</th>
                            <th>Uploaded At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($materials)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['subject']) ?></td>
                                <td><a href="../uploads/<?= urlencode($row['filename']) ?>" target="_blank">Download</a></td>
                                <td><?= $row['uploaded_at'] ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php elseif ($selected_subject): ?>
                <p>No study materials available for the selected subject.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
