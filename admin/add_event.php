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
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if (!$title || !$start_date || !$end_date) {
        $error = "Please fill in all required fields.";
    } elseif ($start_date > $end_date) {
        $error = "End date cannot be earlier than start date.";
    } else {
        $query = "INSERT INTO events (title, description, start_date, end_date) 
                  VALUES ('$title', '$description', '$start_date', '$end_date')";
        if (mysqli_query($conn, $query)) {
            $success = "Event added successfully.";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Event - Admin</title>
    <link rel="stylesheet" href="../assets/css/admin-dashboard.css">
</head>
<body>
<div class="wrapper">
    <div class="main-content">
        <div class="form-card">
            <h2>Add New Event</h2>

            <?php if ($success): ?>
                <p class="success"><?php echo $success; ?></p>
            <?php elseif ($error): ?>
                <p class="error"><?php echo $error; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <input type="text" name="title" placeholder="Event Title" required><br>
                <textarea name="description" placeholder="Event Description" rows="4"></textarea><br>

                <label for="start_date">Start Date:</label><br>
                <input type="date" name="start_date" id="start_date" required><br><br>

                <label for="end_date">End Date:</label><br>
                <input type="date" name="end_date" id="end_date" required><br><br>


                <button type="submit">Add Event</button>
            </form>

            <br><a href="dashboard.php">← Back to Dashboard</a>
        </div>
    </div>
</div>
<script>
document.getElementById('start_date').addEventListener('change', function () {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    endDateInput.min = startDate;

    // Optional: auto-fill end date if it's blank or before start
    if (!endDateInput.value || endDateInput.value < startDate) {
        endDateInput.value = startDate;
    }
});
</script>
</body>

</html>


