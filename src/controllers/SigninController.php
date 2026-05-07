<?php
// Session Validation
session_start();

if (isset($_SESSION["session"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Database Connection
include '../../env.php';

// Get Sign In Data
$username = $_POST["username"];
$password = $_POST["password"];

// Seacrch Username Variable
$query = "SELECT * FROM user WHERE username = '$username'";
$result = mysqli_query($connection, $query);

// Check if Username is Exist in Database
if (mysqli_num_rows($result) > 0) {
    $data = mysqli_fetch_assoc($result);
    
    // Check if Password are Valid
    if (password_verify($password, $data["password"])) {

        // Sign In New Session
        $_SESSION["session"] = $data;

        // Head to Dashboard Page
        header("Location: ../views/dashboard.php");
        exit();
    } else { 
        $_SESSION["error"] = "Invalid Username or Password";
        $_SESSION["user_input"] = $username;
        header("Location: ../views/sign-in.php");
        exit();
    };
} else { 
    $_SESSION["error"] = "Invalid Username or Password";
    $_SESSION["user_input"] = $username;
    header("Location: ../views/sign-in.php");
    exit();
}
?>