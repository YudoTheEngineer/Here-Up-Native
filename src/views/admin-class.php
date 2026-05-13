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
$class_mode_result = mysqli_fetch_assoc(mysqli_query($connection, $class_mode_query));
$class_mode = $class_mode_result["mode"];
$class_name = $class_mode_result["name"];
$class_code = $class_mode_result["unique_code"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin ClassPage | HereUp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        body { font-family: 'Segoe UI', sans-serif; }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.18s ease;
            border: none;
            outline: none;
        }
        .btn-action:hover { transform: translateY(-1px); }
        .btn-action:active { transform: translateY(0); }

        .btn-primary {
            background: #93C5FD;
            color: #1e40af;
        }
        .btn-primary:hover { background: #7BB8FB; box-shadow: 0 4px 12px rgba(147,197,253,0.45); }

        .btn-secondary {
            background: #F1F5F9;
            color: #64748b;
        }
        .btn-secondary:hover { background: #E2E8F0; }

        .btn-outline {
            background: white;
            color: #64748b;
            border: 1.5px solid #E2E8F0;
        }
        .btn-outline:hover { background: #F8FAFC; border-color: #CBD5E1; }

        .attendance-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 3px 9px;
            border-radius: 7px;
            font-size: 11px;
            font-weight: 500;
        }
        .badge-hadir  { background: #DCFCE7; color: #16a34a; }
        .badge-izin   { background: #FEF9C3; color: #ca8a04; }
        .badge-sakit  { background: #DBEAFE; color: #2563eb; }
        .badge-alpha  { background: #FEE2E2; color: #dc2626; }

        .class-hero {
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 60%, #E0F2FE 100%);
            border: 1px solid #BFDBFE;
        }

        .code-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: white;
            border: 1.5px dashed #93C5FD;
            color: #2563eb;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.08em;
            cursor: pointer;
            transition: all 0.15s;
            user-select: none;
        }
        .code-pill:hover { background: #EFF6FF; }
        .code-pill:active { transform: scale(0.97); }

        .copy-tooltip {
            position: relative;
        }
        .copy-tooltip::after {
            content: 'Copied!';
            position: absolute;
            top: -28px;
            left: 50%;
            transform: translateX(-50%);
            background: #1e40af;
            color: white;
            padding: 3px 8px;
            border-radius: 6px;
            font-size: 11px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }
        .copy-tooltip.show::after { opacity: 1; }

        table th, table td { vertical-align: middle; }

        .table-container {
            overflow-x: auto;
        }
        table {
            min-width: 700px;
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
                        <img src="../../storage/profile-default-male.jpg" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['session']['username']); ?></span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="p-10 space-y-6">

            <!-- Class Hero Card -->
            <div class="class-hero rounded-2xl px-8 py-6 flex items-center justify-between">
                <div>
                    <!-- Class Name -->
                    <p class="text-[11px] font-semibold text-blue-400 uppercase tracking-widest mb-1">Admin Class Page</p>
                    <h2 class="text-2xl font-bold text-blue-900 leading-tight mb-2">
                        <?php echo htmlspecialchars($class_name); ?>
                    </h2>

                    <?php if ($class_mode === "1") :?>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[11px] text-blue-400 font-medium">Admin Only Mode</span>
                    </div>

                    <?php else:?>
                    <!-- Invitation Code -->
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-[11px] text-blue-400 font-medium">Invitation Code</span>
                        <span class="code-pill copy-tooltip" id="codeBtn" onclick="copyCode(this)" title="Click for Copy">
                            <i data-lucide="key-round" class="w-3 h-3"></i>
                            <?php echo htmlspecialchars($class_code); ?>
                            <i data-lucide="copy" class="w-3 h-3 opacity-50"></i>
                        </span>
                    </div>

                    <?php endif;?>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 flex-shrink-0">
                    <button class="btn-action btn-primary" onclick="alert('Create Session')">
                        <i data-lucide="play-circle" class="w-4 h-4"></i>
                        Create Session
                    </button>
                    <button class="btn-action btn-outline" onclick="alert('Session History')">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        See All Session
                    </button>
                    <button class="btn-action btn-outline" onclick="alert('Add Member')">
                        <i data-lucide="user-plus" class="w-4 h-4"></i>
                        Add Member
                    </button>
                </div>
            </div>

            <!-- Member Table Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6">All Your Member Class</h2>

                <div class="table-container">
                    <table class="w-full text-sm border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[5%]">No</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[22%]">Fullname</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[20%]">Username</th>
                                <!-- Attendance columns -->
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="check-circle-2" class="w-3.5 h-3.5 text-green-400"></i>
                                        Hadir
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="file-text" class="w-3.5 h-3.5 text-yellow-400"></i>
                                        Izin
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="heart-pulse" class="w-3.5 h-3.5 text-blue-400"></i>
                                        Sakit
                                    </span>
                                </th>
                                <th class="text-center text-xs font-medium text-gray-400 tracking-wide pb-3 w-[10%]">
                                    <span class="inline-flex items-center gap-1 justify-center">
                                        <i data-lucide="x-circle" class="w-3.5 h-3.5 text-red-400"></i>
                                        Alpha
                                    </span>
                                </th>
                                <th class="text-right text-xs font-medium text-gray-400 tracking-wide pb-3 w-[13%]">Added At</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Row 1 -->
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">1</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 rounded-md px-2.5 py-1 text-xs text-gray-500">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        Yudo Harun Wardhana
                                    </span>
                                </td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <span class="w-[22px] h-[22px] rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-medium text-blue-500">Y</span>
                                        YudoTheEngineer
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-hadir">
                                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                        12
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-izin">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        2
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-sakit">
                                        <i data-lucide="heart-pulse" class="w-3 h-3"></i>
                                        1
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-alpha">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        0
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="inline-flex items-center justify-end gap-1.5 text-xs text-gray-400">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        11-09-2024
                                    </span>
                                </td>
                            </tr>

                            <!-- Row 2 (example) -->
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400">2</td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 bg-gray-100 rounded-md px-2.5 py-1 text-xs text-gray-500">
                                        <i data-lucide="user" class="w-3 h-3"></i>
                                        Siti Rahmawati
                                    </span>
                                </td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <span class="w-[22px] h-[22px] rounded-full bg-purple-50 flex items-center justify-center text-[10px] font-medium text-purple-500">S</span>
                                        SitiDev
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-hadir">
                                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                        10
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-izin">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        1
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-sakit">
                                        <i data-lucide="heart-pulse" class="w-3 h-3"></i>
                                        3
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="attendance-badge badge-alpha">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        1
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="inline-flex items-center justify-end gap-1.5 text-xs text-gray-400">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        15-09-2024
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <script>
        lucide.createIcons();

        function copyCode(el) {
            const text = el.innerText.trim();
            navigator.clipboard.writeText(text).then(() => {
                el.classList.add('show');
                setTimeout(() => el.classList.remove('show'), 1500);
            });
        }
    </script>
</body>
</html>