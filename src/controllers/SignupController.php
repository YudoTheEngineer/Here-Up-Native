<?php
// Session Validation
session_start();

if (isset($_SESSION["session"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Daatabase Connection
include "../../env.php";

// Get Sign Up Data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];

// Hash the Password
$hash = password_hash($password, PASSWORD_DEFAULT);

$is_active = 1;

// Insert to Database Variable
$data = "
INSERT INTO user (username, email, password, is_active) 
VALUES ('$username', '$email', '$hash', $is_active)
";

// Create New User with Check if there is an Error
if (mysqli_query($connection, $data)) {

    // Head to Dashbord Page
    header("Location: ../views/dashboard.php");
    exit;
} else {
    echo "Error: " . mysqli_error($connection);
}
?>