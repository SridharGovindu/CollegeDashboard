<?php
session_start();
include('../db/config.php');

// Ensure teacher is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'teacher') {
    header("Location: ../login.php");
    exit();
}

function calculate_similarity_score($file1, $file2) {
    $content1 = strtolower(strip_tags(file_get_contents($file1)));
    $content2 = strtolower(strip_tags(file_get_contents($file2)));
    similar_text($content1, $content2, $percent);
    return round($percent, 2);
}

$title_filter = isset($_GET['title']) ? trim(mysqli_real_escape_string($conn, $_GET['title'])) : '';
$files = [];
$scores = [];

if ($title_filter !== '') {
    $query = "SELECT a.*, u.name FROM assignment_submissions a 
              JOIN users u ON a.student_id = u.id 
              WHERE BINARY a.title LIKE BINARY '%$title_filter%' 
              ORDER BY a.submitted_at DESC";
    $submissions = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_assoc($submissions)) {
        $files[] = $row;
    }

    // Calculate similarity scores
    for ($i = 0; $i < count($files); $i++) {
        for ($j = $i + 1; $j < count($files); $j++) {
            $f1 = "../uploads/submissions/" . $files[$i]['filename'];
            $f2 = "../uploads/submissions/" . $files[$j]['filename'];

            if (file_exists($f1) && file_exists($f2)) {
                $score = calculate_similarity_score($f1, $f2);
                $scores[$files[$i]['id']][$files[$j]['id']] = $score;
                $scores[$files[$j]['id']][$files[$i]['id']] = $score;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Submissions</title>
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
            <h2>Assignment Submissions with Similarity Check</h2>
            <form method="GET" style="margin-bottom: 20px;">
                <label for="title">Filter by Assignment Title:</label>
                <input type="text" name="title" id="title" placeholder="e.g. SDE" value="<?= htmlspecialchars($title_filter) ?>">
                <button type="submit">Search</button>
            </form>

            <?php if ($title_filter === ''): ?>
                <p>Please enter an assignment title to view submissions.</p>
            <?php elseif (empty($files)): ?>
                <p>No submissions found for "<?= htmlspecialchars($title_filter) ?>".</p>
            <?php else: ?>
                <table class="styled-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Title</th>
                            <th>File</th>
                            <th>Submitted At</th>
                            <th>Similarity (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($files as $row): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['name']) ?></td>
                            <td><?= htmlspecialchars($row['title']) ?></td>
                            <td><a href="../uploads/<?= urlencode($row['filename']) ?>" target="_blank">Download</a></td>
                            <td><?= $row['submitted_at'] ?></td>
                            <td>
                                <?php
                                $max_sim = 0;
                                foreach ($scores[$row['id']] ?? [] as $other_id => $val) {
                                    if ($val > $max_sim) $max_sim = $val;
                                }
                                echo $max_sim . '%';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
