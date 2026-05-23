<?php
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

// Get Attendance Data
$session_id = $_POST["session_id"];
$class_id   = $_POST["class_id"];
$attendance = $_POST["attendance"] ?? [];

// Validate Session & Class
$user_id          = $_SESSION["session"]["id"];
$user_class_query = "SELECT * FROM user_class WHERE user_id = '$user_id' AND class_id = '$class_id' LIMIT 1";
$user_class       = mysqli_fetch_assoc(mysqli_query($connection, $user_class_query));
$session_query    = "SELECT * FROM session WHERE id = '$session_id' AND is_active = '1' LIMIT 1";
$session          = mysqli_fetch_assoc(mysqli_query($connection, $session_query));

if (!$user_class || !$session) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Get Session Timezone
$session_timezone = $session["timezone"];

// Mapping status string → angka
$status_map = [
    "present" => 1,
    "excused" => 2,
    "sick"    => 3,
    "absent"  => 4,
];

// Current time in session timezone (untuk fallback marked_at)
try {
    $now_dt        = new DateTime("now", new DateTimeZone($session_timezone));
    $now_formatted = $now_dt->format("Y-m-d H:i:s");
} catch (Exception $e) {
    $now_formatted = date("Y-m-d H:i:s");
}

// ── Auto-fill Not Marked members → absent ───────────────────────────────────
$all_members_query  = "SELECT id FROM member WHERE class_id = '$class_id'";
$all_members_result = mysqli_query($connection, $all_members_query);

while ($member_row = mysqli_fetch_assoc($all_members_result)) {
    $mid = $member_row["id"];
    if (!isset($attendance[$mid]) || $attendance[$mid]["status"] === "") {
        $attendance[$mid] = [
            "status"    => "absent",
            "note"      => "",
            "marked_at" => "",
        ];
    }
}

// Validate Attendance is Not Empty
if (empty($attendance)) {
    $_SESSION["error"] = "No attendance data to submit.";
    header("Location: ../views/session.php?session_id=$session_id&class_id=$class_id");
    exit();
}

// Loop & Insert Each Member Attendance
$has_error = false;

foreach ($attendance as $member_id => $data) {

    $member_id    = (int) $member_id;
    $status_str   = $data["status"]    ?? "absent";
    $note         = $data["note"]      ?? "";
    $marked_at    = $data["marked_at"] ?? "";

    // Validate Status Value & Convert ke angka
    if (!array_key_exists($status_str, $status_map)) continue;
    $status = $status_map[$status_str];

    // Validate Member Belongs to This Class
    $member_check_query  = "SELECT id FROM member WHERE id = '$member_id' AND class_id = '$class_id' LIMIT 1";
    $member_check_result = mysqli_fetch_assoc(mysqli_query($connection, $member_check_query));
    if (!$member_check_result) continue;

    // Convert marked_at UTC ISO → session timezone datetime
    if (!empty($marked_at)) {
        try {
            $dt = new DateTime($marked_at, new DateTimeZone("UTC"));
            $dt->setTimezone(new DateTimeZone($session_timezone));
            $marked_at_formatted = $dt->format("Y-m-d H:i:s");
        } catch (Exception $e) {
            $marked_at_formatted = $now_formatted;
        }
    } else {
        // Not marked → gunakan waktu submit
        $marked_at_formatted = $now_formatted;
    }

    // Insert or Update Attendance
    $attendance_query = "
        INSERT INTO attendance (session_id, class_id, member_id, status, note, marked_at)
        VALUES ('$session_id', '$class_id', '$member_id', '$status', '$note', '$marked_at_formatted')
        ON DUPLICATE KEY UPDATE
            status    = '$status',
            note      = '$note',
            marked_at = '$marked_at_formatted'
    ";

    if (!mysqli_query($connection, $attendance_query)) {
        $has_error = true;
    }
}

// Redirect with Result
if ($has_error) {
    $_SESSION["error"] = "Some attendance records failed to save.";
    header("Location: ../views/session.php?session_id=$session_id&class_id=$class_id");
    exit();
}

$_SESSION["success"] = "Attendance submitted successfully.";
mysqli_query($connection, "UPDATE session SET is_active = '0' WHERE id = '$session_id'");

header("Location: ../views/session.php?session_id=$session_id&class_id=$class_id");
exit();
?>