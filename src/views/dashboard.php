<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: sign-in.php");
    exit();
}

include "../../env.php";

$user_id = $_SESSION["session"]["id"];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Dashboard | HereUp</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
        .action-card { transition: transform 0.18s, box-shadow 0.18s; }
        .action-card:hover { transform: translateY(-2px); box-shadow: 0 10px 28px rgba(0,0,0,0.07); }
    </style>
</head>
<body class="bg-[#F8FAFC] text-[#64748B]">

    <div class="flex min-h-screen">

        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full">
            <div class="p-6 flex items-center gap-2">
                <a href="#">
                    <div class="flex items-center justify-between gap-[13.5px] cursor-pointer">
                        <img class="h-[31.5px]" src="../../public/images/icon.png" alt="Icon">
                        <h1 class="text-[20.7px] font-extrabold text-[#87CEEB]">HERE UP</h1>
                    </div>
                </a>
            </div>

            <nav class="flex-1 px-4 space-y-1">
                <a href="#" class="flex items-center gap-3 px-4 py-3 bg-[#93C5FD] text-white rounded-xl font-medium transition-all">
                    <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
                    <span class="text-sm">Dashboard</span>
                </a>
                <a href="class.php" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
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

        <div class="flex-1 ml-64 flex flex-col">

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

                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
                            <p class="text-sm text-gray-400 mt-1">Welcome back, <?php echo htmlspecialchars($_SESSION['session']['username']); ?></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-5 mb-5">

                        <div class="action-card bg-[#F1F9FE] border border-blue-100 rounded-2xl p-6 flex items-center gap-5 cursor-pointer" onclick="openModal()">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                                <i data-lucide="plus-circle" class="w-6 h-6 text-[#93C5FD]"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-700 mb-0.5">Create a Class</h3>
                                <p class="text-xs text-gray-400 leading-relaxed">Start a new class and invite other users</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="action-card bg-[#FFF1F5] border border-pink-100 rounded-2xl p-6 flex items-center gap-5 cursor-pointer" onclick="openJoinModal()">
                            <div class="w-12 h-12 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                                <i data-lucide="user-plus" class="w-6 h-6 text-pink-300"></i>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-sm font-semibold text-gray-700 mb-0.5">Join a Class</h3>
                                <p class="text-xs text-gray-400 leading-relaxed">Join other user's class with an invitation code</p>
                            </div>
                            <div class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center shrink-0">
                                <i data-lucide="arrow-right" class="w-4 h-4 text-gray-400"></i>
                            </div>
                        </div>

                    </div>

                    <div class="grid grid-cols-5 gap-5">

                        <div class="col-span-3 border border-gray-100 rounded-2xl flex flex-col overflow-hidden" style="min-height:300px;">
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <div>
                                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest">All Your Classes</h2>
                                </div>
                                <a href="class.php" class="text-[11px] text-[#93C5FD] hover:text-blue-400 font-medium flex items-center gap-1 transition-colors">
                                    View all <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </a>
                            </div>

                            <div class="flex-1 overflow-y-auto custom-scrollbar">
                                <?php
                                    $user_class_query = "SELECT * FROM user_class WHERE user_id = '$user_id'";
                                    $user_class_result = mysqli_query($connection, $user_class_query);
                                    
                                    $class_row = 0;
                                    while($user_class = mysqli_fetch_assoc($user_class_result)):
                                        $class_row++;

                                        $class_id = $user_class['class_id'];
                                        $class_query = "SELECT * FROM class WHERE id = '$class_id'";
                                        $class = mysqli_fetch_assoc(mysqli_query($connection, $class_query));

                                        $creator_id = $class['created_by'];
                                        $creator_query = "SELECT username, profile_picture FROM user WHERE id = '$creator_id'";
                                        $creator = mysqli_fetch_assoc(mysqli_query($connection, $creator_query));
                                        
                                        $isAdmin = isset($user_class['role']) && $user_class['role'] == 1;
                                ?>
                                
                                <a href="admin-class.php?class_id=<?= $class_id ?>" class="flex items-center gap-3 px-6 py-3.5 hover:bg-gray-50 transition-colors border-b border-gray-50 last:border-0 cursor-pointer block">
                                    <span class="text-[10px] text-gray-300 w-4 shrink-0"><?= $class_row ?></span>
                                    <div class="w-8 h-8 rounded-lg overflow-hidden bg-gray-100 shrink-0">
                                        <img src="../../storage/class_profile_picture/<?= htmlspecialchars($class['profile_picture']) ?>" alt="" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-600 truncate"><?= htmlspecialchars($class['name']) ?></p>
                                        <div class="flex items-center gap-1 mt-0.5">
                                            <div class="w-3.5 h-3.5 rounded-full overflow-hidden bg-gray-100 shrink-0">
                                                <img src="../../storage/profile_picture/<?= htmlspecialchars($creator['profile_picture']) ?>"
                                                    alt="" class="w-full h-full object-cover"
                                                    onerror="this.style.display='none'">
                                            </div>
                                            <p class="text-[10px] text-gray-400 truncate"><?= htmlspecialchars($creator['username']) ?></p>
                                        </div>
                                    </div>
                                    <?php if ($isAdmin): ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-blue-50 text-blue-400 border border-blue-100 shrink-0">
                                        <i data-lucide="shield-check" class="w-2.5 h-2.5"></i> Admin
                                    </span>
                                    <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-semibold bg-green-50 text-green-500 border border-green-100 shrink-0">
                                        <i data-lucide="user" class="w-2.5 h-2.5"></i> Member
                                    </span>
                                    <?php endif; ?>
                                </a>
                                <?php endwhile; ?>
                            </div>
                        </div>

                        <div class="col-span-2 border border-gray-100 rounded-2xl flex flex-col overflow-hidden">
                            
                            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                                <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest">All Your Activity</h2>
                                <a href="class.php" class="text-[11px] text-[#93C5FD] hover:text-blue-400 font-medium flex items-center gap-1 transition-colors">
                                    View all <i data-lucide="chevron-right" class="w-3 h-3"></i>
                                </a>
                            </div>

                            <div class="flex-1 overflow-y-auto custom-scrollbar divide-y divide-gray-50">
                                <?php
                                    $user_activity_query = "SELECT * FROM user_class WHERE user_id = '$user_id'";
                                    $user_activity_result = mysqli_query($connection, $user_activity_query);
                                    
                                    // Variabel penanda jika tidak ada aktivitas sama sekali
                                    $has_activity = false; 

                                    // MULAI PERULANGAN KELAS
                                    while ($user_activity = mysqli_fetch_assoc($user_activity_result)) :
                                        $class_activity_id = $user_activity['class_id'];
                                        
                                        // Ambil info kelas
                                        $class_activity_query = "SELECT name FROM class WHERE id = '$class_activity_id'";
                                        $class_activity = mysqli_fetch_assoc(mysqli_query($connection, $class_activity_query));
                                        
                                        // Ambil aktivitas (session) yang sedang aktif saja
                                        $activity_query = "SELECT * FROM session WHERE class_id = '$class_activity_id' AND is_active = '1'";
                                        $activity_result = mysqli_query($connection, $activity_query);
                                        
                                        // PERULANGAN AKTIVITAS: Jika ada sesi yang aktif, tampilkan datanya
                                        while ($activity = mysqli_fetch_assoc($activity_result)):
                                            $has_activity = true; // Tandai bahwa ada minimal 1 aktivitas
                                ?>
                                
                                <div class="px-6 py-4 flex items-start gap-3 hover:bg-gray-50 transition-colors">
                                    <div class="w-8 h-8 rounded-xl bg-[#F1F9FE] flex items-center justify-center shrink-0 mt-0.5">
                                        <i data-lucide="shield" class="w-3.5 h-3.5 text-[#93C5FD]"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-medium text-gray-600 leading-tight"><?= htmlspecialchars($activity["name"] ?? 'Unknown Activity') ?></p>
                                        <p class="text-[10px] text-gray-400 mt-0.5 truncate"><?= htmlspecialchars($class_activity["name"] ?? 'Unknown Class') ?></p>
                                    </div>
                                    <span class="text-[10px] text-gray-400 shrink-0 pt-0.5 flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3 h-3"></i>
                                        <?= htmlspecialchars($activity["created_at"] ?? '-') ?>
                                    </span>
                                </div>

                                <?php 
                                        endwhile; // Akhir perulangan aktivitas
                                    endwhile; // Akhir perulangan kelas
                                ?>

                                <?php if (!$has_activity): ?>
                                <div class="px-6 py-10 flex flex-col items-center text-center">
                                    <i data-lucide="calendar-x" class="w-6 h-6 text-gray-200 mb-2"></i>
                                    <p class="text-[11px] text-gray-300">No active sessions right now</p>
                                </div>
                                <?php endif; ?>

                            </div>
                        </div>

                    </div>
                </div>
            </main>

        </div>
    </div>

    <div id="createClassModal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Create Class</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Here is Where Your Journey Begins</p>

            <form class="space-y-3" action="../controllers/CreateClassController.php" method="POST" enctype="multipart/form-data">
                <label class="text-sm text-[#565D6D]">Class Name</label>
                <div class="relative">
                    <input type="text" name="class_name" id="class_name"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="create a class name">
                    <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-class_name" class="hidden text-sm text-red-400"></p>

                <label class="text-sm text-[#565D6D]">Description</label>
                <div class="relative">
                    <input type="text" name="class_description" id="class_description"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="describe your class (optional)">
                    <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-class_description" class="hidden text-sm text-red-400"></p>

                <label class="text-sm text-[#565D6D]">Select Mode</label>
                <div class="relative">
                    <select name="class_mode"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]">
                        <option value="" disabled selected>What Mode do you prefer?</option>
                        <option value="1">Admin Only</option>
                        <option value="2">Member Participation</option>
                        <option value="3">QR Code</option>
                    </select>
                    <i data-lucide="blend" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-class_mode" class="hidden text-sm text-red-400"></p>

                <label class="text-sm text-[#565D6D]">Class Profile</label>
                <div class="flex gap-2">
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
                                <img src="../../storage/class_profile_picture/default-profile-<?= $i ?>.svg" alt="Default <?= $i ?>" class="w-full h-full object-cover">
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <input type="hidden" name="class_default_photo" id="class_default_photo" value="default-profile-1.svg">
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
                        <button type="button" onclick="document.getElementById('class_profile_picture').click()"
                            class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#DEE1E6] rounded-lg text-xs text-[#565D6D] hover:bg-gray-50 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i> Browse
                        </button>
                    </div>
                    <input type="file" name="class_profile_picture" id="class_profile_picture" accept="image/*" class="hidden" onchange="previewClassPhoto(event)">
                </div>
                <p id="error-class_profile_picture" class="hidden text-sm text-red-400"></p>

                <div class="flex gap-3 mt-5">
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl">Create</button>
                    <button type="button" onclick="closeModal()" class="flex-1 py-3 border border-[#DEE1E6] rounded-xl">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="joinClassModal" class="fixed inset-0 hidden items-center justify-center z-50">
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>
        <div class="relative bg-white w-[500px] rounded-2xl border border-[#DEE1E6] p-6 z-10">
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Join Class</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Here is Where Your Journey Begins</p>

            <form class="space-y-3" action="../controllers/JoinClassController.php" method="POST">
                <label class="text-sm text-[#565D6D]">Invitation Code</label>
                <div class="relative">
                    <input type="text" name="invitation_code" id="invitation_code"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="Enter code">
                    <i data-lucide="key" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-invitation_code" class="hidden text-sm text-red-400"></p>

                <div class="flex gap-3 mt-5">
                    <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl">Join</button>
                    <button type="button" onclick="closeJoinModal()" class="flex-1 py-3 border border-[#DEE1E6] rounded-xl">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        lucide.createIcons();

        function openModal() { const m = document.getElementById('createClassModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeModal() { const m = document.getElementById('createClassModal'); m.classList.add('hidden'); m.classList.remove('flex'); }
        function openJoinModal() { const m = document.getElementById('joinClassModal'); m.classList.remove('hidden'); m.classList.add('flex'); }
        function closeJoinModal() { const m = document.getElementById('joinClassModal'); m.classList.add('hidden'); m.classList.remove('flex'); }

        function switchPhotoTab(tab) {
            const isDefault = tab === 'default';
            document.getElementById('panel-default').classList.toggle('hidden', !isDefault);
            document.getElementById('panel-upload').classList.toggle('hidden', isDefault);
            document.getElementById('tab-default').className = isDefault
                ? 'text-xs px-3 py-1.5 rounded-lg bg-[#93C5FD] text-white transition-all'
                : 'text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all';
            document.getElementById('tab-upload').className = !isDefault
                ? 'text-xs px-3 py-1.5 rounded-lg bg-[#93C5FD] text-white transition-all'
                : 'text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all';
            if (isDefault) {
                document.getElementById('class_profile_picture').value = '';
                document.getElementById('picture-preview').classList.add('hidden');
                document.getElementById('photo-placeholder-icon').classList.remove('hidden');
                document.getElementById('picture-filename').textContent = 'No file chosen';
            } else {
                document.getElementById('class_default_photo').value = '';
                document.querySelectorAll('#default-photo-grid > div').forEach(el => {
                    el.classList.remove('border-[#93C5FD]'); el.classList.add('border-transparent');
                });
            }
        }

        function selectDefaultPhoto(num) {
            document.querySelectorAll('#default-photo-grid > div').forEach(el => {
                el.classList.remove('border-[#93C5FD]'); el.classList.add('border-transparent');
            });
            const sel = document.getElementById('default-opt-' + num);
            if (sel) { sel.classList.remove('border-transparent'); sel.classList.add('border-[#93C5FD]'); }
            document.getElementById('class_default_photo').value = 'default-profile-' + num + '.svg';
        }

        function previewClassPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;
            document.getElementById('picture-filename').textContent = file.name;
            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('picture-preview');
                const icon = document.getElementById('photo-placeholder-icon');
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                icon.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    </script>
    <script src="../scripts/class-validation.js"></script>
</body>
</html>