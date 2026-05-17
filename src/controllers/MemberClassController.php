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
$fullname = $_POST["fullname"];
$gender = $_POST["gender"];
if (!empty($_FILES['profile_picture']['name'])) {
    
    // Upload foto sendiri
    $file     = $_FILES['profile_picture'];
    $fileExt  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    if (!in_array($fileExt, $allowed)) {
        $_SESSION["error"] = "Invalid file type.";
        header("Location: ../views/admin-class.php?class_id=".$class_id);
        exit();
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION["error"] = "File too large. Max 2MB.";
        header("Location: ../views/admin-class.php?class_id=".$class_id);
        exit();
    }

    $newFileName   = "class_" . time() . "_" . rand(100, 999) . "." . $fileExt;
    $uploadPath    = __DIR__."/../../storage/profile_picture/" . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $_SESSION["error"] = "Failed to upload image.";
        header("Location: ../views/admin-class.php?class_id=".$class_id);
        exit();
    }

    $profile_picture = $newFileName;

} else {
    $profile_picture = $_POST['default_profile_picture'] ?? 'default-profile-1.svg';
}

$query = "INSERT INTO member (fullname, gender, profile_picture, class_id) VALUES('$fullname', '$gender', '$profile_picture', '$class_id')";

if(mysqli_query($connection, $query)) {
    // Head to Admin Class Page
    header("Location: ../views/admin-class.php?class_id=".$class_id);
    exit();
} else {
    echo "Error: " . mysqli_error($connection);
}
?>