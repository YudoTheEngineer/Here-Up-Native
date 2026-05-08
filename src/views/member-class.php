<?php
// Session Validation
session_start();

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database connection
include "../../env.php";

// URL Validation
if (!isset($_GET["class_id"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Database Validation
$class_id = $_GET["class_id"];
$user_id = $_SESSION["session"]["id"];

$query = "SELECT * FROM user_class WHERE user_id = '$user_id' AND class_id = '$class_id' LIMIT 1";

$result = mysqli_query($connection, $query);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    header("Location: ../views/dashboard.php");
    exit();
}

if ($row['role'] != 2) {
    header("Location: ../views/admin-class.php?class_id=".$class_id);
    exit();
}
?>