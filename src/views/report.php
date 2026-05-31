<?php
// Session Validation
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

include "../../env.php";

$user_id = $_SESSION["session"]["id"];

// State: step 1 = pilih class, step 2 = pilih session, step 3 = print
$selected_class_id   = isset($_GET['class_id'])   ? $_GET['class_id']   : '';
$selected_session_id = isset($_GET['session_id']) ? $_GET['session_id'] : '';

// Get all classes user belongs to
$classes_query = "
    SELECT c.id, c.name, c.profile_picture, uc.role
    FROM user_class uc
    JOIN class c ON uc.class_id = c.id
    WHERE uc.user_id = '$user_id'
    ORDER BY c.name ASC
";
$classes_result = mysqli_query($connection, $classes_query);
$classes_list   = [];
while ($cl = mysqli_fetch_assoc($classes_result)) {
    $classes_list[] = $cl;
}

// Step 2: get sessions for selected class
$sessions_list = [];
$selected_class = null;
if ($selected_class_id !== '') {
    foreach ($classes_list as $cl) {
        if ($cl['id'] == $selected_class_id) { $selected_class = $cl; break; }
    }
    $sessions_query = "SELECT * FROM session WHERE class_id = '$selected_class_id' ORDER BY start_time DESC";
    $sessions_result = mysqli_query($connection, $sessions_query);
    while ($s = mysqli_fetch_assoc($sessions_result)) {
        $sessions_list[] = $s;
    }
}

// Step 3: get attendance for selected session
$attendance_rows = [];
$selected_session = null;
$stats = [1 => 0, 2 => 0, 3 => 0, 4 => 0];

if ($selected_session_id !== '' && $selected_class_id !== '') {
    $session_query = "SELECT * FROM session WHERE id = '$selected_session_id' AND class_id = '$selected_class_id' LIMIT 1";
    $selected_session = mysqli_fetch_assoc(mysqli_query($connection, $session_query));

    if ($selected_session) {
        $att_query = "
            SELECT
                a.status,
                a.marked_at,
                a.note,
                m.fullname,
                m.profile_picture AS member_picture,
                m.gender
            FROM attendance a
            JOIN member m ON a.member_id = m.id
            WHERE a.session_id = '$selected_session_id'
            ORDER BY m.fullname ASC
        ";
        $att_result = mysqli_query($connection, $att_query);
        while ($row = mysqli_fetch_assoc($att_result)) {
            $attendance_rows[] = $row;
            $s = (int)$row['status'];
            if (isset($stats[$s])) $stats[$s]++;
        }
    }
}

$status_config = [
    1 => ["dot" => "bg-green-500",  "badge" => "bg-green-50 text-green-600",   "icon" => "check-circle-2", "label" => "Present"],
    2 => ["dot" => "bg-yellow-400", "badge" => "bg-yellow-50 text-yellow-600", "icon" => "file-text",      "label" => "Excused"],
    3 => ["dot" => "bg-blue-400",   "badge" => "bg-blue-50 text-blue-600",     "icon" => "heart-pulse",    "label" => "Sick"],
    4 => ["dot" => "bg-red-400",    "badge" => "bg-red-50 text-red-500",       "icon" => "x-circle",       "label" => "Not Excused"],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Report | HereUp</title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        @media print {
            body { background: white !important; }
            aside, header, .no-print { display: none !important; }
            .ml-64 { margin-left: 0 !important; }
            .shadow-sm { box-shadow: none !important; }
        }
    </style>
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
            <a href="class.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="presentation" class="w-5 h-5"></i>
                <span class="text-sm">Class</span>
            </a>
            <a href="attendance.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
                <i data-lucide="clipboard-check" class="w-5 h-5"></i>
                <span class="text-sm">Attendance</span>
            </a>
            <a href="report.php" class="flex items-center gap-3 px-4 py-3 bg-[#93C5FD] text-white rounded-xl font-medium transition-all">
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
                <input type="text" placeholder="Search class or user" class="w-full bg-[#F1F5F9] border-none rounded-full py-2.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder:text-gray-400">
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
                        <img src="../../storage/profile_picture/<?= htmlspecialchars($_SESSION['session']['profile_picture']) ?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['session']['username']) ?></span>
                </div>
            </div>
        </header>

        <main class="p-10 space-y-6">

            <!-- Breadcrumb -->
            <div class="flex items-center gap-2 text-xs text-gray-400 no-print">
                <a href="report.php" class="hover:text-gray-600 transition-colors">Report</a>
                <?php if ($selected_class): ?>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="report.php?class_id=<?= $selected_class_id ?>" class="hover:text-gray-600 transition-colors">
                    <?= htmlspecialchars($selected_class['name']) ?>
                </a>
                <?php endif; ?>
                <?php if ($selected_session): ?>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-gray-600 font-medium"><?= htmlspecialchars($selected_session['name']) ?></span>
                <?php endif; ?>
            </div>

            <?php if ($selected_session_id === '' && $selected_class_id === ''): ?>
            <!-- ══════════════════════════════════════════════ -->
            <!-- STEP 1: Pilih Class                           -->
            <!-- ══════════════════════════════════════════════ -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="mb-6">
                    <p class="text-[11px] font-semibold text-[#93C5FD] uppercase tracking-widest mb-1">Report</p>
                    <h2 class="text-xl font-bold text-gray-800">Select a Class</h2>
                    <p class="text-sm text-gray-400 mt-1">Choose a class to view its sessions</p>
                </div>

                <?php if (empty($classes_list)): ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3">
                        <i data-lucide="presentation" class="w-7 h-7 text-gray-300"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-300">No classes yet</p>
                    <p class="text-xs text-gray-300 mt-1">Join or create a class to get started</p>
                </div>
                <?php else: ?>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    <?php foreach ($classes_list as $cl): ?>
                    <a href="report.php?class_id=<?= $cl['id'] ?>">
                        <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition-all cursor-pointer flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                <img src="../../storage/class_profile_picture/<?= htmlspecialchars($cl['profile_picture']) ?>" alt="" class="w-full h-full object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-700 truncate"><?= htmlspecialchars($cl['name']) ?></p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <?= $cl['role'] == 1 ? 'Admin' : 'Member' ?>
                                </p>
                            </div>
                            <i data-lucide="chevron-right" class="w-4 h-4 text-gray-300 shrink-0"></i>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php elseif ($selected_session_id === ''): ?>
            <!-- ══════════════════════════════════════════════ -->
            <!-- STEP 2: Pilih Session                         -->
            <!-- ══════════════════════════════════════════════ -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6 flex-wrap gap-4">
                    <div>
                        <p class="text-[11px] font-semibold text-[#93C5FD] uppercase tracking-widest mb-1">Report</p>
                        <h2 class="text-xl font-bold text-gray-800">Select a Session</h2>
                        <p class="text-sm text-gray-400 mt-1">
                            Class: <span class="font-medium text-gray-600"><?= htmlspecialchars($selected_class['name']) ?></span>
                        </p>
                    </div>
                    <a href="report.php"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Back
                    </a>
                </div>

                <?php if (empty($sessions_list)): ?>
                <div class="flex flex-col items-center justify-center py-16 text-center">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3">
                        <i data-lucide="play-circle" class="w-7 h-7 text-gray-300"></i>
                    </div>
                    <p class="text-sm font-semibold text-gray-300">No sessions yet</p>
                    <p class="text-xs text-gray-300 mt-1">This class has no sessions to report</p>
                </div>
                <?php else: ?>
                <div class="flex flex-col gap-3">
                    <?php foreach ($sessions_list as $s): ?>
                    <a href="report.php?class_id=<?= $selected_class_id ?>&session_id=<?= $s['id'] ?>">
                        <div class="border border-gray-100 rounded-2xl p-5 hover:border-gray-200 transition-all cursor-pointer flex flex-col">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="book-open" class="w-4 h-4 text-blue-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[13px] font-semibold text-gray-700 truncate"><?= htmlspecialchars($s['name']) ?></span>
                                        <?php if ($s['is_active'] == '1'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-[3px] rounded-full text-[10px] font-medium bg-green-50 text-green-600 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                            Active
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                            Closed
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($s['description'])): ?>
                                    <p class="text-[12px] text-gray-400 mt-1 leading-relaxed line-clamp-2"><?= htmlspecialchars($s['description']) ?></p>
                                    <?php else: ?>
                                    <p class="text-[12px] text-gray-300 mt-1 italic font-light inline-flex items-center gap-1">
                                        <i data-lucide="help-circle" class="w-3 h-3 opacity-60"></i>
                                        No description provided
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="border-t border-gray-50"></div>
                            <div class="flex items-center justify-between flex-wrap gap-2 mt-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <?= date("d M Y", strtotime($s['start_time'])) ?>
                                    </span>
                                    <span class="text-[11px] text-gray-300">→</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                        <?= date("d M Y", strtotime($s['end_time'])) ?>
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-wider font-mono">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($s['timezone']) ?>
                                </span>
                            </div>
                            <div class="flex items-center gap-1.5 mt-2">
                                <i data-lucide="clock-4" class="w-3 h-3 text-gray-300"></i>
                                <span class="text-[11px] text-gray-400 font-mono">
                                    <?= date("H:i", strtotime($s['start_time'])) ?> – <?= date("H:i", strtotime($s['end_time'])) ?>
                                </span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <?php else: ?>
            <!-- ══════════════════════════════════════════════ -->
            <!-- STEP 3: Tampil & Print Attendance             -->
            <!-- ══════════════════════════════════════════════ -->

            <!-- Session Hero Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-start justify-between gap-4 flex-wrap">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i data-lucide="book-open" class="w-6 h-6 text-blue-400"></i>
                        </div>
                        <div>
                            <p class="text-[11px] font-semibold text-[#93C5FD] uppercase tracking-widest mb-1">Session Report</p>
                            <div class="flex items-center gap-2 mb-1">
                                <h2 class="text-xl font-bold text-gray-800 leading-tight"><?= htmlspecialchars($selected_session['name']) ?></h2>
                                <?php if ($selected_session['is_active'] == '1'): ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-green-50 text-green-600 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Active
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                    Closed
                                </span>
                                <?php endif; ?>
                            </div>
                            <p class="text-sm text-gray-400 leading-relaxed max-w-lg">
                                <?= !empty($selected_session['description']) ? htmlspecialchars($selected_session['description']) : '...' ?>
                            </p>
                            <div class="flex items-center gap-2 flex-wrap mt-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <?= date("d M Y", strtotime($selected_session['start_time'])) ?>
                                </span>
                                <span class="text-[11px] text-gray-300">→</span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                    <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                    <?= date("d M Y", strtotime($selected_session['end_time'])) ?>
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 font-mono">
                                    <i data-lucide="clock-4" class="w-3 h-3"></i>
                                    <?= date("H:i", strtotime($selected_session['start_time'])) ?> - <?= date("H:i", strtotime($selected_session['end_time'])) ?>
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-wider font-mono">
                                    <i data-lucide="globe" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($selected_session['timezone']) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap no-print">
                        <a href="report.php?class_id=<?= $selected_class_id ?>"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="arrow-left" class="w-4 h-4"></i>
                            Back
                        </a>
                        <button onclick="window.print()"
                            class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                            <i data-lucide="printer" class="w-4 h-4"></i>
                            Print Report
                        </button>
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
                        <p class="text-xl font-bold text-gray-700"><?= $stats[1] ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5 text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Excused</p>
                        <p class="text-xl font-bold text-gray-700"><?= $stats[2] ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="heart-pulse" class="w-5 h-5 text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Sick</p>
                        <p class="text-xl font-bold text-gray-700"><?= $stats[3] ?></p>
                    </div>
                </div>
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="x-circle" class="w-5 h-5 text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Not Excused</p>
                        <p class="text-xl font-bold text-gray-700"><?= $stats[4] ?></p>
                    </div>
                </div>
            </div>

            <!-- Attendance Table -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest">Attendance List</h2>
                </div>

                <div class="overflow-x-auto">
                    <?php if (empty($attendance_rows)): ?>
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3">
                            <i data-lucide="clipboard-list" class="w-7 h-7 text-gray-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-300">No attendance records</p>
                        <p class="text-xs text-gray-300 mt-1">No one has been marked for this session yet</p>
                    </div>
                    <?php else: ?>
                    <table class="w-full text-sm border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[5%]">No</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[25%]">Member</th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[8%]">Gender</th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[15%]">Status</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[25%]">Note</th>
                                <th class="text-right text-xs font-medium text-gray-400 tracking-wide pb-3 w-[22%]">Marked At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($attendance_rows as $i => $row):
                                $s    = (int)$row['status'];
                                $cfg  = $status_config[$s] ?? ["dot" => "bg-gray-300", "badge" => "bg-gray-50 text-gray-400", "icon" => "minus", "label" => "-"];
                                try {
                                    $tz = new DateTimeZone($selected_session['timezone'] ?? 'UTC');
                                    $dt = new DateTime($row['marked_at'], $tz);
                                    $fmt_marked = $dt->format("d M Y, H:i");
                                } catch (Exception $e) {
                                    $fmt_marked = $row['marked_at'] ?? '-';
                                }
                            ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400"><?= $i + 1 ?></td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="../../storage/profile_picture/<?= htmlspecialchars($row['member_picture']) ?>" alt="" class="w-full h-full object-cover">
                                        </div>
                                        <?= htmlspecialchars($row['fullname']) ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <?php if ($row['gender'] === "1"): ?>
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50" title="Male">
                                        <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                                    </span>
                                    <?php elseif ($row['gender'] === "2"): ?>
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-pink-50" title="Female">
                                        <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                                    </span>
                                    <?php else: ?>
                                    <span class="text-gray-300 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium <?= $cfg['badge'] ?>">
                                        <span class="w-1.5 h-1.5 rounded-full <?= $cfg['dot'] ?> shrink-0"></span>
                                        <?= $cfg['label'] ?>
                                    </span>
                                </td>
                                <td class="py-4 text-xs text-gray-500">
                                    <?= $row['note'] ? htmlspecialchars($row['note']) : '<span class="text-gray-300 italic">...</span>' ?>
                                </td>
                                <td class="py-4 text-right text-[11px] text-gray-500 font-mono"><?= $fmt_marked ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php endif; ?>
                </div>
            </div>

            <?php endif; ?>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>