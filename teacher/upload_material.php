<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];
$success = $error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $subject = mysqli_real_escape_string($conn, $_POST['subject']);
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];
    $file = $_FILES['material'];

    $allowed_ext = ['pdf', 'docx', 'doc', 'pptx', 'ppt'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, $allowed_ext)) {
        $error = "Invalid file format.";
    } else {
        $filename = time() . '_' . basename($file['name']);
        $upload_dir = "../uploads/";
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $target = $upload_dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            $query = "INSERT INTO study_materials (teacher_id, subject, filename, branch, semester)
                      VALUES ($teacher_id, '$subject', '$filename', '$branch', $semester)";
            if (mysqli_query($conn, $query)) {
                $success = "Material uploaded successfully.";
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
    <title>Upload Study Material</title>
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
            <h2>Upload Study Material</h2>

            <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <label>Subject:</label><br>
                <input type="text" name="subject" required><br><br>

                <label>Upload File:</label><br>
                <input type="file" name="material" accept=".pdf,.doc,.docx,.ppt,.pptx" required><br><br>

                <label>Branch:</label><br>
                <select name="branch" required>
                    <option value="">--Select Branch--</option>
                    <option value="CSE">CSE</option>
                    <option value="ECE">ECE</option>
                    <option value="EEE">EEE</option>
                    <option value="MECH">MECH</option>
                    <option value="CIVIL">CIVIL</option>
                </select><br><br>

                <label>Semester:</label><br>
                <select name="semester" required>
                    <option value="">--Select Semester--</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select><br><br>

                <button type="submit">Upload</button>
            </form>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
