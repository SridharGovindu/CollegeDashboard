<?php
session_start();
include('../db/config.php');

// Ensure student is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'student') {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $assignment_title = mysqli_real_escape_string($conn, $_POST['title']);
    $file = $_FILES['assignment_file'];

    $allowed_ext = ['pdf', 'docx'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        $error = "Only PDF and DOCX files are allowed.";
    } else {
        $filename = time() . '_' . basename($file['name']);
        $target = "../uploads/submissions/" . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $query = "INSERT INTO assignment_submissions (student_id, title, filename) 
                      VALUES ($user_id, '$assignment_title', '$filename')";
            if (mysqli_query($conn, $query)) {
                $success = "Assignment uploaded successfully.";
            } else {
                $error = "Database error: " . mysqli_error($conn);
            }
        } else {
            $error = "Failed to upload the file.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Assignment</title>
    <link rel="stylesheet" href="../assets/css/student-dashboard.css">
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
            <li><a href="upload_assignment.php"><i class="fas fa-tasks"></i> Upload Assignment</a></li>
            <li><a href="../logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="dashboard-card">
            <h2>Upload Your Assignment</h2>

            <?php if ($success): ?>
                <p class="success">✅ <?= $success ?></p>
            <?php elseif ($error): ?>
                <p class="error">❌ <?= $error ?></p>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label>Assignment Title:</label><br>
                <input type="text" name="title" required><br><br>

                <label>Upload File (PDF/DOCX only):</label><br>
                <input type="file" name="assignment_file" accept=".pdf,.docx" required><br><br>

                <button type="submit">Upload Assignment</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>