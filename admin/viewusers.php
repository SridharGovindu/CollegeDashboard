<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

// Filter values
$role_filter = $_GET['role'] ?? '';
$branch_filter = $_GET['branch'] ?? '';
$sem_filter = $_GET['semester'] ?? '';

// Base query
$query = "SELECT u.id, u.name, u.email, u.role, s.branch, s.semester 
          FROM users u
          LEFT JOIN students s ON u.id = s.user_id
          WHERE u.role != 'admin'";


// Apply filters
if ($role_filter) {
    $query .= " AND u.role = '$role_filter'";
}
if ($branch_filter) {
    $query .= " AND s.branch = '$branch_filter'";
}
if ($sem_filter) {
    $query .= " AND s.semester = '$sem_filter'";
}

$query .= " ORDER BY u.name ASC";
$users = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="dashboard-card">
            <h2>All Users</h2>

            <!-- Filter Form -->
            <form method="GET" style="margin-bottom: 20px;">
                <label>Role:</label>
                <select name="role">
                    <option value="">All</option>
                    <option value="student" <?= $role_filter == 'student' ? 'selected' : '' ?>>Student</option>
                    <option value="teacher" <?= $role_filter == 'teacher' ? 'selected' : '' ?>>Teacher</option>
                </select>

                <label>Branch:</label>
                <select name="branch">
                    <option value="">All</option>
                    <?php foreach (['CSE', 'ECE', 'EEE', 'MECH', 'CIVIL'] as $branch): ?>
                        <option value="<?= $branch ?>" <?= $branch_filter == $branch ? 'selected' : '' ?>><?= $branch ?></option>
                    <?php endforeach; ?>
                </select>

                <label>Semester:</label>
                <select name="semester">
                    <option value="">All</option>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>" <?= $sem_filter == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <button type="submit">Filter</button>
                <a href="view_users.php" style="margin-left: 10px;">Reset</a>
            </form>

            <!-- Users Table -->
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Branch</th>
                        <th>Semester</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($users) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($users)): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['name']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= ucfirst($row['role']) ?></td>
                                <td><?= $row['role'] === 'student' ? $row['branch'] : '-' ?></td>
                                <td><?= $row['role'] === 'student' ? $row['semester'] : '-' ?></td>
                                <td>
                                    <a href="edit_users.php?id=<?= $row['id'] ?>">Edit</a> |
                                    <a href="delete_user.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
