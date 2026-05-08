<?php
$host = "localhost";
$user = "root";
$pass = "";
$database = "here_up";

$connection = mysqli_connect($host, $user, $pass, $database);

if (!$connection) {
    die("Connection Failed: ". mysqli_connect_error());
}
?>