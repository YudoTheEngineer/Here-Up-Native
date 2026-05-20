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
            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
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
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-start justify-between gap-4 flex-wrap">

                    <!-- Left: Session Info -->
                    <div class="flex items-start gap-4">
                        <!-- Icon Box -->
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

                            <!-- Description -->
                            <p class="text-sm text-gray-400 leading-relaxed max-w-lg">
                                <?php 
                                    if($session["description"]) { 
                                        echo htmlspecialchars($session["description"]);
                                    } else { 
                                        echo "..."; 
                                    }
                                ?>
                            </p>

                            <!-- Meta: Time & Timezone -->
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
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit Session
                        </button>

                        <button
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-400 rounded-xl text-sm font-medium hover:bg-blue-100 transition-all border border-blue-100">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Submit Attendance
                        </button>
                        <?php endif;?>
                    </div>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-4 gap-4">
                <!-- Present -->
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-green-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="check-circle-2" class="w-5 h-5 text-green-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Present</p>
                        <p class="text-xl font-bold text-gray-700">0</p>
                    </div>
                </div>
                <!-- Excused -->
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-yellow-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="file-text" class="w-5 h-5 text-yellow-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Excused</p>
                        <p class="text-xl font-bold text-gray-700">0</p>
                    </div>
                </div>
                <!-- Sick -->
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="heart-pulse" class="w-5 h-5 text-blue-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Sick</p>
                        <p class="text-xl font-bold text-gray-700">0</p>
                    </div>
                </div>
                <!-- Not Excused -->
                <div class="bg-white rounded-2xl p-5 shadow-sm flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="x-circle" class="w-5 h-5 text-red-400"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 mb-0.5">Not Excused</p>
                        <p class="text-xl font-bold text-gray-700">0</p>
                    </div>
                </div>
            </div>

            <!-- Attendance Table Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">

                <!-- Card Header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 id="attendanceTableTitle" class="text-xs font-medium text-gray-400 uppercase tracking-widest">Attendance List</h2>

                    <!-- Buttons: default view -->
                    <div id="btnGroupList" class="flex items-center gap-3">
                        <?php if ($session["is_active"] === "1"): ?>
                        <button
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                            Mark Attendance
                        </button>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="overflow-x-auto">

                    <!-- Container: Attendance List -->
                    <div id="containerAttendanceList">
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
                                    <td class="py-4 text-center">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-300 shrink-0"></span>
                                            Not Marked
                                        </span>
                                    </td>
                                    <td class="py-4">
                                        <span class="text-xs text-gray-400 italic">...</span>
                                    </td>
                                    <td class="py-4 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                                <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            </span>
                                            <span class="text-[11px] text-gray-400 mt-0.5 font-mono">00:00</span>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile;?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>