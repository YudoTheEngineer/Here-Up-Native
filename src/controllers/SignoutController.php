<?php
// Session Validation
session_start();

if (isset($_SESSION["session"])) {

    // Sign Out and Destroy The Session
    session_unset();
    session_destroy();

    // Head to Landing Page
    header("Location: ../../index.php");
    exit();
}
?>