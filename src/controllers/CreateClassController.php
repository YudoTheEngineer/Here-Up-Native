<?php
// Session Vaidation
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database connection
include "../../env.php";

// Get Create Class Data
$class_name = $_POST["class_name"];
$class_description = $_POST["class_description"];
$class_mode = $_POST["class_mode"];
$class_creator = $_SESSION["session"]["id"];

// Create New Unique Code
function generateInvitationCode($connection) {
    do {
        $code = strtoupper(substr(uniqid(), -6));
        // Check if the Unique Code has Already Exist
        $check = mysqli_query($connection, "SELECT id FROM class WHERE unique_code = '$code'");
    } while (mysqli_num_rows($check) > 0);
    return $code;
}

// Handle Class Profile Picture
if (!empty($_FILES['class_profile_picture']['name'])) {
    
    // Upload foto sendiri
    $file     = $_FILES['class_profile_picture'];
    $fileExt  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed  = ['jpg', 'jpeg', 'png', 'webp', 'svg'];

    if (!in_array($fileExt, $allowed)) {
        $_SESSION["error"] = "Invalid file type.";
        header("Location: ../views/dashboard.php");
        exit();
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        $_SESSION["error"] = "File too large. Max 2MB.";
        header("Location: ../views/dashboard.php");
        exit();
    }

    $newFileName   = "class_" . time() . "_" . rand(100, 999) . "." . $fileExt;
    $uploadPath    = "../../storage/class_profile_picture/" . $newFileName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        $_SESSION["error"] = "Failed to upload image.";
        header("Location: ../views/dashboard.php");
        exit();
    }

    $profile_picture = $newFileName;

} else {
    // Pakai default photo yang dipilih
    $profile_picture = $_POST['class_default_photo'] ?? 'default-profile-1.svg';
}

// Run Unique Code Function
$invitation_code = generateInvitationCode($connection);

// Create Class Variable
$data = "
INSERT INTO class (name, description, mode, created_by, unique_code, profile_picture)
VALUES ('$class_name', '$class_description', '$class_mode', '$class_creator', '$invitation_code', '$profile_picture')
";

// Create New Class with Validation
if (mysqli_query($connection, $data)) {
    // Get Class ID
    $get_id = mysqli_query($connection, "SELECT id FROM class WHERE unique_code = '$invitation_code' LIMIT 1");
    $result = mysqli_fetch_assoc($get_id);

    // Check if Class ID Exist
    if ($result) {
        $class_id = $result['id'];
        
        // Create UserClass Variable
        $user_class = "
        INSERT INTO user_class (user_id, class_id, role)
        VALUES ('$class_creator', '$class_id', '1')
        ";
        
        // Create New UserClass with Check if there is an Error
        if (mysqli_query($connection, $user_class)) {

            // Head to Admin Class Page
            header("Location: ../views/admin-class.php?class_id=".$class_id);
            exit();
        } else {
            echo "Error adding user to class: " . mysqli_error($connection);
        }
    }
} else {    
    echo "Error: " . mysqli_error($connection);
}
?>