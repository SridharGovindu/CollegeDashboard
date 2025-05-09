<?php
session_start();
include('../db/config.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$success = $error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $branch = $_POST['branch'];
    $semester = $_POST['semester'];

    if ($end_date < $start_date) {
        $error = "End date cannot be earlier than start date.";
    } else {
        $file = $_FILES['assignment_file'];
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
                $query = "INSERT INTO assignments (title, description, filename, branch, semester, start_date, end_date)
                          VALUES ('$title', '$description', '$filename', '$branch', '$semester', '$start_date', '$end_date')";
                if (mysqli_query($conn, $query)) {
                    $success = "Assignment posted successfully.";
                } else {
                    $error = "Database error: " . mysqli_error($conn);
                }
            } else {
                $error = "File upload failed.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Give Assignment</title>
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
            <h2>Give Assignment</h2>

            <?php if ($success): ?><p class="success"><?= $success ?></p><?php endif; ?>
            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 800px; margin-bottom: 20px;">
                    <h3>Assign To</h3>
                    <p><strong>Branch</strong></p>
                    <select name="branch" required>
                        <option value="">--Select Branch--</option>
                        <option value="CSE">CSE</option>
                        <option value="ECE">ECE</option>
                        <option value="EEE">EEE</option>
                        <option value="MECH">MECH</option>
                        <option value="CIVIL">CIVIL</option>
                    </select>

                    <p><strong>Semester</strong></p>
                    <select name="semester" required>
                        <option value="">--Select Semester--</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 800px; margin-bottom: 20px;">
                    <h3>Assignment Info</h3>
                    <label>Title:</label>
                    <input type="text" name="title" required>

                    <label>Description:</label>
                    <textarea name="description" rows="4" required></textarea>
                    <br><br>
                    <label>Upload File:</label>
                    <input type="file" name="assignment_file" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                </div>

                <div style="border: 1px solid #ccc; padding: 20px; border-radius: 8px; max-width: 800px;">
                    <h3>Set Availability</h3>
                    <label>Available From:</label>
                    <input type="date" name="start_date" id="start_date" required>

                    <label>Due Date (Until):</label>
                    <input type="date" name="end_date" id="end_date" required>
                </div>

                <br><br>
                <button type="submit" style="padding: 10px 20px;">Post Assignment</button>
            </form>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>

<!-- JavaScript validation for date consistency -->
<script>
    const form = document.querySelector("form");
    const startInput = document.getElementById("start_date");
    const endInput = document.getElementById("end_date");

    startInput.addEventListener("change", () => {
        endInput.min = startInput.value;
    });

    form.addEventListener("submit", function(event) {
        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);

        if (endDate < startDate) {
            alert("End date cannot be earlier than start date.");
            event.preventDefault();
        }
    });
</script>
</body>
</html>
