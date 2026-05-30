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

// Amankan input dari SQL Injection
$search = '';
if (isset($_GET['search']) && $_GET['search'] !== '') {
    $search = mysqli_real_escape_string($connection, trim($_GET['search']));
}

$filter_status = '';
if (isset($_GET['status']) && $_GET['status'] !== '') {
    $filter_status = mysqli_real_escape_string($connection, trim($_GET['status']));
}

$session_query = "
    SELECT 
        s.id as session_id,
        s.class_id,
        s.name,
        s.description,
        s.start_time,
        s.end_time,
        s.is_active,
        s.timezone,
        c.name as class_name,
        c.profile_picture as class_picture,
        c.mode,
        u.username as creator_username,
        u.profile_picture as creator_picture
    FROM session s
    JOIN class c ON s.class_id = c.id
    JOIN user u ON s.created_by = u.id
    WHERE s.class_id IN (
        SELECT class_id FROM user_class WHERE user_id = '$user_id'
    )
";

// Tambahkan logika pencarian jika ada
if ($search !== '') {
    $session_query .= " AND (s.name LIKE '%$search%' OR c.name LIKE '%$search%')";
}

// Tambahkan logika filter status jika dipilih (0 atau 1)
if ($filter_status !== '') {
    $session_query .= " AND s.is_active = '$filter_status'";
}

$session_query .= " ORDER BY s.is_active DESC, s.start_time ASC";

$session_result = mysqli_query($connection, $session_query);
$total = mysqli_num_rows($session_result);

// Karena sudah difilter oleh SQL, kita tidak perlu memisahkan array secara manual lagi
$all_sessions = [];
while ($row = mysqli_fetch_assoc($session_result)) {
    $all_sessions[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Attendance | HereUp</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .table-scroll-area { max-height: 280px; overflow-y: auto; }
    </style>
</head>
<body class="bg-[#F8FAFC] min-h-screen flex">

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
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-[#93C5FD] text-white rounded-xl font-medium transition-all">
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

    <div class="flex-1 ml-64 flex flex-col">
        <header class="h-[75px] border-b border-gray-200 bg-white flex items-center justify-between px-8 sticky top-0 z-10">
            <div class="relative w-1/3">
                <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none">
                    <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                </div>
                <input type="text" placeholder="Search session or class" class="w-full bg-[#F1F5F9] border-none rounded-full py-2.5 pl-11 pr-4 text-sm focus:ring-2 focus:ring-blue-100 outline-none transition-all placeholder:text-gray-400">
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

        <main class="p-10">
            <div class="bg-white rounded-[2rem] p-8 min-h-[calc(100vh-155px)] flex flex-col shadow-sm">
                <div class="flex items-center justify-between mb-8 gap-4 flex-wrap">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Attendance</h2>
                        <p class="text-sm text-gray-400 mt-1">All sessions from your joined classes</p>
                    </div>

                    <form method="GET" class="flex gap-3 flex-wrap">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search session..." class="bg-gray-50 border border-gray-100 rounded-xl py-2 pl-9 pr-4 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-100 w-44 placeholder:text-gray-400">
                        </div>

                        <select name="status" onchange="this.form.submit()" class="bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                            <option value="" <?= $filter_status === '' ? 'selected' : '' ?>>All Status</option>
                            <option value="1" <?= $filter_status === '1' ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?= $filter_status === '0' ? 'selected' : '' ?>>Inactive</option>
                        </select>

                        <button type="submit" class="flex cursor-pointer items-center gap-2 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Search
                        </button>

                        <?php if ($search !== '' || $filter_status !== ''): ?>
                        <a href="attendance.php" class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-400 rounded-xl text-sm font-medium hover:bg-red-100 transition-all">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Reset
                        </a>
                        <?php endif; ?>
                    </form>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
                    <?php foreach ($all_sessions as $session): ?>
                    <?php
                        $isActive = ((int)$session['is_active'] === 1);
                        $statusLabel = $isActive ? 'Active' : 'Not Active';
                        $statusClass = $isActive ? 'bg-emerald-50 text-emerald-500' : 'bg-gray-100 text-gray-500';
                        $modeLabel = $session['timezone'] !== '' ? $session['timezone'] : 'Timezone';
                    ?>
                    <a href="session.php?class_id=<?= urlencode($session['class_id']) ?>&session_id=<?= urlencode($session['session_id']) ?>">
                        <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition-all cursor-pointer flex flex-col h-[220px]">
                            <div class="grid mb-3 overflow-hidden shrink-0" style="grid-template-columns: 48px 1fr auto; column-gap: 12px; align-items: start; height: 54px;">
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-blue-50 shrink-0 flex items-center justify-center">
                                    <i data-lucide="book-open" class="w-5 h-5 text-blue-400"></i>
                                </div>

                                <div class="overflow-hidden pt-px">
                                    <h3 class="text-[13px] font-semibold text-gray-700 leading-[1.35] break-words line-clamp-2 overflow-hidden"><?= htmlspecialchars($session['name']) ?></h3>
                                    <p class="text-[11px] text-gray-400 mt-0.5"><?= htmlspecialchars($session['class_name']) ?></p>
                                </div>

                                <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-full text-[10px] font-bold uppercase tracking-[0.05em] whitespace-nowrap shrink-0 <?= $statusClass ?>">
                                    <i data-lucide="squircle-dashed" class="w-3 h-3"></i>
                                    <?= $statusLabel ?>
                                </span>
                            </div>

                            <p class="h-8 text-[12px] text-gray-400 leading-[1.5] line-clamp-2 overflow-hidden shrink-0 mb-3">
                                <?= htmlspecialchars($session['description'] !== '' ? $session['description'] : '...') ?>
                            </p>

                            <div class="flex-1"></div>

                            <div class="flex items-center gap-2 mb-3 shrink-0">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 shrink-0">
                                    <i data-lucide="globe" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($modeLabel) ?>
                                </span>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 shrink-0">
                                    <i data-lucide="clock-3" class="w-3 h-3"></i>
                                    <?= date('H:i', strtotime($session['start_time'])) ?> - <?= date('H:i', strtotime($session['end_time'])) ?>
                                </span>
                            </div>

                            <div class="flex items-center justify-between pt-3 border-t border-gray-50 shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-5 h-5 rounded-full overflow-hidden bg-gray-100 shrink-0">
                                        <img src="../../storage/profile_picture/<?= htmlspecialchars($session['creator_picture']) ?>" alt="Creator" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[11px] text-gray-400 truncate"><?= htmlspecialchars($session['creator_username']) ?></span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-400 shrink-0">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <?= date('d-m-Y', strtotime($session['start_time'])) ?>
                                </span>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>

                <?php if ($total === 0): ?>
                <div class="flex-1 flex flex-col items-center justify-center py-20 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                        <i data-lucide="clipboard-x" class="w-8 h-8 text-gray-300"></i>
                    </div>
                    <p class="text-sm font-medium text-gray-400">
                        <?= ($search !== '' || $filter_status !== '') ? 'No sessions match your filter' : 'No sessions right now' ?>
                    </p>
                    <p class="text-xs text-gray-300 mt-1">
                        <?= ($search !== '' || $filter_status !== '') ? 'Try different keywords or reset the filter' : 'Your attendance sessions will appear here' ?>
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>