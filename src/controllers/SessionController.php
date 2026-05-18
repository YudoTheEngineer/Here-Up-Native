<?php
// Session Validation
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database Connection
include "../../env.php";

$class_id = $_POST["class_id"];
$user_id = $_POST["user_id"];
$name = $_POST["name"];
$description = $_POST["description"];
$start_time = $_POST["start_time"];
$end_time = $_POST["end_time"];
$timezone = $_POST["timezone"];

$query = "INSERT INTO session (name, description, start_time, end_time, class_id, created_by, is_active, timezone) VALUES('$name', '$description', '$start_time', '$end_time', '$class_id', '$user_id','1', '$timezone')";

if(mysqli_query($connection, $query)) {
    // Head to Admin Class Page
    header("Location: ../views/admin-class.php?class_id=".$class_id);
    exit();
} else {
    echo "Error: " . mysqli_error($connection);
}
?>