<?php
include '../../env.php';

session_start();

if (isset($_SESSION["session"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

if (isset($_POST["username"])){
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($connection, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        if (password_verify($password, $data["password"])) {
            $_SESSION["session"] = $data;
            header("Location: ../views/dashboard.php");
            exit();
        } else {
            echo "<script>alert('Invalid username or password'); window.location.href = '../views/sign-in.php';</script>";
        };
    } else {
        echo "<script>alert('Invalid username or password'); window.location.href = '../views/sign-in.php';</script>";
    }
    ;
}
?>