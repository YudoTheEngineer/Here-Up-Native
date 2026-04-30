<?php
include "../../env.php";

session_start();

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

$class_name = $_POST["class_name"];
$class_description = $_POST["class_description"];
$class_mode = $_POST["class_mode"];
$class_creator = $_SESSION["session"]["id"];

function generateInvitationCode($connection) {
    do {
        $code = strtoupper(substr(uniqid(), -6));
        $check = mysqli_query($connection, "SELECT id FROM class WHERE unique_code = '$code'");
    } while (mysqli_num_rows($check) > 0);
    return $code;
}

$invitation_code = generateInvitationCode($connection);

$data = "
INSERT INTO class (name, description, mode, created_by, unique_code)
VALUES ('$class_name', '$class_description', '$class_mode', '$class_creator', '$invitation_code')
";

if (mysqli_query($connection, $data)) {
    $get_id = mysqli_query($connection, "SELECT id FROM class WHERE unique_code = '$invitation_code' LIMIT 1");
    $result = mysqli_fetch_assoc($get_id);

    if ($result) {
        $class_id = $result['id'];
        
        $user_class = "
        INSERT INTO user_class (user_id, class_id, role)
        VALUES ('$class_creator', '$class_id', '1')
        ";
        
        if (mysqli_query($connection, $user_class)) {
            header("Location: ../views/admin-class.php");
            exit();
        } else {
            echo "Error adding user to class: " . mysqli_error($connection);
        }
    }
} else {    
    echo "Error: " . mysqli_error($connection);
}
?>