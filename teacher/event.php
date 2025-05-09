<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

$events = mysqli_query($conn, "SELECT * FROM events ORDER BY start_date DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Events</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="dashboard-card">
            <h2>All College Events</h2>

            <table class="styled-table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($row = mysqli_fetch_assoc($events)) : ?>
                    <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td>
            
                    </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>

            </table>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
</body>
</html>
