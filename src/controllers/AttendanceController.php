<?php
var_dump($_POST);
die();

// Session Validation
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database connection
include "../../env.php";

// Ambil data dari POST
$session_id = $_POST["session_id"];
$class_id   = $_POST["class_id"];

$draft = json_decode($_POST["draft"], true);

// Validasi session masih aktif
$session_query  = "SELECT id, timezone   FROM session WHERE id = '$session_id' AND is_active = '1' LIMIT 1";
$session_result = mysqli_fetch_assoc(mysqli_query($connection, $session_query));

if (!$session_result) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Ambil semua member di class ini
$member_query  = "SELECT id FROM member WHERE class_id = '$class_id'";
$member_result = mysqli_query($connection, $member_query);

$tz  = new DateTimeZone($session_result["timezone"]);
$now = new DateTime("now", $tz);
$now = $now->format("Y-m-d H:i:s");

// Loop semua member
while ($member = mysqli_fetch_assoc($member_result)) {
    $member_id = $member["id"];

    // Cek apakah member ini ada di draft
    if (isset($draft[$member_id])) {
        $statusMap = [
            "present"     => 1,
            "excused"     => 2,
            "sick"        => 3,
            "not_excused" => 4
        ];

        $status = $statusMap[$draft[$member_id]["status"]];
        $note   = mysqli_real_escape_string($connection, $draft[$member_id]["note"]);
    } else {
        // Tidak ada di draft — otomatis not_excused, note kosong
        $status = 4;
        $note   = "";
    }

    // Simpan ke database
    $insert_query = "INSERT INTO attendance (session_id, member_id, status, note, marked_at)
                    VALUES ('$session_id', '$member_id', '$status', '$note', '$now')";
    mysqli_query($connection, $insert_query);
}

// Kembali ke halaman session setelah selesai
header("Location: ../views/session.php?session_id=$session_id&class_id=$class_id");
exit();