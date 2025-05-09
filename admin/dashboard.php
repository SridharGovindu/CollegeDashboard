<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

include('../db/config.php');

// Count users (students + teachers)
$user_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM users WHERE role='student' OR role='teacher'"))['count'];

// Count events
$event_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS count FROM events"))['count'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Compact Admin Dashboard CSS */
        body {
            margin: 0;
            font-family: 'Segoe UI', system-ui, sans-serif;
            background: #f5f7fa;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Compact Sidebar */
       /* Make the sidebar curved (on the right edge) */
.sidebar {
    width: 220px;
    background: #2c3e50;
    color: white;
    padding: 15px 0;
    position: fixed;
    height: 100vh;
    border-top-right-radius: 30px;
    border-bottom-right-radius: 30px;
    overflow: hidden;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1);
}

/* Style the logo container */
.sidebar-logo {
    text-align: center;
    padding: 10px 0 20px;
}

/* Style the logo image into a circle */
.sidebar-logo img {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #ecf0f1;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}


        .sidebar ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar li {
            padding: 12px 20px;
            margin: 2px 0;
            transition: background 0.2s;
        }

        .sidebar li:hover {
            background: #34495e;
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar i {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: 220px;
            padding: 20px;
            flex: 1;
        }

        .dashboard-card {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .header h1 {
            margin: 0 0 20px;
            font-size: 24px;
            color: #2c3e50;
        }

        /* Compact Cards */
        .cards {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .card {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            text-align: center;
            min-height: 140px;
            transition: transform 0.2s;
            text-decoration: none;
            display: block;
            color: inherit;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .card h3 {
            margin: 0 0 10px;
            font-size: 16px;
            color: #2c3e50;
        }

        .card p {
            font-size: 28px;
            margin: 10px 0;
            font-weight: bold;
            color: #2c3e50;
        }

        .users-card {
            background: #ffe9d6;
            border-left: 4px solid #f9a825;
        }

        .events-card {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
        }

        .progress-bar {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            margin-top: 10px;
        }

        .progress-bar > div {
            height: 100%;
            background: #4caf50;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
            .cards {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 480px) {
            .cards {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-logo">
            <img src="../assets/bussiness.png" alt="Logo">
        </div>
        <ul>
            <li><i class="fas fa-users"></i> <a href="add_user.php">Users</a></li>
            <li><i class="fas fa-calendar-plus"></i> <a href="add_event.php">Add Event</a></li>
            <li><i class="fas fa-calendar-alt"></i> <a href="viewevents.php">View Events</a></li>
            <li><i class="fas fa-user-friends"></i> <a href="viewusers.php">View Users</a></li>
            <li><i class="fas fa-sign-out-alt"></i> <a href="../logout.php">Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="dashboard-card">
            <div class="header">
                <h1>Dashboard</h1>
            </div>

            <div class="cards">
                <a href="viewusers.php" class="card users-card">
                    <h3>Users</h3>
                    <p><?php echo $user_count ?: '0'; ?></p>
                    <div class="progress-bar"><div style="width: 100%;"></div></div>
                </a>

                <a href="add_event.php" class="card events-card">
                    <h3>Add Events</h3>
                    <p>+</p>
                    <div class="progress-bar"><div style="width: 100%;"></div></div>
                </a>

                <a href="viewevents.php" class="card events-card">
                    <h3>View Events</h3>
                    <p><?php echo $event_count ?: '0'; ?></p>
                    <div class="progress-bar"><div style="width: 100%;"></div></div>
                </a>

                <a href="add_user.php" class="card users-card">
                    <h3>Add Users</h3>
                                        <p>+</p>

                    <div class="progress-bar"><div style="width: 100%;"></div></div>
                </a>
            </div>
        </div>
    </div>
</div>
</body>
</html>