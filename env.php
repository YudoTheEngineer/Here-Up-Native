<?php
$host = 'localhost';
$username = 'root';
$pass = '';
$database = 'here_up_native';

$conn = mysqli_connect($host, $username, $pass, $database);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>