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

// Get User ID
$user_id = $_SESSION["session"]["id"];

// Get Search & Filter
$search      = isset($_GET['search']) ? $_GET['search'] : '';
$filter_role = isset($_GET['role']) ? $_GET['role'] : '';

// Single JOIN query with search & filter
$user_class_query = "
    SELECT uc.role, c.id as class_id, c.name, c.description, c.mode, c.unique_code,
    c.profile_picture, c.created_at,
    u.username as creator_username, u.profile_picture as creator_picture
    FROM user_class uc
    JOIN class c ON uc.class_id = c.id
    JOIN user u ON c.created_by = u.id
    WHERE uc.user_id = '$user_id'
";

if ($search !== '') {
    $user_class_query .= " AND c.name LIKE '%$search%'";
}

if ($filter_role !== '') {
    $user_class_query .= " AND uc.role = '$filter_role'";
}

$user_class_result = mysqli_query($connection, $user_class_query);
$total             = mysqli_num_rows($user_class_result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Class | HereUp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
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
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-[#93C5FD] text-white rounded-xl font-medium transition-all">
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
                        <img src="../../storage/profile_picture/<?= htmlspecialchars($_SESSION['session']['profile_picture'])?>" alt="Avatar" class="w-full h-full object-cover">
                    </div>
                    <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['session']['username']); ?></span>
                </div>
            </div>
        </header>

        <main class="p-10">
            <div class="bg-white rounded-[2rem] p-8 min-h-[calc(100vh-155px)] flex flex-col shadow-sm">

                <!-- Page Header -->
                <div class="flex items-center justify-between mb-8 gap-4 flex-wrap">
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">My Classes</h2>
                        <p class="text-sm text-gray-400 mt-1">
                            All Your Joined or Created Classes
                        </p>
                    </div>

                    <div class="flex gap-3 flex-wrap">

                        <form method="GET" class="flex gap-3 flex-wrap">
                            <div class="relative">
                                <div class="absolute inset-y-0 left-3 flex items-center pointer-events-none">
                                    <i data-lucide="search" class="w-3.5 h-3.5 text-gray-400"></i>
                                </div>
                                <input type="text" name="search"
                                    value="<?= htmlspecialchars($search) ?>"
                                    placeholder="Search class..."
                                    class="bg-gray-50 border border-gray-100 rounded-xl py-2 pl-9 pr-4 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-100 w-44 placeholder:text-gray-400">
                            </div>

                            <select name="role" onchange="this.form.submit()"
                                class="bg-gray-50 border border-gray-100 rounded-xl py-2 px-3 text-sm text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-100 cursor-pointer">
                                <option value="" <?= $filter_role === '' ? 'selected' : '' ?>>All Role</option>
                                <option value="1" <?= $filter_role === '1' ? 'selected' : '' ?>>Admin</option>
                                <option value="2" <?= $filter_role === '2' ? 'selected' : '' ?>>Member</option>
                            </select>

                            <button type="submit"
                                class="flex items-center gap-2 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                                <i data-lucide="search" class="w-4 h-4"></i>
                                Search
                            </button>

                            <?php if ($search !== '' || $filter_role !== ''): ?>
                            <a href="class.php"
                                class="flex items-center gap-2 px-4 py-2 bg-red-50 text-red-400 rounded-xl text-sm font-medium hover:bg-red-100 transition-all">
                                <i data-lucide="x" class="w-4 h-4"></i>
                                Reset
                            </a>
                            <?php endif; ?>
                        </form>

                        <button class="flex items-center gap-2 px-5 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                            New Class
                        </button>

                    </div>
                </div>

                <!-- Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">

                    <?php while ($row = mysqli_fetch_assoc($user_class_result)):

                        $class_id  = $row["class_id"];
                        $user_role = ['role' => $row["role"]];
                        $class     = [
                            'name'            => $row["name"],
                            'description'     => $row["description"],
                            'mode'            => $row["mode"],
                            'unique_code'     => $row["unique_code"],
                            'profile_picture' => $row["profile_picture"],
                            'created_at'      => $row["created_at"],
                        ];
                        $creator   = [
                            'username'        => $row["creator_username"],
                            'profile_picture' => $row["creator_picture"],
                        ];

                        $member_count_query = "SELECT COUNT(*) as total FROM user_class WHERE class_id = '$class_id'";
                        $member_count       = mysqli_fetch_assoc(mysqli_query($connection, $member_count_query));
                    ?>
                    <a href="<?= $class["mode"] === '1' ? htmlspecialchars('admin-class.php?class_id='.$class_id) : htmlspecialchars('member-class.php?class_id='.$class_id)?>">
                        <div class="border border-gray-100 rounded-2xl p-5 hover:shadow-md transition-all cursor-pointer flex flex-col h-[220px]">
                            <!-- card-header -->
                            <div class="grid mb-3 overflow-hidden shrink-0" style="grid-template-columns: 48px 1fr auto; column-gap: 12px; align-items: start; height: 54px;">
                                <!-- Kolom 1: Foto -->
                                <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-100 shrink-0">
                                    <img src="../../storage/class_profile_picture/<?= htmlspecialchars($class["profile_picture"])?>" alt="Class" class="w-full h-full object-cover block">
                                </div>

                                <!-- Kolom 2: Nama + member -->
                                <div class="overflow-hidden pt-px">
                                    <h3 class="text-[13px] font-semibold text-gray-700 leading-[1.35] break-words line-clamp-2 overflow-hidden"><?= htmlspecialchars($class["name"]) ?></h3>
                                    <p class="text-[11px] text-gray-400 mt-0.5"><?= $member_count['total'] . ' Member' . ($member_count['total'] != 1 ? 's' : '') ?></p>
                                </div>

                                <!-- Kolom 3: Badge -->
                                <?php if ($user_role["role"] == 1): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-full text-[10px] font-bold uppercase tracking-[0.05em] whitespace-nowrap shrink-0 bg-blue-50 text-blue-500">
                                    <i data-lucide="shield-check" class="w-3 h-3"></i>
                                    Admin
                                </span>
                                <?php else: ?>
                                <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-full text-[10px] font-bold uppercase tracking-[0.05em] whitespace-nowrap shrink-0 bg-green-50 text-green-500">
                                    <i data-lucide="user" class="w-3 h-3"></i>
                                    Member
                                </span>
                                <?php endif; ?>

                            </div>

                            <!-- Description -->
                            <p class="h-8 text-[12px] text-gray-400 leading-[1.5] line-clamp-2 overflow-hidden shrink-0 mb-3">
                                <?= $class["description"] !== "" ? htmlspecialchars($class["description"]) : "..." ?>
                            </p>

                            <!-- Spacer -->
                            <div class="flex-1"></div>

                            <!-- Info Row -->
                            <div class="flex items-center gap-2 mb-3 shrink-0">
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 shrink-0">
                                    <?php if ($class["mode"] == "1"): ?>
                                    <i data-lucide="shield" class="w-3 h-3"></i>
                                    Admin Only
                                    <?php elseif ($class["mode"] == "2"): ?>
                                    <i data-lucide="users" class="w-3 h-3"></i>
                                    Member
                                    <?php else: ?>
                                    <i data-lucide="qr-code" class="w-3 h-3"></i>
                                    QR Code
                                    <?php endif; ?>
                                </span>

                                <?php if($class["mode"] !== "1"):?>
                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 rounded-lg text-[11px] text-gray-500 font-mono shrink-0">
                                    <i data-lucide="key" class="w-3 h-3"></i>
                                    <?= htmlspecialchars($class["unique_code"]) ?>
                                </span>
                                <?php endif;?>
                            </div>

                            <!-- Card Footer -->
                            <div class="flex items-center justify-between pt-3 border-t border-gray-50 shrink-0">
                                <div class="flex items-center gap-2 min-w-0">
                                    <div class="w-5 h-5 rounded-full overflow-hidden bg-gray-100 shrink-0">
                                        <img src="../../storage/profile_picture/<?= htmlspecialchars($creator["profile_picture"])?>"
                                            alt="Creator" class="w-full h-full object-cover">
                                    </div>
                                    <span class="text-[11px] text-gray-400 truncate"><?= htmlspecialchars($creator["username"]) ?></span>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[11px] text-gray-400 shrink-0">
                                    <i data-lucide="calendar" class="w-3 h-3"></i>
                                    <?= date('d-m-Y', strtotime($class["created_at"])) ?>
                                </span>
                            </div>

                        </div>

                        <?php endwhile; ?>

                    </a>

                    <!-- Empty State -->
                    <?php if ($total === 0): ?>
                    <div class="col-span-3 flex flex-col items-center justify-center py-20 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4">
                            <i data-lucide="presentation" class="w-8 h-8 text-gray-300"></i>
                        </div>
                        <p class="text-sm font-medium text-gray-400">
                            <?= ($search !== '' || $filter_role !== '') ? 'No classes match your search' : 'No classes yet' ?>
                        </p>
                        <p class="text-xs text-gray-300 mt-1">
                            <?= ($search !== '' || $filter_role !== '') ? 'Try different keywords or reset the filter' : 'Create or join a class to get started' ?>
                        </p>
                    </div>
                    <?php endif; ?>

                </div>

            </div>
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>