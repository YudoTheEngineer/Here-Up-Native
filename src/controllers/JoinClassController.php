<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Validation
session_start();

if (!isset($_SESSION[   "session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database Connection
include "../../env.php";

$invitation_code = $_POST["invitation_code"];

// Check if Class Exist
$check_class = "SELECT * FROM `class` WHERE unique_code = '$invitation_code' LIMIT 1";
$result = mysqli_query($connection, $check_class);

if (mysqli_num_rows($result) > 0) {
    // Check if User Already Join
    $class = mysqli_fetch_assoc($result);
    $class_id = $class["id"];
    $user_id = $_SESSION["session"]["id"];
    $check_user = "
    SELECT * FROM user_class WHERE user_id = '$user_id' 
    AND class_id = '$class_id' LIMIT 1"
    ;

    if (mysqli_num_rows(mysqli_query($connection, $check_user)) > 0) {
        $_SESSION["error"] = "You Already Join This Class";
        header("Location: ../views/dashboard.php");
        exit();
    }

    $query = "
    INSERT INTO user_class (user_id, class_id, role) 
    VALUES ('$user_id', '$class_id', '2')"
    ;

    if (mysqli_query($connection, $query)) {
        // Head to Member Class
        header("Location: ../views/member-class.php?class_id=".$class_id);
        exit();
    } else {
        echo "Error joining user to class: " . mysqli_error($connection);
    }
} else {
    $_SESSION["error"] = "Class Not Found";

    header("Location: ../views/dashboard.php");
    exit();
}

?>