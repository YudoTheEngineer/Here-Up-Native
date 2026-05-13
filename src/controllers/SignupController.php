<?php
// Session Validation
session_start();

if (isset($_SESSION["session"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Database Connection
include "../../env.php";

// Input User
$_SESSION["user_input"] = $_POST;

// Get Sign Up Data
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$random_profile = rand(1, 20);
$profile_picture = "default-profile-".$random_profile.".svg";

// Check if Username are too short or long
$username_length = strlen($username);

if ($username_length < 5) {
    $_SESSION["error"] = "Username must be at least 5 characters.";
    header("Location: ../views/sign-up.php");
    exit();
}

if ($username_length > 25) {
    $_SESSION["error"] = "Username must be 25 characters or less.";
    header("Location: ../views/sign-up.php");
    exit();
}

// Check if Password are too short or long
$password_length = strlen($password);

if ($password_length < 8) {
    $_SESSION["error"] = "Password must be at least 8 characters.";
    header("Location: ../views/sign-up.php");
    exit();
}

if ($password_length > 64) {
    $_SESSION["error"] = "Password must be 64 characters or less.";
    header("Location: ../views/sign-up.php");
    exit();
}

// check if Email are too long
$email_length = strlen($email);
if ($email_length > 320) {
    $_SESSION["error"] = "Email must be 320 characters or less.";
    header("Location: ../views/sign-up.php");
    exit();
}

// Check if Username already exists
if ($username){
    $check = mysqli_query($connection, "SELECT id FROM user WHERE username = '$username' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION["error"] = "Username has already used";
        header("Location: ../views/sign-up.php");
        exit();
    }
}

// Check if Email already exists in Database
if ($email){
    $check = mysqli_query($connection, "SELECT id FROM user WHERE email = '$email' LIMIT 1");
    if (mysqli_num_rows($check) > 0) {
        $_SESSION["error"] = "Email has already used";
        header("Location: ../views/sign-up.php");
        exit();
    }
}

// Hash the Password
$hash = password_hash($password, PASSWORD_DEFAULT);

// Set Account Status
$is_active = 1;

// Insert to Database Variable
$data = "
INSERT INTO user (username, email, password, is_active, profile_picture) 
VALUES ('$username', '$email', '$hash', '$is_active', '$profile_picture')
";

// Create New User with Check if there is an Error
if (mysqli_query($connection, $data)) {
    unset($_SESSION["user_input"]);
    // Head to Dashbord Page
    header("Location: ../views/dashboard.php");
    exit;
} else {
    echo "Error: " . mysqli_error($connection);
}
?>