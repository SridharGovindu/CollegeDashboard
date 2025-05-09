<?php
include('db/config.php');
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $query = "SELECT * FROM users WHERE email = '$email'";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                // Store session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['name'] = $user['name'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: admin/dashboard.php");
                } elseif ($user['role'] === 'teacher') {
                    header("Location: teacher/dashboard.php");
                } elseif ($user['role'] === 'student') {
                    $sid = $user['id'];
                    $s = mysqli_query($conn, "SELECT branch, semester FROM students WHERE user_id = $sid");
                    if ($s && mysqli_num_rows($s)) {
                        $stu = mysqli_fetch_assoc($s);
                        $_SESSION['branch'] = $stu['branch'];
                        $_SESSION['semester'] = $stu['semester'];
                    }
                    header("Location: student/dashboard.php");
                    exit();
                }
                 else {
                    $error = "User role is invalid.";
                }
                exit();
            } else {
                $error = "Incorrect password.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - College Dashboard</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-page-wrapper">
    <div class="login-container card-float">
        <div class="login-left">
            <h2>LOGIN</h2>
            <p>Welcome to the College Dashboard</p>

            <form method="POST" action="">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <button type="submit">Login Now</button>
            </form>
        </div>
        <div class="login-right">
            <img src="assets/logo.png" alt="Logo" class="login-logo">
        </div>
    </div>
</div>

<?php if ($error): ?>
<script>
    alert("<?= htmlspecialchars($error) ?>");
</script>
<?php endif; ?>
</body>
</html>
