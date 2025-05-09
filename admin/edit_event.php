<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
$success = '';
$error = '';

$event = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM events WHERE id = $id"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!$title || !$start_date || !$end_date) {
        $error = "All fields are required.";
    } elseif ($start_date > $end_date) {
        $error = "End date must be after start date.";
    } else {
        $q = "UPDATE events SET title='$title', description='$description', start_date='$start_date', end_date='$end_date' WHERE id=$id";
        if (mysqli_query($conn, $q)) {
            $success = "Event updated.";
            header("Location: view_events.php");
            exit();
        } else {
            $error = "Update failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Event</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="form-card">
            <h2>Edit Event</h2>

            <?php if ($error): ?><p class="error"><?= $error ?></p><?php endif; ?>

            <form method="POST">
                <input type="text" name="title" value="<?= htmlspecialchars($event['title']) ?>" required><br>
                <textarea name="description" rows="4"><?= htmlspecialchars($event['description']) ?></textarea><br>
                <label>Start Date:</label><br>
                <input type="date" name="start_date" value="<?= $event['start_date'] ?>" required><br><br>
                <label>End Date:</label><br>
                <input type="date" name="end_date" value="<?= $event['end_date'] ?>" required><br><br>
                <button type="submit">Update Event</button>
            </form>

            <br><a href="view_events.php">← Back</a>
        </div>
    </div>
</div>
</body>
</html>
