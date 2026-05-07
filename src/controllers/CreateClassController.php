<?php
// Session Vaidation
session_start();

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

// Run Unique Code Function
$invitation_code = generateInvitationCode($connection);

// Create Class Variable
$data = "
INSERT INTO class (name, description, mode, created_by, unique_code)
VALUES ('$class_name', '$class_description', '$class_mode', '$class_creator', '$invitation_code')
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