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
$session_query = "SELECT * FROM session WHERE created_by = '$user_id' AND class_id = $class_id LIMIT 1";
$session = mysqli_fetch_assoc(mysqli_query($connection, $session_query));
if (!$user_class || !$session) {
    header("Location: ../views/dashboard.php");
    exit();
}
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
                                    <!-- Ganti dengan: <?php // echo htmlspecialchars($session["name"]); ?> -->
                                    Session Name Placeholder
                                </h2>
                                <!-- Status Badge — ganti kondisi dengan: $session["is_active"] == 1 -->
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-green-50 text-green-600 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                    Active
                                </span>
                                <!-- Contoh status closed (tampilkan salah satu sesuai kondisi):
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-[3px] rounded-full text-[10px] font-medium bg-gray-50 text-gray-400 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300"></span>
                                    Closed
                                </span>
                                -->
                            </div>

                            <!-- Description -->
                            <p class="text-sm text-gray-400 leading-relaxed max-w-lg">
                                <!-- Ganti dengan: <?php // echo htmlspecialchars($session["description"]); ?> -->
                                Session description placeholder. Describes what this session is about.
                            </p>

                            <!-- Meta: Time & Timezone -->
                            <div class="flex items-center gap-2 flex-wrap mt-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <!-- <?php // echo date("d M Y", strtotime($session["start_time"])); ?> -->
                                    01 Jan 2025
                                </span>
                                <span class="text-[11px] text-gray-300">→</span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500">
                                    <i data-lucide="calendar-check" class="w-3 h-3"></i>
                                    <!-- <?php // echo date("d M Y", strtotime($session["end_time"])); ?> -->
                                    01 Jan 2025
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 font-mono">
                                    <i data-lucide="clock-4" class="w-3 h-3"></i>
                                    <!-- <?php // echo date("H:i", strtotime($session["start_time"])); ?> – <?php // echo date("H:i", strtotime($session["end_time"])); ?> -->
                                    08:00 – 10:00
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[10px] font-bold text-gray-500 uppercase tracking-wider font-mono">
                                    <i data-lucide="globe" class="w-3 h-3"></i>
                                    <!-- <?php // echo htmlspecialchars($session["timezone"]); ?> -->
                                    UTC+7
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Action Buttons -->
                    <div class="flex items-center gap-3 flex-wrap">
                        <button onclick="openEditSessionModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="pencil" class="w-4 h-4"></i>
                            Edit Session
                        </button>
                        <!-- Toggle active/close — ganti logika sesuai $session["is_active"] -->
                        <button
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-50 text-red-400 rounded-xl text-sm font-medium hover:bg-red-100 transition-all border border-red-100">
                            <i data-lucide="stop-circle" class="w-4 h-4"></i>
                            Close Session
                        </button>
                        <!-- Jika session sudah closed, tampilkan tombol ini:
                        <button
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-50 text-green-500 rounded-xl text-sm font-medium hover:bg-green-100 transition-all border border-green-100">
                            <i data-lucide="play-circle" class="w-4 h-4"></i>
                            Reopen Session
                        </button>
                        -->
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

            <!-- Attendance Table -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest">Attendance List</h2>
                    <button onclick="openMarkAttendanceModal()"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                        <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                        Mark Attendance
                    </button>
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
                            <!-- Placeholder rows — ganti dengan loop dari database -->
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">1</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="../../storage/profile_picture/default-profile-1.svg" alt="Member" class="w-full h-full object-cover">
                                        </div>
                                        John Doe
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50" title="Male">
                                        <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-green-50 text-green-600">
                                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                        Present
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="text-xs text-gray-400 italic">—</span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            01 January 2025
                                        </span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 font-mono">08:45 UTC</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">2</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="../../storage/profile_picture/default-profile-2.svg" alt="Member" class="w-full h-full object-cover">
                                        </div>
                                        Jane Smith
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-pink-50" title="Female">
                                        <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-yellow-50 text-yellow-600">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        Excused
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="text-xs text-gray-500">Family event</span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            01 January 2025
                                        </span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 font-mono">08:30 UTC</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">3</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="../../storage/profile_picture/default-profile-3.svg" alt="Member" class="w-full h-full object-cover">
                                        </div>
                                        Alex Tan
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-blue-50" title="Male">
                                        <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-blue-50 text-blue-600">
                                        <i data-lucide="heart-pulse" class="w-3 h-3"></i>
                                        Sick
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="text-xs text-gray-500">Fever since yesterday</span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            01 January 2025
                                        </span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 font-mono">09:00 UTC</span>
                                    </div>
                                </td>
                            </tr>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">4</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0 bg-gray-100">
                                            <img src="../../storage/profile_picture/default-profile-4.svg" alt="Member" class="w-full h-full object-cover">
                                        </div>
                                        Rina Kusuma
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-pink-50" title="Female">
                                        <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-red-50 text-red-500">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        Not Excused
                                    </span>
                                </td>
                                <td class="py-4">
                                    <span class="text-xs text-gray-400 italic">—</span>
                                </td>
                                <td class="py-4 text-right">
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center gap-1 text-xs font-medium text-gray-700">
                                            <i data-lucide="calendar" class="w-3 h-3 text-gray-400"></i>
                                            01 January 2025
                                        </span>
                                        <span class="text-[11px] text-gray-400 mt-0.5 font-mono">09:15 UTC</span>
                                    </div>
                                </td>
                            </tr>
                            <!-- Empty state — tampilkan jika tidak ada data:
                            <tr>
                                <td colspan="6" class="py-12">
                                    <div class="flex flex-col items-center justify-center gap-2 text-gray-400">
                                        <i data-lucide="clipboard" class="w-8 h-8 text-gray-300"></i>
                                        <span class="text-xs italic font-medium">No attendance recorded yet for this session.</span>
                                    </div>
                                </td>
                            </tr>
                            -->
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <!-- ── Modal: Mark Attendance ───────────────────────────────────────────── -->
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
            <p class="text-sm text-[#565D6D] mb-4">Record attendance status for a member in this session</p>

            <form id="formMarkAttendance" action="../controllers/AttendanceController.php" method="POST" class="space-y-4">

                <!-- Member Select -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Member</label>
                    <div class="relative">
                        <select
                            id="attendance_member"
                            name="member_id"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm bg-white appearance-none"
                        >
                            <option value="" disabled selected>Select a member</option>
                            <!-- Loop member dari database di sini -->
                            <option value="1">John Doe</option>
                            <option value="2">Jane Smith</option>
                            <option value="3">Alex Tan</option>
                            <option value="4">Rina Kusuma</option>
                        </select>
                        <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                        <i data-lucide="chevron-down" class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                    <p id="error-attendance_member" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <!-- Status -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-2">Attendance Status</label>
                    <div class="grid grid-cols-2 gap-2">
                        <label id="status-present-label" onclick="selectStatus('present')"
                            class="flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="check-circle-2" class="w-4 h-4 text-green-400"></i>
                            <span class="text-sm text-[#565D6D]">Present</span>
                        </label>
                        <label id="status-excused-label" onclick="selectStatus('excused')"
                            class="flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="file-text" class="w-4 h-4 text-yellow-400"></i>
                            <span class="text-sm text-[#565D6D]">Excused</span>
                        </label>
                        <label id="status-sick-label" onclick="selectStatus('sick')"
                            class="flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="heart-pulse" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-sm text-[#565D6D]">Sick</span>
                        </label>
                        <label id="status-absent-label" onclick="selectStatus('absent')"
                            class="flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="x-circle" class="w-4 h-4 text-red-400"></i>
                            <span class="text-sm text-[#565D6D]">Not Excused</span>
                        </label>
                    </div>
                    <input type="hidden" id="attendance_status" name="status" value="">
                    <p id="error-attendance_status" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <!-- Note -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">
                        Note <span class="text-gray-300">(optional)</span>
                    </label>
                    <div class="relative">
                        <textarea
                            id="attendance_note"
                            name="note"
                            placeholder="e.g. left early, doctor's appointment..."
                            rows="3"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm resize-none"
                        ></textarea>
                        <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-5">
                    <button
                        type="button"
                        onclick="handleMarkAttendance()"
                        class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl text-sm font-medium hover:opacity-90 transition-all">
                        Save Attendance
                    </button>
                    <button
                        type="button"
                        onclick="closeMarkAttendanceModal()"
                        class="flex-1 py-3 border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>

                <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($session_id); ?>">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">
            </form>
        </div>
    </div>

    <!-- ── Modal: Edit Session ─────────────────────────────────────────────── -->
    <div id="editSessionModal" class="fixed inset-0 hidden items-center justify-center z-50">

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeEditSessionModal()"></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Edit Session</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Update the details of this session</p>

            <form id="formEditSession" action="../controllers/SessionController.php" method="POST" class="space-y-4">
                <input type="hidden" name="_method" value="PUT">
                <input type="hidden" name="session_id" value="<?php echo htmlspecialchars($session_id); ?>">
                <input type="hidden" name="class_id" value="<?php echo htmlspecialchars($class_id); ?>">

                <!-- Session Name -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Session Name</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="edit_session_name"
                            name="name"
                            placeholder="e.g. attendance 1"
                            value="Session Name Placeholder"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm"
                        >
                        <i data-lucide="bookmark" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-edit_session_name" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <!-- Description -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">
                        Description <span class="text-gray-300">(optional)</span>
                    </label>
                    <div class="relative">
                        <textarea
                            id="edit_session_description"
                            name="description"
                            placeholder="e.g. today's activity is about..."
                            rows="3"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm resize-none"
                        >Session description placeholder.</textarea>
                        <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Start Time -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Start Time</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="edit_start_time"
                            name="start_time"
                            placeholder="select start date and time"
                            readonly
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm cursor-pointer bg-white"
                        >
                        <i data-lucide="clock" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-edit_start_time" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <!-- End Time -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">End Time</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="edit_end_time"
                            name="end_time"
                            placeholder="select end date and time"
                            readonly
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm cursor-pointer bg-white"
                        >
                        <i data-lucide="clock-4" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-edit_end_time" class="hidden text-xs text-red-400 mt-1"></p>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 mt-5">
                    <button
                        type="button"
                        onclick="handleEditSession()"
                        class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl text-sm font-medium hover:opacity-90 transition-all">
                        Save Changes
                    </button>
                    <button
                        type="button"
                        onclick="closeEditSessionModal()"
                        class="flex-1 py-3 border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        // ── Modal: Mark Attendance ──────────────────────────────────────────
        function openMarkAttendanceModal() {
            const modal = document.getElementById("markAttendanceModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");
        }
        function closeMarkAttendanceModal() {
            const modal = document.getElementById("markAttendanceModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        // ── Modal: Edit Session ─────────────────────────────────────────────
        function openEditSessionModal() {
            const modal = document.getElementById("editSessionModal");
            modal.classList.remove("hidden");
            modal.classList.add("flex");

            // Init flatpickr inside modal
            flatpickr("#edit_start_time", { enableTime: true, dateFormat: "Y-m-d H:i", time_24hr: true });
            flatpickr("#edit_end_time",   { enableTime: true, dateFormat: "Y-m-d H:i", time_24hr: true });

            lucide.createIcons();
        }
        function closeEditSessionModal() {
            const modal = document.getElementById("editSessionModal");
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }

        // ── Status Selector ─────────────────────────────────────────────────
        const statusKeys = ["present", "excused", "sick", "absent"];
        function selectStatus(selected) {
            statusKeys.forEach(key => {
                const el = document.getElementById("status-" + key + "-label");
                if (el) {
                    el.classList.toggle("border-[#93C5FD]", key === selected);
                    el.classList.toggle("bg-blue-50",       key === selected);
                    el.classList.toggle("border-[#DEE1E6]", key !== selected);
                    el.classList.remove("bg-blue-50");
                }
            });
            const el = document.getElementById("status-" + selected + "-label");
            if (el) { el.classList.add("border-[#93C5FD]", "bg-blue-50"); }
            document.getElementById("attendance_status").value = selected;
        }

        // ── Placeholder handlers (belum aktif) ─────────────────────────────
        function handleMarkAttendance() { /* TODO */ }
        function handleEditSession()    { /* TODO */ }
    </script>
</body>
</html>