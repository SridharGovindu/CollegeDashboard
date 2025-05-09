<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = $_POST['role'];
    $semester = $_POST['semester'] ?? null;
    $branch = $_POST['branch'] ?? null;

    // Validate email format and Gmail domain
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !str_ends_with($email, '@gmail.com')) {
        $error = "Please enter a valid Gmail address.";
    } else {
        // Check if email already exists
        $check = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");
        if (mysqli_num_rows($check) > 0) {
            $error = "User already exists with that email.";
        } else {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insert user
            $query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', '$role')";
            if (mysqli_query($conn, $query)) {
                $user_id = mysqli_insert_id($conn);

                // Insert into student or teacher table
                if ($role == 'student') {
                    mysqli_query($conn, "INSERT INTO students (user_id, reg_no, course, semester, branch) 
                        VALUES ($user_id, 'N/A', 'N/A', '$semester', '$branch')");
                } else if ($role == 'teacher') {
                    mysqli_query($conn, "INSERT INTO teachers (user_id, subject) VALUES ($user_id, 'N/A')");
                }

                $success = "User added successfully.";
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add User - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="form-card">
            <h2>Add Student / Teacher</h2>

            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php elseif ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="name" placeholder="Full Name" required><br>
                <input type="email" name="email" placeholder="Email Address" required><br>
                <input type="password" name="password" placeholder="Password" required><br>

                <select name="role" required onchange="toggleStudentFields(this.value)">
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                </select><br>

                <div id="student-fields" style="display:none;">
                    <select name="semester">
                        <option value="">Select Semester</option>
                        <?php for ($i = 1; $i <= 8; $i++): ?>
                            <option value="<?php echo $i; ?>">Semester <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select><br>

                    <select name="branch">
                        <option value="">Select Branch</option>
                        <option value="CSE">Computer Science</option>
                        <option value="ECE">Electronics</option>
                        <option value="EEE">Electrical</option>
                        <option value="MECH">Mechanical</option>
                        <option value="CIVIL">Civil</option>
                    </select><br>
                </div>

                <button type="submit">Add User</button>
            </form>

            <script>
                function toggleStudentFields(role) {
                    document.getElementById('student-fields').style.display = role === 'student' ? 'block' : 'none';
                }

                // Auto-show student fields if form was submitted with role = student
                window.onload = function () {
                    const role = document.querySelector('select[name="role"]').value;
                    toggleStudentFields(role);
                };
            </script>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
