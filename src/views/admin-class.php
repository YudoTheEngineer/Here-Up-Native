<?php
// Session Validation
session_start();

if (!isset($_SESSION["session"])) {
    header("Location: ../views/sign-in.php");
    exit();
}

// Database connection
include "../../env.php";

// URL Validation
if (!isset($_GET["class_id"])) {
    header("Location: ../views/dashboard.php");
    exit();
}

// Database Validation
$class_id = $_GET["class_id"];
$user_id = $_SESSION["session"]["id"];

$user_class_query = "SELECT * FROM user_class WHERE user_id = '$user_id' AND class_id = '$class_id' LIMIT 1";
$user_class_result = mysqli_query($connection, $user_class_query);
$row = mysqli_fetch_assoc($user_class_result);

if (!$row) {
    header("Location: ../views/dashboard.php");
    exit();
}

if ($row['role'] != 1) {
    header("Location: ../views/member-class.php?class_id=".$class_id);
    exit();
}

// Check Class Mode
$class_mode_query = "SELECT * FROM `class` WHERE id = '$class_id' LIMIT 1";
$class = mysqli_fetch_assoc(mysqli_query($connection, $class_mode_query));
$member_query = "SELECT * FROM member WHERE class_id = $class_id";
$member_result = mysqli_query($connection, $member_query);
$member_total = mysqli_num_rows($member_result);
$session_query = "SELECT * FROM session WHERE class_id = $class_id";
$session_result = mysqli_query($connection, $session_query);
$session_total = mysqli_num_rows($session_result);
// Attendance Statistics
$attendance_stats = [];

$attendance_query = "
    SELECT
        member_id,
        status,
        COUNT(*) as total
    FROM attendance
    WHERE class_id = '$class_id'
    GROUP BY member_id, status
";

$attendance_result = mysqli_query($connection, $attendance_query);

while ($attendance = mysqli_fetch_assoc($attendance_result)) {
    $attendance_stats[$attendance['member_id']][$attendance['status']] = $attendance['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Admin Class | HereUp</title>

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
                <span class="text-gray-600 font-medium">Admin Class</span>
            </div>

            <!-- Class Hero Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center justify-between gap-4 flex-wrap">

                    <!-- Left: Class Info -->
                    <div>
                        <p class="text-[11px] font-semibold text-[#93C5FD] uppercase tracking-widest mb-1">Admin Class Page</p>
                        <h2 class="text-xl font-bold text-gray-800 leading-tight mb-2">
                            <?php echo htmlspecialchars($class["name"]); ?>
                        </h2>

                        <div class="flex items-center gap-2 mt-1">
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 shrink-0">
                                <i data-lucide="shield" class="w-3 h-3"></i>
                                Admin Only Mode
                            </span>
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <button onclick="openCreateSessionModal()"
                            class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                            <i data-lucide="play-circle" class="w-4 h-4"></i>
                            Create Session
                        </button>
                        <button id="btnToggleView" onclick="toggleView()"
                            class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i id="btnToggleIcon" data-lucide="history" class="w-4 h-4"></i>
                            <span id="btnToggleLabel">See All Session</span>
                        </button>
                        <button onclick="openAddMemberModal()"
                            class="inline-flex cursor-pointer items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Add Member
                        </button>
                    </div>

                </div>
            </div>

            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <h2 id="tableTitle" class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6">All Your Member Class</h2>

                <!-- Table: Members -->
                <div id="containerTableMember" class="overflow-x-auto">
                    <?php if ($member_total === 0): ?>

                    <!-- Empty state: no members -->
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3">
                            <i data-lucide="users" class="w-7 h-7 text-gray-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-300">No members yet</p>
                        <p class="text-xs text-gray-300 mt-1 mb-4">Add members to start tracking attendance</p>
                    </div>

                    <?php else: ?>

                    <table class="w-full text-sm border-collapse min-w-[750px]">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[5%]">No</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[20%]">Fullname</th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[8%]">Gender</th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-green-400"></i>
                                        Present
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-yellow-400"></i>
                                        Excused
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-blue-400"></i>
                                        Sick
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-red-400"></i>
                                        Not Excused
                                    </span>
                                </th>
                                <th class="text-right text-xs font-medium text-gray-400 tracking-wide pb-3 w-[13%]">Added At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $row_num = 1;
                                while($member = mysqli_fetch_assoc($member_result)):
                                    $present_count = $attendance_stats[$member['id']]['1'] ?? 0;
                                    $excused_count = $attendance_stats[$member['id']]['2'] ?? 0;
                                    $sick_count = $attendance_stats[$member['id']]['3'] ?? 0;
                                    $not_excused_count = $attendance_stats[$member['id']]['4'] ?? 0;
                            ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400"><?= $row_num++; ?></td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0">
                                            <img src="../../storage/profile_picture/<?= htmlspecialchars($member['profile_picture']) ?>"
                                                alt="<?= htmlspecialchars($member['fullname']) ?>"
                                                class="w-full h-full object-cover">
                                        </div>
                                        <?= htmlspecialchars($member["fullname"]); ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <?php if ($member['gender'] == 1 || strtolower($member['gender']) == 'male'): ?>
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50" title="Male">
                                            <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                                        </span>
                                    <?php elseif ($member['gender'] == 2 || strtolower($member['gender']) == 'female'): ?>
                                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-pink-50" title="Female">
                                            <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-gray-300 text-xs">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-green-50 text-green-600">
                                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                        <?= $present_count ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-yellow-50 text-yellow-600">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        <?= $excused_count ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-blue-50 text-blue-600">
                                        <i data-lucide="heart-pulse" class="w-3 h-3"></i>
                                        <?= $sick_count ?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-red-50 text-red-500">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        <?= $not_excused_count ?>
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            <?= date("d F Y", strtotime($member["created_at"])) ?>
                                        </span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 font-mono">
                                            <?= date("H:i", strtotime($member["created_at"])) ?> UTC
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                    <?php endif; ?>
                </div>

                <!-- Table: Sessions -->
                <div id="containerTableSession" class="flex flex-col gap-3 hidden">
                    <?php if ($session_total === 0): ?>

                    <!-- Empty state: no sessions -->
                    <div class="flex flex-col items-center justify-center py-16 text-center">
                        <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-3">
                            <i data-lucide="play-circle" class="w-7 h-7 text-gray-300"></i>
                        </div>
                        <p class="text-sm font-semibold text-gray-300">No sessions yet</p>
                        <p class="text-xs text-gray-300 mt-1 mb-4">Create a session to start taking attendance</p>
                    </div>

                    <?php else: ?>

                    <?php 
                        while($session = mysqli_fetch_assoc($session_result)):
                    ?>
                    <a href="session.php?class_id=<?= htmlspecialchars($class_id)?>&session_id=<?= htmlspecialchars($session["id"])?>">
                        <div class="border border-gray-100 rounded-2xl p-5 hover:border-gray-200 transition-all cursor-pointer flex flex-col">
                            <!-- Card Header -->
                            <div class="flex items-start gap-3 mb-3">
                                <div class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                                    <i data-lucide="book-open" class="w-4 h-4 text-blue-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="text-[13px] font-semibold text-gray-700 truncate">
                                            <?= htmlspecialchars($session["name"]); ?>
                                        </span>
                                        <?php if ($session["is_active"] == '1'): ?>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-[3px] rounded-full text-[10px] font-medium bg-green-50 text-green-600 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                            Active
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center gap-1.5 px-2 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                            Not Active
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if (!empty($session["description"])): ?>
                                    <p class="text-[12px] text-gray-400 mt-1 leading-relaxed line-clamp-2">
                                        <?= htmlspecialchars($session["description"]); ?>
                                    </p>
                                    <?php else: ?>
                                    <p class="text-[12px] text-gray-300 mt-1 italic font-light inline-flex items-center gap-1">
                                        <i data-lucide="help-circle" class="w-3 h-3 opacity-60"></i>
                                        No description provided
                                    </p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="border-t border-gray-50 my-0"></div>

                            <!-- Footer: Date & Timezone -->
                            <div class="flex items-center justify-between flex-wrap gap-2 mt-3">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <?= date("d M Y", strtotime($session["start_time"])); ?>
                                    </span>
                                    <span class="text-[11px] text-gray-300">→</span>
                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                        <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                        <?= date("d M Y", strtotime($session["end_time"])); ?>
                                    </span>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-wider font-mono">
                                    <i data-lucide="clock" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($session["timezone"]); ?>
                                </span>
                            </div>

                            <!-- Time Range -->
                            <div class="flex items-center gap-1.5 mt-2">
                                <i data-lucide="clock-4" class="w-3 h-3 text-gray-300"></i>
                                <span class="text-[11px] text-gray-400 font-mono">
                                    <?= date("H:i", strtotime($session["start_time"])); ?>
                                </span>
                                <span class="text-[11px] text-gray-300">–</span>
                                <span class="text-[11px] text-gray-400 font-mono">
                                    <?= date("H:i", strtotime($session["end_time"])); ?>
                                </span>
                            </div>
                        </div>
                    </a>
                    <?php endwhile; ?>

                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>

    <!-- ── Modal: Add Member ────────────────────────────────────────────────── -->
    <div id="addMemberModal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeAddMemberModal()"></div>
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Add Member</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Invite a new member to join this class</p>

            <form id="formAddMember" action="../controllers/MemberClassController.php" method="POST" enctype="multipart/form-data" class="space-y-4">

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Full Name</label>
                    <div class="relative">
                        <input type="text" id="fullname" name="fullname" placeholder="Enter member's fullname"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm">
                        <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-fullname" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-2">Gender</label>
                    <div class="flex gap-3">
                        <label id="gender-male-label" onclick="selectGender(1)"
                            class="flex-1 flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-sm text-[#565D6D]">Male</span>
                        </label>
                        <label id="gender-female-label" onclick="selectGender(2)"
                            class="flex-1 flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                            <span class="text-sm text-[#565D6D]">Female</span>
                        </label>
                    </div>
                    <input type="hidden" id="gender" name="gender" value="">
                    <p id="error-gender" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-2">Profile Photo</label>
                    <div class="flex gap-2 mb-3">
                        <button type="button" onclick="switchPhotoTab('default')" id="tab-default"
                            class="text-xs px-3 py-1.5 rounded-lg bg-[#93C5FD] text-white transition-all">Default Photo</button>
                        <button type="button" onclick="switchPhotoTab('upload')" id="tab-upload"
                            class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">Upload Photo</button>
                    </div>

                    <div id="panel-default">
                        <div class="w-full border border-[#DEE1E6] rounded-lg p-3 bg-[#FAFAFA]">
                            <p class="text-[11px] text-gray-400 mb-2">Pick one of the default photos</p>
                            <div class="grid grid-cols-5 gap-2" id="default-photo-grid">
                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                <div onclick="selectDefaultPhoto(<?= $i ?>)" id="default-opt-<?= $i ?>"
                                    class="w-full aspect-square rounded-lg overflow-hidden cursor-pointer border-2 border-transparent hover:border-[#93C5FD] transition-all">
                                    <img src="../../storage/profile_picture/default-profile-<?= $i ?>.svg" alt="Default <?= $i ?>" class="w-full h-full object-cover">
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <input type="hidden" name="default_profile_picture" id="default_photo" value="default-profile-1.svg">
                    </div>

                    <div id="panel-upload" class="hidden">
                        <div class="w-full border border-[#DEE1E6] rounded-lg p-4 flex items-center gap-4 bg-[#FAFAFA]">
                            <div class="w-14 h-14 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                <i data-lucide="image" class="w-6 h-6 text-gray-300" id="photo-placeholder-icon"></i>
                                <img id="picture-preview" class="hidden w-full h-full object-cover" src="" alt="Preview">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#565D6D] font-medium mb-0.5" id="picture-filename">No file chosen</p>
                                <p class="text-[11px] text-gray-400">JPG, PNG, WEBP — max 2MB</p>
                            </div>
                            <button type="button" onclick="document.getElementById('profile_picture').click()"
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#DEE1E6] rounded-lg text-xs text-[#565D6D] hover:bg-gray-50 transition-all">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                Browse
                            </button>
                        </div>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden" onchange="previewPhoto(event)">
                    </div>
                </div>

                <div class="flex gap-3 mt-5">
                    <button type="button" onclick="handleAddMember()"
                        class="flex-1 py-3 cursor-pointer bg-gradient-to-r from-[#7C58DF] via-[#22CCB2] to-[#626BE8] hover:opacity-90 transition-opacity duration-200 text-white rounded-xl text-sm font-medium">
                        Add Member
                    </button>
                    <button type="button" onclick="closeAddMemberModal()"
                        class="flex-1 py-3 cursor-pointer border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            </form>
        </div>
    </div>

    <!-- ── Modal: Create Session ──────────────────────────────────────────── -->
    <div id="createSessionModal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeCreateSessionModal()"></div>
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Create Session</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Create a new attendance session for this class</p>

            <form id="formCreateSession" action="../controllers/SessionController.php" method="POST" class="space-y-4">

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Session Name</label>
                    <div class="relative">
                        <input type="text" id="session_name" name="name" placeholder="e.g. attendance 1"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm">
                        <i data-lucide="bookmark" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-session_name" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">
                        Description <span class="text-gray-300">(optional)</span>
                    </label>
                    <div class="relative">
                        <textarea id="session_description" name="description" placeholder="e.g. today's activity is about..." rows="3"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm resize-none"></textarea>
                        <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Start Time</label>
                    <div class="relative">
                        <input type="text" id="start_time" name="start_time" placeholder="select start date and time" readonly
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm cursor-pointer bg-white">
                        <i data-lucide="clock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-start_time" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">End Time</label>
                    <div class="relative">
                        <input type="text" id="end_time" name="end_time" placeholder="select end date and time" readonly
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm cursor-pointer bg-white">
                        <i data-lucide="clock-4" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-end_time" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Timezone</label>
                    <div class="relative">
                        <select id="timezone" name="timezone"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm bg-white appearance-none">
                            <option value="" disabled>Select timezone</option>
                            <option value="UTC" selected>UTC+0 — UTC</option>
                            <option value="Europe/London">UTC+0 — London</option>
                            <option value="Africa/Accra">UTC+0 — Accra</option>
                            <option value="Europe/Paris">UTC+1 — Paris</option>
                            <option value="Europe/Berlin">UTC+1 — Berlin</option>
                            <option value="Europe/Rome">UTC+1 — Rome</option>
                            <option value="Africa/Lagos">UTC+1 — Lagos</option>
                            <option value="Europe/Athens">UTC+2 — Athens</option>
                            <option value="Europe/Helsinki">UTC+2 — Helsinki</option>
                            <option value="Africa/Cairo">UTC+2 — Cairo</option>
                            <option value="Africa/Johannesburg">UTC+2 — Johannesburg</option>
                            <option value="Europe/Moscow">UTC+3 — Moscow</option>
                            <option value="Asia/Riyadh">UTC+3 — Riyadh</option>
                            <option value="Africa/Nairobi">UTC+3 — Nairobi</option>
                            <option value="Asia/Baghdad">UTC+3 — Baghdad</option>
                            <option value="Asia/Tehran">UTC+3:30 — Tehran</option>
                            <option value="Asia/Dubai">UTC+4 — Dubai</option>
                            <option value="Asia/Baku">UTC+4 — Baku</option>
                            <option value="Indian/Mauritius">UTC+4 — Mauritius</option>
                            <option value="Asia/Kabul">UTC+4:30 — Kabul</option>
                            <option value="Asia/Karachi">UTC+5 — Karachi</option>
                            <option value="Asia/Tashkent">UTC+5 — Tashkent</option>
                            <option value="Asia/Kolkata">UTC+5:30 — Kolkata / Mumbai</option>
                            <option value="Asia/Colombo">UTC+5:30 — Colombo</option>
                            <option value="Asia/Kathmandu">UTC+5:45 — Kathmandu</option>
                            <option value="Asia/Dhaka">UTC+6 — Dhaka</option>
                            <option value="Asia/Almaty">UTC+6 — Almaty</option>
                            <option value="Asia/Yangon">UTC+6:30 — Yangon</option>
                            <option value="Indian/Cocos">UTC+6:30 — Cocos Islands</option>
                            <option value="Asia/Jakarta">UTC+7 — Jakarta (WIB)</option>
                            <option value="Asia/Bangkok">UTC+7 — Bangkok</option>
                            <option value="Asia/Ho_Chi_Minh">UTC+7 — Ho Chi Minh</option>
                            <option value="Asia/Phnom_Penh">UTC+7 — Phnom Penh</option>
                            <option value="Asia/Makassar">UTC+8 — Makassar (WITA)</option>
                            <option value="Asia/Singapore">UTC+8 — Singapore</option>
                            <option value="Asia/Kuala_Lumpur">UTC+8 — Kuala Lumpur</option>
                            <option value="Asia/Shanghai">UTC+8 — Shanghai</option>
                            <option value="Asia/Hong_Kong">UTC+8 — Hong Kong</option>
                            <option value="Asia/Taipei">UTC+8 — Taipei</option>
                            <option value="Asia/Manila">UTC+8 — Manila</option>
                            <option value="Australia/Perth">UTC+8 — Perth</option>
                            <option value="Australia/Eucla">UTC+8:45 — Eucla</option>
                            <option value="Asia/Jayapura">UTC+9 — Jayapura (WIT)</option>
                            <option value="Asia/Tokyo">UTC+9 — Tokyo</option>
                            <option value="Asia/Seoul">UTC+9 — Seoul</option>
                            <option value="Pacific/Palau">UTC+9 — Palau</option>
                            <option value="Australia/Darwin">UTC+9:30 — Darwin</option>
                            <option value="Australia/Adelaide">UTC+9:30 — Adelaide</option>
                            <option value="Australia/Sydney">UTC+10 — Sydney</option>
                            <option value="Australia/Brisbane">UTC+10 — Brisbane</option>
                            <option value="Pacific/Port_Moresby">UTC+10 — Port Moresby</option>
                            <option value="Pacific/Guam">UTC+10 — Guam</option>
                            <option value="Australia/Lord_Howe">UTC+10:30 — Lord Howe Island</option>
                            <option value="Pacific/Noumea">UTC+11 — Noumea</option>
                            <option value="Pacific/Guadalcanal">UTC+11 — Guadalcanal</option>
                            <option value="Pacific/Auckland">UTC+12 — Auckland</option>
                            <option value="Pacific/Fiji">UTC+12 — Fiji</option>
                            <option value="Pacific/Majuro">UTC+12 — Marshall Islands</option>
                            <option value="Pacific/Chatham">UTC+12:45 — Chatham Islands</option>
                            <option value="Pacific/Tongatapu">UTC+13 — Tonga</option>
                            <option value="Pacific/Apia">UTC+13 — Samoa</option>
                            <option value="Pacific/Kiritimati">UTC+14 — Kiritimati</option>
                            <option value="Atlantic/Azores">UTC-1 — Azores</option>
                            <option value="Atlantic/Cape_Verde">UTC-1 — Cape Verde</option>
                            <option value="America/Noronha">UTC-2 — Fernando de Noronha</option>
                            <option value="Atlantic/South_Georgia">UTC-2 — South Georgia</option>
                            <option value="America/Sao_Paulo">UTC-3 — São Paulo</option>
                            <option value="America/Buenos_Aires">UTC-3 — Buenos Aires</option>
                            <option value="America/Santiago">UTC-3 — Santiago</option>
                            <option value="America/St_Johns">UTC-3:30 — St. John's (NST)</option>
                            <option value="America/Halifax">UTC-4 — Halifax (AST)</option>
                            <option value="America/La_Paz">UTC-4 — La Paz</option>
                            <option value="America/Caracas">UTC-4 — Caracas</option>
                            <option value="America/Manaus">UTC-4 — Manaus</option>
                            <option value="America/New_York">UTC-5 — New York (EST)</option>
                            <option value="America/Toronto">UTC-5 — Toronto</option>
                            <option value="America/Bogota">UTC-5 — Bogotá</option>
                            <option value="America/Lima">UTC-5 — Lima</option>
                            <option value="America/Chicago">UTC-6 — Chicago (CST)</option>
                            <option value="America/Mexico_City">UTC-6 — Mexico City</option>
                            <option value="America/Denver">UTC-7 — Denver (MST)</option>
                            <option value="America/Phoenix">UTC-7 — Phoenix</option>
                            <option value="America/Los_Angeles">UTC-8 — Los Angeles (PST)</option>
                            <option value="America/Vancouver">UTC-8 — Vancouver</option>
                            <option value="America/Anchorage">UTC-9 — Alaska</option>
                            <option value="Pacific/Gambier">UTC-9 — Gambier Islands</option>
                            <option value="Pacific/Honolulu">UTC-10 — Honolulu (HST)</option>
                            <option value="Pacific/Tahiti">UTC-10 — Tahiti</option>
                            <option value="Pacific/Niue">UTC-11 — Niue</option>
                            <option value="Pacific/Pago_Pago">UTC-11 — Pago Pago</option>
                            <option value="Etc/GMT+12">UTC-12 — Baker Island</option>
                        </select>
                        <i data-lucide="globe" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    <p id="error-timezone" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <div class="flex gap-3 mt-5">
                    <button type="button" onclick="handleCreateSession()"
                        class="flex-1 py-3 cursor-pointer bg-gradient-to-r from-[#7C58DF] via-[#22CCB2] to-[#626BE8] hover:opacity-90 transition-opacity duration-200 text-white rounded-xl text-sm font-medium">
                        Create Session
                    </button>
                    <button type="button" onclick="closeCreateSessionModal()"
                        class="flex-1 cursor-pointer py-3 border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>

                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
            </form>
        </div>
    </div>

    <script src="../scripts/admin-class-validation.js"></script>
    <script>
        lucide.createIcons();

        document.getElementById("btnToggleView").addEventListener("click", function () {
            const tableMember  = document.getElementById("containerTableMember");
            const tableSession = document.getElementById("containerTableSession");
            const btnLabel     = document.getElementById("btnToggleLabel");
            const btnIcon      = document.getElementById("btnToggleIcon");
            const tableTitle   = document.getElementById("tableTitle");

            if (!tableMember || !tableSession || !btnLabel || !btnIcon || !tableTitle) return;

            const isShowingMember = !tableMember.classList.contains("hidden");

            if (isShowingMember) {
                tableMember.classList.add("hidden");
                tableSession.classList.remove("hidden");
                btnLabel.textContent = "See All Members";
                btnIcon.setAttribute("data-lucide", "users");
                tableTitle.textContent = "All Your Session Class";
            } else {
                tableMember.classList.remove("hidden");
                tableSession.classList.add("hidden");
                btnLabel.textContent = "See All Session";
                tableTitle.textContent = "All Your Member Class";
                btnIcon.setAttribute("data-lucide", "history");
            }

            lucide.createIcons();
        });
    </script>
</body>
</html>