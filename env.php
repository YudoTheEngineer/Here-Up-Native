<?php
$host = 'localhost';
$username = 'root';
$pass = '';
$database = 'here_up';

$connection = mysqli_connect($host, $username, $pass, $database);

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>