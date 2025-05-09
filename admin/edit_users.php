<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM users WHERE id = $id"));

if (!$user) {
    die("User not found.");
}

$student = ($user['role'] === 'student') ? 
    mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM students WHERE user_id = $id")) : null;

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $branch = $_POST['branch'] ?? '';
    $semester = $_POST['semester'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email.";
    } else {
        // Update users table
        $update = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id=$id";
        if (mysqli_query($conn, $update)) {
            // If student, update students table
            if ($role === 'student') {
                if ($student) {
                    mysqli_query($conn, "UPDATE students SET branch='$branch', semester='$semester' WHERE user_id=$id");
                } else {
                    mysqli_query($conn, "INSERT INTO students (user_id, branch, semester) VALUES ($id, '$branch', '$semester')");
                }
            } else {
                mysqli_query($conn, "DELETE FROM students WHERE user_id = $id");
            }

            $success = "User updated successfully.";
            header("Location: view_users.php");
            exit();
        } else {
            $error = "Failed to update.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="form-card">
            <h2>Edit User</h2>

            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

            <form method="POST">
                <input type="text" name="name" value="<?= htmlspecialchars($user['name']) ?>" required><br>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

                <select name="role" onchange="toggleStudentFields(this.value)">
                    <option value="">Select Role</option>
                    <option value="student" <?= $user['role'] === 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="teacher" <?= $user['role'] === 'teacher' ? 'selected' : '' ?>>Teacher</option>
                </select><br>

                <div id="student-fields" style="display: <?= $user['role'] === 'student' ? 'block' : 'none' ?>;">
                    <select name="branch">
                        <option value="">Select Branch</option>
                        <?php foreach (['CSE', 'ECE', 'EEE', 'MECH', 'CIVIL'] as $b): ?>
                            <option value="<?= $b ?>" <?= ($student && $student['branch'] == $b) ? 'selected' : '' ?>><?= $b ?></option>
                        <?php endforeach; ?>
                    </select><br>

                    <select name="semester">
                        <option value="">Select Semester</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?= $i ?>" <?= ($student && $student['semester'] == $i) ? 'selected' : '' ?>><?= $i ?></option>
                        <?php endfor; ?>
                    </select><br>
                </div>

                <button type="submit">Update User</button>
            </form>

            <script>
                function toggleStudentFields(role) {
                    document.getElementById('student-fields').style.display = (role === 'student') ? 'block' : 'none';
                }
            </script>

            <br><a href="viewusers.php">← Back</a>
        </div>
    </div>
</div>
</body>
</html>
