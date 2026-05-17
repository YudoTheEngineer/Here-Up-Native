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
                        <button onclick="alert('Create Session')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#93C5FD] text-white rounded-xl text-sm font-medium hover:bg-blue-400 transition-all shadow-sm">
                            <i data-lucide="play-circle" class="w-4 h-4"></i>
                            Create Session
                        </button>
                        <button onclick="alert('Session History')"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="history" class="w-4 h-4"></i>
                            See All Session
                        </button>
                        <button onclick="openAddMemberModal()"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-50 text-gray-600 rounded-xl text-sm font-medium hover:bg-gray-100 transition-all border border-gray-100">
                            <i data-lucide="user-plus" class="w-4 h-4"></i>
                            Add Member
                        </button>
                    </div>

                </div>
            </div>

            <!-- Member Table Card -->
            <div class="bg-white rounded-[2rem] p-8 shadow-sm">
                <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6">All Your Member Class</h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm border-collapse min-w-[700px]">
                        <thead>
                            <tr class="border-b border-gray-100">
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[5%]">No</th>
                                <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[20%]">Fullname </th>
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
                                $row = 1;
                                while($member = mysqli_fetch_assoc($member_result)):
                            ?>
                            <tr class="border-b border-gray-50 hover:bg-gray-50/60 transition-colors">
                                <td class="py-4 text-xs text-gray-400"><?= $row++;?></td>
                                <td class="py-4 pl-3">
                                    <span class="inline-flex items-center gap-1.5 text-xs text-gray-500">
                                        <div class="w-[22px] h-[22px] rounded-full overflow-hidden flex-shrink-0">
                                            <img src="../../storage/profile_picture/<?= htmlspecialchars($member['profile_picture']) ?>" 
                                            alt="<?= htmlspecialchars($member['username']) ?>"
                                            class="w-full h-full object-cover"
                                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        </div>
                                        
                                        <?= htmlspecialchars($member["fullname"]);?>
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-green-50 text-green-600">
                                        <i data-lucide="check-circle-2" class="w-3 h-3"></i>
                                        0
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-yellow-50 text-yellow-600">
                                        <i data-lucide="file-text" class="w-3 h-3"></i>
                                        0
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-blue-50 text-blue-600">
                                        <i data-lucide="heart-pulse" class="w-3 h-3"></i>
                                        0
                                    </span>
                                </td>
                                <td class="py-4 text-center">
                                    <span class="inline-flex items-center gap-1 px-2 py-[3px] rounded-lg text-[11px] font-medium bg-red-50 text-red-500">
                                        <i data-lucide="x-circle" class="w-3 h-3"></i>
                                        0
                                    </span>
                                </td>
                                <td class="py-4 text-right">
                                    <span class="inline-flex items-center justify-end gap-1.5 text-xs text-gray-400">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <?= date("d-m-Y", strtotime($member["created_at"]))?>
                                    </span>
                                </td>
                            </tr>
                            <?php endwhile;?>
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>

    <div id="addMemberModal" class="fixed inset-0 hidden items-center justify-center z-50">
    
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm" onclick="closeAddMemberModal()"></div>
    
        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">
    
            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Add Member</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Invite a new member to join this class</p>
    
            <form id="formAddMember" action="../controllers/MemberClassController.php" method="POST" enctype="multipart/form-data" class="space-y-4">
    
                <!-- Fullname -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-1">Full Name</label>
                    <div class="relative">
                        <input
                            type="text"
                            id="fullname"
                            name="fullname"
                            placeholder="Enter member's fullname"
                            class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB] text-sm"
                        >
                        <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                    </div>
                    <p id="error-fullname" class="hidden text-xs text-red-400 mt-1"></p>
                </div>
    
                <!-- Gender -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-2">Gender</label>
                    <div class="flex gap-3">
                        <label id="gender-male-label"
                            onclick="selectGender(1)"
                            class="flex-1 flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="mars" class="w-4 h-4 text-blue-400"></i>
                            <span class="text-sm text-[#565D6D]">Male</span>
                        </label>
                        <label id="gender-female-label"
                            onclick="selectGender(2)"
                            class="flex-1 flex items-center gap-2.5 px-4 py-2.5 border-2 border-[#DEE1E6] rounded-xl cursor-pointer transition-all hover:border-[#93C5FD]">
                            <i data-lucide="venus" class="w-4 h-4 text-pink-400"></i>
                            <span class="text-sm text-[#565D6D]">Female</span>
                        </label>
                    </div>
                    <input type="hidden" id="gender" name="gender" value="">
                    <p id="error-gender" class="hidden text-xs text-red-400 mt-1"></p>
                </div>
    
                <!-- Profile Photo -->
                <div>
                    <label class="text-sm text-[#565D6D] block mb-2">Profile Photo</label>
    
                    <!-- Tab Toggle -->
                    <div class="flex gap-2 mb-3">
                        <button type="button" onclick="switchPhotoTab('default')" id="tab-default"
                            class="text-xs px-3 py-1.5 rounded-lg bg-[#93C5FD] text-white transition-all">
                            Default Photo
                        </button>
                        <button type="button" onclick="switchPhotoTab('upload')" id="tab-upload"
                            class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all">
                            Upload Photo
                        </button>
                    </div>
    
                    <!-- Panel: Default Photos -->
                    <div id="panel-default">
                        <div class="w-full border border-[#DEE1E6] rounded-lg p-3 bg-[#FAFAFA]">
                            <p class="text-[11px] text-gray-400 mb-2">Pick one of the default photos</p>
                            <div class="grid grid-cols-5 gap-2" id="default-photo-grid">
                                <?php for ($i = 1; $i <= 20; $i++): ?>
                                <div onclick="selectDefaultPhoto(<?= $i ?>)"
                                    id="default-opt-<?= $i ?>"
                                    class="w-full aspect-square rounded-lg overflow-hidden cursor-pointer border-2 border-transparent hover:border-[#93C5FD] transition-all">
                                    <img src="../../storage/profile_picture/default-profile-<?= $i ?>.svg"
                                        alt="Default <?= $i ?>"
                                        class="w-full h-full object-cover">
                                </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <input type="hidden" name="default_profile_picture" id="default_photo" value="default-profile-1.svg">
                    </div>
    
                    <!-- Panel: Upload -->
                    <div id="panel-upload" class="hidden">
                        <div class="w-full border border-[#DEE1E6] rounded-lg p-4 flex items-center gap-4 bg-[#FAFAFA]">
    
                            <!-- Thumbnail Preview -->
                            <div class="w-14 h-14 rounded-xl bg-gray-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                <i data-lucide="image" class="w-6 h-6 text-gray-300" id="photo-placeholder-icon"></i>
                                <img id="picture-preview" class="hidden w-full h-full object-cover" src="" alt="Preview">
                            </div>
    
                            <!-- Text + Button -->
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-[#565D6D] font-medium mb-0.5" id="picture-filename">No file chosen</p>
                                <p class="text-[11px] text-gray-400">JPG, PNG, WEBP — max 2MB</p>
                            </div>
    
                            <!-- Trigger Button -->
                            <button type="button" onclick="document.getElementById('profile_picture').click()"
                                class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#DEE1E6] rounded-lg text-xs text-[#565D6D] hover:bg-gray-50 transition-all">
                                <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                                Browse
                            </button>
                        </div>
                        <input type="file" name="profile_picture" id="profile_picture" accept="image/*" class="hidden"
                            onchange="previewPhoto(event)">
                    </div>
                </div>
    
                <!-- Buttons -->
                <div class="flex gap-3 mt-5">
                    <button
                        type="button"
                        onclick="handleAddMember()"
                        class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl text-sm font-medium hover:opacity-90 transition-all">
                        Add Member
                    </button>
                    <button
                        type="button"
                        onclick="closeAddMemberModal()"
                        class="flex-1 py-3 border border-[#DEE1E6] rounded-xl text-sm text-[#565D6D] hover:bg-gray-50 transition-all">
                        Cancel
                    </button>
                </div>
                <input type="hidden" name="class_id" value="<?php echo $class_id; ?>">
            </form>
        </div>
    </div>

    <script src="../scripts/admin-class-validation.js"></script>
    <script>
        lucide.createIcons();
    </script>
</body>
</html>