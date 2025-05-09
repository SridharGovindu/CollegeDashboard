<?php
include('db/config.php');

// Default admin credentials
$admin_id = 'admin';
$admin_pass = 'ZmuhoSyz';
$hashed_pass = password_hash($admin_pass, PASSWORD_DEFAULT);

// Check if admin already exists
$check = mysqli_query($conn, "SELECT * FROM users WHERE email='$admin_id'");
if (mysqli_num_rows($check) > 0) {
    echo "Admin already exists.";
} else {
    $insert = "INSERT INTO users (name, email, password, role)
               VALUES ('Admin', '$admin_id', '$hashed_pass', 'admin')";

    if (mysqli_query($conn, $insert)) {
        echo "Default admin created successfully.";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>
