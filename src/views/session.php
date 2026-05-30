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
// URL Validation
if (!isset($_GET["session_id"]) || !isset($_GET["class_id"])) {
    header("Location: ../views/dashboard.php");
    exit();
}
// Database Validation
$session_id = $_GET["session_id"];
$class_id = $_GET["class_id"];
$user_id = $_SESSION["session"]["id"];
$user_class_query = "SELECT * FROM user_class WHERE user_id = '$user_id' AND class_id = '$class_id' LIMIT 1";
$user_class = mysqli_fetch_assoc(mysqli_query($connection, $user_class_query));
$session_query = "SELECT * FROM session WHERE id = '$session_id' LIMIT 1";
$session = mysqli_fetch_assoc(mysqli_query($connection, $session_query));
if (!$user_class || !$session) {
    header("Location: ../views/dashboard.php");
    exit();
}

$member_query = "SELECT * FROM member WHERE class_id = $class_id";
$member_result = mysqli_query($connection, $member_query);

// ── Ambil data attendance yang sudah tersimpan ────────────────────────────
$attendance_query  = "SELECT * FROM attendance WHERE session_id = '$session_id'";
$attendance_result = mysqli_query($connection, $attendance_query);
$attendance_data   = [];
while ($att = mysqli_fetch_assoc($attendance_result)) {
    $attendance_data[$att["member_id"]] = $att;
}

// ── Hitung stats ──────────────────────────────────────────────────────────
$stats = [1 => 0, 2 => 0, 3 => 0, 4 => 0];
foreach ($attendance_data as $att) {
    $s = (int) $att["status"];
    if (isset($stats[$s])) $stats[$s]++;
}

// ── Mapping status angka → config tampilan ────────────────────────────────
$status_config = [
    1 => ["dot" => "bg-green-500",  "badge" => "bg-green-50 text-green-600",   "label" => "Present"],
    2 => ["dot" => "bg-yellow-400", "badge" => "bg-yellow-50 text-yellow-600", "label" => "Excused"],
    3 => ["dot" => "bg-blue-400",   "badge" => "bg-blue-50 text-blue-600",     "label" => "Sick"],
    4 => ["dot" => "bg-red-400",    "badge" => "bg-red-50 text-red-500",       "label" => "Not Excused"],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Session | HereUp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Flatpickr -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex">

    <!-- Sidebar -->
    <aside class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full z-20">
        <div class="p-6 flex items-center gap-2">
            <a href="#">
                <div class="flex items-center justify-between gap-[13.5px] cursor-pointer">
                    <img class="h-[31.5px]" src="../../public/images/icon.png" alt="Icon">
                    <h1 class="text-[20.7px] font-extrabold text-[#87CEEB]">HERE UP</h1>
                </div>
            </a>
        </div>

        <nav class="flex-1 px-4 space-y-1">
            <a href="dashboard.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                <span class="text-sm">Dashboard</span>
            </a>
            <a href="class.php" class="flex items-center gap-3 px-4 py-3 bg-[#93C5FD] text-white rounded-xl font-medium transition-all">
                <i data-lucide="presentation" class="w-5 h-5"></i>
                <span class="text-sm">Class</span>
            </a>
            <a href="attendance.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span class="text-sm">Attendance</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="bar-chart-3" class="w-5 h-5"></i>
                <span class="text-sm">Report</span>
            </a>
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="settings" class="w-5 h-5"></i>
                <span class="text-sm">Settings</span>
            </a>
            <form action="../controllers/SignoutController.php" method="POST">
                <button type="submit" class="flex items-center gap-3 ml-[3px] px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl w-full transition-all">
                    <i data-lucide="log-out" class="w-5 h-5"></i>
                    <span class="text-sm">Sign Out</span>
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Area -->
    <div class="flex-1 ml-64 flex flex-col">

        <!-- Header -->
        <header class="h-[75px] border-b border-gray-200 bg-white flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="relative w-1/3">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search class or user" class="w-full bg-[#F1F5F9] border-none rounded-full py-2.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-blue-100 outline-none transition-all">
            </div>

            <div class="flex items-center gap-6">
                <button class="text-gray-400 hover:text-gray-600 relative transition-colors">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-400 border-2 border-white rounded-full"></span>
                </button>
                <button class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="settings-2" class="w-5 h-5"></i>
                </button>
                <div class="flex items-center gap-3 pl-4 border-l border-gray-100">
                    <div class="w-10 h-10 rounded-full border-blue-50 overflow-hidden bg-gray-100 shadow-sm">
                        <img src="../../storage/profile_picture/<?php echo htmlspecialchars($_SESSION['session']['profile_picture']) ?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['session']['username']); ?></span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-10 space-y-6">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-gray-400">
                <a href="class.php" class="hover:text-gray-600 transition-colors">Class</a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="admin-class.php?class_id=<?php echo htmlspecialchars($class_id); ?>" class="hover:text-gray-600 transition-colors">
                    Admin Class
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gray-600 font-medium">Session Detail</span>
            </div>

            <!-- Session Hero Card -->
            <form action="../controllers/AttendanceController.php" method="POST" class="space-y-6" id="attendanceForm">

                <input type="hidden" name="session_id" value="<?= $session_id ?>">
                <input type="hidden" name="class_id" value="<?= $class_id ?>">

                <div id="hidden-attendance-inputs"></div>

                <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                    <div class="flex items-start justify-between gap-4 flex-wrap">

                        <!-- Left: Session Info -->
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i data-lucide="book-open" class="w-6 h-6 text-blue-400"></i>
                            </div>

                            <div>
                                <p class="text-[11px] font-semibold text-[#93C5FD] uppercase tracking-widest mb-1">Session Detail</p>
                                <div class="flex items-center gap-2 mb-1">
                                    <h2 class="text-xl font-bold text-gray-800 leading-tight">
                                        <?= htmlspecialchars($session["name"])?>
                                    </h2>
                                    <?php if ($session["is_active"] === "1"): ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-green-50 text-green-600 flex-shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                        Active
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                        Closed
                                    </span>
                                    <?php endif;?>
                                </div>

                                <p class="text-sm text-gray-400 leading-relaxed max-w-lg">
                                    <?php 
                                        if($session["description"]) { 
                                            echo htmlspecialchars($session["description"]);
                                        } else { 
                                            echo "..."; 
                                        }
                                    ?>
                                </p>

                                <div class="flex items-center gap-2 flex-wrap mt-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <?php echo date("d M Y", strtotime($session["start_time"])); ?>
                                    </span>
                                    <span class="text-[11px] text-gray-300">→</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                        <?php echo date("d M Y", strtotime($session["end_time"])); ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 font-mono">
                                        <i data-lucide="clock-4" class="w-3 h-3"></i>
                                        <?php echo date("H:i", strtotime($session["start_time"])); ?> - <?php echo date("H:i", strtotime($session["end_time"])); ?>
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-wider font-mono">
                                        <i data-lucide="globe" class="w-3 h-3"></i>
                                        <?php echo htmlspecialchars($session["timezone"]); ?>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Action Buttons -->
                        <div class="flex items-center gap-3 flex-wrap">
                            <?php if ($session["is_active"] === "1"): ?>
                            <button
                                class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                Edit Session
                            </button>

                            <button
                                type="submit"
                                class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-400 rounded-xl text-sm font-medium hover:bg-blue-100 transition-all border border-blue-100">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                Submit Attendance
                            </button>
                            <?php endif;?>
                        </div>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-4 gap-4">
                    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Present</p>
                            <p class="text-xl font-bold text-gray-700" id="stat-present"><?= $stats[1] ?></p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="file-text" class="w-5 h-5 text-yellow-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Excused</p>
                            <p class="text-xl font-bold text-gray-700" id="stat-excused"><?= $stats[2] ?></p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="heart-pulse" class="w-5 h-5 text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Sick</p>
                            <p class="text-xl font-bold text-gray-700" id="stat-sick"><?= $stats[3] ?></p>
                        </div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                            <i data-lucide="x-circle" class="w-5 h-5 text-red-400"></i>
                        </div>
                        <div>
                            <p class="text-xs text-gray-400 mb-0.5">Not Excused</p>
                            <p class="text-xl font-bold text-gray-700" id="stat-absent"><?= $stats[4] ?></p>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table Card -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm">

                    <!-- Card Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest">Attendance List</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm border-collapse min-w-[700px]">
                            <thead>
                                <tr class="border-b border-gray-100">
                                    <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[5%]">No</th>
                                    <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[25%]">Member</th>
                                    <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[8%]">Gender</th>
                                    <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[18%]">Status</th>
                                    <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[25%]">Note</th>
                                    <th class="text-right text-xs font-medium text-gray-400 tracking-wide pb-3 w-[19%]">Marked At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $row = 1;
                                    while($member = mysqli_fetch_assoc($member_result)):
                                    $member_id = $member['id'];
                                    $att = $attendance_data[$member_id] ?? null;
                                ?>
                                <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">

                                    <td class="py-4 text-xs text-gray-400"><?= $row++;?></td>
                                    <td class="py-4 pl-3">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                            <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                                <img src="../../storage/profile_picture/<?= htmlspecialchars($member['profile_picture'])?>" alt="Member" class="w-full h-full object-cover">
                                            </div>
                                            <?= htmlspecialchars($member["fullname"])?>
                                        </span>
                                    </td>
                                    <td class="py-4 text-center">
                                        <?php if ($member['gender'] === "1") : ?>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50" title="Male">
                                                <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                                            </span>
                                        <?php elseif ($member['gender'] === "2") :?>
                                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-pink-50" title="Female">
                                                <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-gray-300 text-xs">-</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Status Column -->
                                    <td class="py-4 text-center" id="status-col-<?= $member_id ?>">
                                        <?php if ($att): $cfg = $status_config[(int)$att["status"]]; ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium <?= $cfg['badge'] ?>">
                                                <span class="w-1.5 h-1.5 rounded-full <?= $cfg['dot'] ?> shrink-0"></span>
                                                <?= $cfg['label'] ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></span>
                                                Not Marked
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Note Column -->
                                    <td class="py-4" id="note-col-<?= $member_id ?>">
                                        <?php if ($att && $att["note"]): ?>
                                            <span class="text-xs text-gray-600"><?= htmlspecialchars($att["note"]) ?></span>
                                        <?php else: ?>
                                            <span class="text-xs text-gray-400 italic">...</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Marked At Column -->
                                    <td class="py-4 text-right" id="mark-col-<?= $member_id ?>">
                                        <?php if ($session["is_active"] === "1"): ?>
                                            <?php if ($att): ?>
                                            <div class="flex flex-col items-end gap-1">
                                                <span class="text-[11px] text-gray-500 font-mono">
                                                    <?php
                                                        try {
                                                            $dt = new DateTime($att["marked_at"], new DateTimeZone($session["timezone"]));
                                                            echo $dt->format("d M Y, H:i");
                                                        } catch (Exception $e) {
                                                            echo htmlspecialchars($att["marked_at"]);
                                                        }
                                                    ?>
                                                </span>
                                                <button
                                                    type="button"
                                                    onclick="openMarkAttendanceModal(<?= $member_id ?>)"
                                                    class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 text-gray-400 rounded-lg text-[10px] font-medium hover:bg-gray-100 transition-all border border-gray-100">
                                                    <i data-lucide="pencil" class="w-3 h-3"></i>
                                                    Re-mark
                                                </button>
                                            </div>
                                            <?php else: ?>
                                            <button
                                                type="button"
                                                onclick="openMarkAttendanceModal(<?= $member_id ?>)"
                                                class="inline-flex cursor-pointer items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-400 rounded-xl text-xs font-medium hover:bg-blue-100 transition-all border border-blue-100">
                                                <i data-lucide="check" class="w-3.5 h-3.5"></i>
                                                Mark
                                            </button>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <?php if ($att): ?>
                                            <span class="text-[11px] text-gray-500 font-mono">
                                                <?php
                                                    try {
                                                        $dt = new DateTime($att["marked_at"], new DateTimeZone($session["timezone"]));
                                                        echo $dt->format("d M Y, H:i");
                                                    } catch (Exception $e) {
                                                        echo htmlspecialchars($att["marked_at"]);
                                                    }
                                                ?>
                                            </span>
                                            <?php else: ?>
                                            <span class="text-xs text-gray-400 font-mono">-</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </main>
    </div>

    <!-- ── Modal: Mark Attendance ────────────────────────────────────────────────── -->
    <div id="markAttendanceModal" class="fixed inset-0 hidden items-center justify-center z-50">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeMarkAttendanceModal()"></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Mark Attendance</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-6">Record attendance for this member</p>

            <!-- Member Info -->
            <div class="flex items-center gap-3 mb-6 p-4 bg-[#FAFAFA] rounded-xl border border-[#DEE1E6]">
                <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0 bg-gray-100">
                    <img id="modal-member-photo" src="" alt="Member" class="w-full h-full object-cover">
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700" id="modal-member-name"></p>
                    <p class="text-xs text-gray-400" id="modal-member-status">Session: -</p>
                </div>
            </div>

            <!-- Attendance Status -->
            <div class="mb-6">
                <label class="text-sm text-[#565D6D] block mb-3 font-medium">Attendance Status</label>
                <div class="space-y-2.5" id="status-options-wrapper">
                    
                    <!-- Present -->
                    <label class="flex items-center gap-3 p-3 border-2 border-[#DEE1E6] rounded-xl cursor-pointer hover:border-[#93C5FD] transition-all">
                        <input type="radio" name="attendance_status" value="present" class="w-4 h-4 text-blue-400">
                        <div class="flex items-center gap-2 flex-1">
                            <span class="w-2 h-2 rounded-full bg-green-400"></span>
                            <span class="text-sm text-[#565D6D]">Present</span>
                        </div>
                    </label>

                    <!-- Excused -->
                    <label class="flex items-center gap-3 p-3 border-2 border-[#DEE1E6] rounded-xl cursor-pointer hover:border-[#93C5FD] transition-all">
                        <input type="radio" name="attendance_status" value="excused" class="w-4 h-4 text-yellow-400">
                        <div class="flex items-center gap-2 flex-1">
                            <span class="w-2 h-2 rounded-full bg-yellow-400"></span>
                            <span class="text-sm text-[#565D6D]">Excused</span>
                        </div>
                    </label>

                    <!-- Sick -->
                    <label class="flex items-center gap-3 p-3 border-2 border-[#DEE1E6] rounded-xl cursor-pointer hover:border-[#93C5FD] transition-all">
                        <input type="radio" name="attendance_status" value="sick" class="w-4 h-4 text-blue-400">
                        <div class="flex items-center gap-2 flex-1">
                            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
                            <span class="text-sm text-[#565D6D]">Sick</span>
                        </div>
                    </label>

                    <!-- Not Excused -->
                    <label class="flex items-center gap-3 p-3 border-2 border-[#DEE1E6] rounded-xl cursor-pointer hover:border-[#93C5FD] transition-all">
                        <input type="radio" name="attendance_status" value="absent" class="w-4 h-4 text-red-400">
                        <div class="flex items-center gap-2 flex-1">
                            <span class="w-2 h-2 rounded-full bg-red-400"></span>
                            <span class="text-sm text-[#565D6D]">Not Excused</span>
                        </div>
                    </label>
                </div>

                <!-- ═══ ERROR: Status wajib dipilih ═══ -->
                <p id="error-attendance-status" class="hidden mt-2 text-xs text-red-400 flex items-center gap-1">
                    <i data-lucide="alert-circle" class="w-3 h-3"></i>
                    Please select an attendance status.
                </p>
            </div>

            <!-- Note -->
            <div class="mb-6">
                <label class="text-sm text-[#565D6D] block mb-2 font-medium">Note (Optional)</label>
                <input
                    type="text"
                    id="modal-note"
                    placeholder="Add a note..."
                    class="w-full border border-[#DEE1E6] rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-1 focus:ring-[#87CEEB]">
            </div>

            <!-- Hidden Member ID -->
            <input type="hidden" id="modal-member-id" value="">

            <!-- Buttons -->
            <div class="flex gap-3">
                <button
                    type="button"
                    onclick="confirmAttendance()"
                    class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl text-sm font-medium hover:opacity-90 transition-all">
                    Confirm
                </button>
                <button
                    type="button"
                    onclick="closeMarkAttendanceModal()"
                    class="flex-1 py-3 border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all font-medium">
                    Cancel
                </button>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // ── Data dari PHP ────────────────────────────────────────────────────────
        const membersData = {
            <?php 
                $member_query = "SELECT * FROM member WHERE class_id = $class_id";
                $member_result_script = mysqli_query($connection, $member_query);
                $first = true;
                while($m = mysqli_fetch_assoc($member_result_script)):
                    if (!$first) echo ",";
                    $first = false;
            ?>
            "<?= $m['id'] ?>": {
                "fullname": "<?= htmlspecialchars($m['fullname']) ?>",
                "profile_picture": "<?= htmlspecialchars($m['profile_picture']) ?>"
            }
            <?php endwhile; ?>
        };

        const sessionName     = "<?= htmlspecialchars($session['name']) ?>";
        const sessionTimezone = "<?= htmlspecialchars($session['timezone']) ?>";

        // ── localStorage key unik per session ───────────────────────────────────
        const STORAGE_KEY = "attendance_session_<?= $session_id ?>_class_<?= $class_id ?>";

        // ── Data attendance dari DB (untuk kalkulasi stats) ──────────────────────
        const dbAttendanceData = {
            <?php
                $db_att_map = ["1" => "present", "2" => "excused", "3" => "sick", "4" => "absent"];
                $first = true;
                foreach ($attendance_data as $mid => $att):
                    if (!$first) echo ",";
                    $first = false;
                    $status_str = $db_att_map[$att["status"]] ?? "absent";
            ?>
            "<?= $mid ?>": "<?= $status_str ?>"
            <?php endforeach; ?>
        };


        // ════════════════════════════════════════════════════════════════════════
        // HELPERS
        // ════════════════════════════════════════════════════════════════════════

        // Mapping status → tampilan badge tabel
        const STATUS_CONFIG = {
            present: {
                dot:   "bg-green-500",
                badge: "bg-green-50 text-green-600",
                label: "Present",
            },
            excused: {
                dot:   "bg-yellow-400",
                badge: "bg-yellow-50 text-yellow-600",
                label: "Excused",
            },
            sick: {
                dot:   "bg-blue-400",
                badge: "bg-blue-50 text-blue-600",
                label: "Sick",
            },
            absent: {
                dot:   "bg-red-400",
                badge: "bg-red-50 text-red-500",
                label: "Not Excused",
            },
        };

        // Format tanggal ke timezone session
        function formatMarkedAt(isoString) {
            try {
                const date = new Date(isoString);
                return date.toLocaleString("en-GB", {
                    timeZone:  sessionTimezone,
                    day:       "2-digit",
                    month:     "short",
                    year:      "numeric",
                    hour:      "2-digit",
                    minute:    "2-digit",
                    hour12:    false,
                });
            } catch (e) {
                return new Date(isoString).toLocaleString("en-GB", {
                    day:    "2-digit",
                    month:  "short",
                    year:   "numeric",
                    hour:   "2-digit",
                    minute: "2-digit",
                    hour12: false,
                });
            }
        }

        // Update stat counters — gabungkan data DB + localStorage
        function updateStats() {
            const baseStats = {
                present: <?= $stats[1] ?>,
                excused: <?= $stats[2] ?>,
                sick:    <?= $stats[3] ?>,
                absent:  <?= $stats[4] ?>,
            };

            const saved  = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
            const counts = { ...baseStats };

            Object.entries(saved).forEach(([memberId, data]) => {
                // Kurangi status lama dari DB jika member ini sudah ada di DB
                if (dbAttendanceData[memberId]) {
                    counts[dbAttendanceData[memberId]]--;
                }
                // Tambah status baru dari localStorage
                if (counts[data.status] !== undefined) counts[data.status]++;
            });

            document.getElementById("stat-present").textContent = Math.max(0, counts.present);
            document.getElementById("stat-excused").textContent = Math.max(0, counts.excused);
            document.getElementById("stat-sick").textContent    = Math.max(0, counts.sick);
            document.getElementById("stat-absent").textContent  = Math.max(0, counts.absent);
        }

        // Tulis / perbarui hidden inputs di dalam form
        function writeHiddenInputs(memberId, status, note, markedAt) {
            const container = document.getElementById("hidden-attendance-inputs");

            // Hapus input lama milik member ini
            container.querySelectorAll(`[data-member="${memberId}"]`).forEach(el => el.remove());

            const fields = { status, note, marked_at: markedAt };
            Object.entries(fields).forEach(([key, val]) => {
                const inp          = document.createElement("input");
                inp.type           = "hidden";
                inp.name           = `attendance[${memberId}][${key}]`;
                inp.value          = val;
                inp.dataset.member = memberId;
                container.appendChild(inp);
            });
        }

        // Update baris tabel setelah di-mark
        function updateRowUI(memberId, status, note, markedAt) {
            const cfg = STATUS_CONFIG[status];
            if (!cfg) return;

            // — Status badge —
            const statusCol = document.getElementById(`status-col-${memberId}`);
            if (statusCol) {
                statusCol.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium ${cfg.badge}">
                        <span class="w-1.5 h-1.5 rounded-full ${cfg.dot} shrink-0"></span>
                        ${cfg.label}
                    </span>`;
            }

            // — Note —
            const noteCol = document.getElementById(`note-col-${memberId}`);
            if (noteCol) {
                noteCol.innerHTML = note
                    ? `<span class="text-xs text-gray-600">${note}</span>`
                    : `<span class="text-xs text-gray-400 italic">...</span>`;
            }

            // — Marked At: tampilkan tanggal + tombol Re-mark —
            const markCol = document.getElementById(`mark-col-${memberId}`);
            if (markCol) {
                markCol.innerHTML = `
                    <div class="flex flex-col items-end gap-1">
                        <span class="text-[11px] text-gray-500 font-mono">${formatMarkedAt(markedAt)}</span>
                        <button
                            type="button"
                            onclick="openMarkAttendanceModal(${memberId})"
                            class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 text-gray-400 rounded-lg text-[10px] font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="pencil" class="w-3 h-3"></i>
                            Re-mark
                        </button>
                    </div>`;
                lucide.createIcons();
            }
        }

        // Restore seluruh data dari localStorage saat halaman dimuat
        function restoreFromStorage() {
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
            Object.entries(saved).forEach(([memberId, data]) => {
                writeHiddenInputs(memberId, data.status, data.note, data.markedAt);
                updateRowUI(memberId, data.status, data.note, data.markedAt);
            });
            updateStats();
        }


        // ════════════════════════════════════════════════════════════════════════
        // MODAL FUNCTIONS
        // ════════════════════════════════════════════════════════════════════════

        function openMarkAttendanceModal(memberId) {
            document.getElementById("modal-member-id").value = memberId;

            const member = membersData[memberId];
            if (member) {
                document.getElementById("modal-member-photo").src          = "../../storage/profile_picture/" + member.profile_picture;
                document.getElementById("modal-member-name").textContent   = member.fullname;
                document.getElementById("modal-member-status").textContent = "Session: " + sessionName;
            }

            // Isi ulang status & note — prioritaskan localStorage, fallback ke DB
            const saved    = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
            const existing = saved[memberId];
            const dbStatus = dbAttendanceData[memberId] ?? null;

            document.querySelectorAll("input[name='attendance_status']").forEach(r => {
                if (existing) {
                    r.checked = r.value === existing.status;
                } else if (dbStatus) {
                    r.checked = r.value === dbStatus;
                } else {
                    r.checked = false;
                }
            });

            document.getElementById("modal-note").value = existing ? existing.note : "";

            // Reset error
            clearStatusError();

            document.getElementById("markAttendanceModal").classList.remove("hidden");
            document.getElementById("markAttendanceModal").classList.add("flex");
        }

        function closeMarkAttendanceModal() {
            document.getElementById("markAttendanceModal").classList.add("hidden");
            document.getElementById("markAttendanceModal").classList.remove("flex");
        }


        // ════════════════════════════════════════════════════════════════════════
        // VALIDATION
        // ════════════════════════════════════════════════════════════════════════

        function showStatusError() {
            const wrapper = document.getElementById("status-options-wrapper");
            const errorEl = document.getElementById("error-attendance-status");

            wrapper.querySelectorAll("label").forEach(lbl => {
                lbl.classList.remove("border-[#DEE1E6]", "hover:border-[#93C5FD]");
                lbl.classList.add("border-red-300");
            });

            errorEl.classList.remove("hidden");
            lucide.createIcons();
        }

        function clearStatusError() {
            const wrapper = document.getElementById("status-options-wrapper");
            const errorEl = document.getElementById("error-attendance-status");

            wrapper.querySelectorAll("label").forEach(lbl => {
                lbl.classList.remove("border-red-300");
                lbl.classList.add("border-[#DEE1E6]", "hover:border-[#93C5FD]");
            });

            errorEl.classList.add("hidden");
        }

        function validateAttendanceStatus() {
            const selected = document.querySelector("input[name='attendance_status']:checked");
            if (!selected) {
                showStatusError();
                return false;
            }
            clearStatusError();
            return true;
        }

        // Hapus error begitu user memilih status
        document.querySelectorAll("input[name='attendance_status']").forEach(radio => {
            radio.addEventListener("change", () => clearStatusError());
        });


        // ════════════════════════════════════════════════════════════════════════
        // CONFIRM ATTENDANCE
        // ════════════════════════════════════════════════════════════════════════

        function confirmAttendance() {
            // 1. Validasi
            if (!validateAttendanceStatus()) return;

            const memberId = document.getElementById("modal-member-id").value;
            const status   = document.querySelector("input[name='attendance_status']:checked").value;
            const note     = document.getElementById("modal-note").value.trim();
            const markedAt = new Date().toISOString();

            // 2. Simpan ke localStorage (tahan refresh)
            const saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || "{}");
            saved[memberId] = { status, note, markedAt };
            localStorage.setItem(STORAGE_KEY, JSON.stringify(saved));

            // 3. Tulis hidden inputs ke form
            writeHiddenInputs(memberId, status, note, markedAt);

            // 4. Update UI tabel
            updateRowUI(memberId, status, note, markedAt);

            // 5. Update stat counters
            updateStats();

            // 6. Tutup modal
            closeMarkAttendanceModal();
        }


        // ════════════════════════════════════════════════════════════════════════
        // SUBMIT: Bersihkan localStorage setelah form dikirim
        // ════════════════════════════════════════════════════════════════════════

        document.getElementById("attendanceForm").addEventListener("submit", () => {
            localStorage.removeItem(STORAGE_KEY);
        });


        // ── Keyboard shortcut ────────────────────────────────────────────────────
        document.addEventListener("keydown", e => {
            if (e.key === "Escape") closeMarkAttendanceModal();
        });


        // ── Init ─────────────────────────────────────────────────────────────────
        restoreFromStorage();
    </script>
</body>
</html>