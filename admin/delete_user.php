<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

// Remove from students table if applicable
mysqli_query($conn, "DELETE FROM students WHERE user_id = $id");

// Remove from teachers table if applicable
mysqli_query($conn, "DELETE FROM teachers WHERE user_id = $id");

// Remove from users table
mysqli_query($conn, "DELETE FROM users WHERE id = $id");

header("Location: viewusers.php");
exit();
