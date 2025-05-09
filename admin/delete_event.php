<?php
include('../db/config.php');
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM events WHERE id = $id");

header("Location: view_events.php");
exit();
