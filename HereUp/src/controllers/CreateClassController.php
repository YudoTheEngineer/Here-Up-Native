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
$is_active = 1;
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
INSERT INTO class (name, description, mode, is_active, created_by, unique_code)
VALUES ('$class_name', '$class_description', '$class_mode', '$is_active', '$class_creator', '$invitation_code')
";

if (mysqli_query($connection, $data)) {
    header("Location: ../views/class.php");
    exit;
} else {
    echo "Error: " . mysqli_error($connection);
}
?>