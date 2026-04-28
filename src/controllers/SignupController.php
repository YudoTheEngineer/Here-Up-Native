<?php
include "../../env.php";

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];
$hash = password_hash($password, PASSWORD_DEFAULT);
$is_active = 1;

$data = "
INSERT INTO user (username, email, password, is_active) 
VALUES ('$username', '$email', '$hash', $is_active)
";

if (mysqli_query($connection, $data)) {
    header("Location: ../views/sign-in.php");
    exit;
} else {
    echo "Error: " . mysqli_error($connection);
}
?>