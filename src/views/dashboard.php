<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION["session"])) {
    header("Location: sign-in.php");
    exit();
}

include "../../env.php";

// Get User ID
$user_id = $_SESSION["session"]["id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="../../public/favicon.ico">
    <title>Dashboard | HereUp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#F8FAFC] text-[#64748B]">

    <div class="flex min-h-screen">
        
        <!-- SideBar -->
        <aside class="  w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full">
            <div class="p-6  flex items-center gap-2">
                <!-- Icon and Title -->
                <a href="#">
                    <div class="flex items-center justify-between gap-[13.5px] cursor-pointer">
                        <img class="h-[31.5px]" src="../../public/images/icon.png" alt="Icon">
                        <h1 class="text-[20.7px] font-extrabold text-[#87CEEB]">HERE UP</h1>
                    </div>
                </a>
            </div>
            
            <!-- Navigation Menu -->
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
                            <img src="../../storage/default.svg" alt="Avatar" class="w-full h-full object-cover">
                        </div>
                        <span class="text-sm font-medium text-gray-700"><?php echo htmlspecialchars($_SESSION['session']['username']); ?></span>
                    </div>
                </div>
            </header>

            <!-- Main Content -->
            <main class="p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">    
                    <div class="bg-[#F1F9FE] rounded-[2rem] p-8 h-[320px] flex flex-col relative shadow-sm">
                        <h2 class="text-md">Create Class</h2>
                        
                        <div class="flex-1 flex flex-col items-center mt-7 justify-center">
                            <div class="mb-4">
                                <i data-lucide="plus" class="w-7 h-7 stroke-[1.5]"></i>
                            </div>
                            
                            <p class="text-slate-500 text-sm mb-6 font-normal">Start a New Class and Invite Other User</p>
                            
                            <button onclick="openModal()" class="bg-[#93C5FD] hover:bg-blue-400 text-white text-sm py-2.5 px-10 rounded-xl transition-all shadow-sm font-medium cursor-pointer">
                                Create Class
                            </button>
                        </div>  
                    </div>

                <div class="bg-[#FFF1F5] rounded-[2rem] p-8 h-[320px] flex flex-col relative shadow-sm">
                    <h2 class="text-md">Join Class</h2>
                    
                    <div class="flex-1 flex flex-col items-center justify-center mt-7">
                        <div class="mb-4">
                            <i data-lucide="user-plus" class="w-7 h-7 stroke-[1.5]"></i>
                        </div>
                        
                        <p class="text-slate-500 text-sm mb-6 font-normal">Join Other User Class With Invitation Code</p>
                        
                        <button onclick="openJoinModal()" class="bg-[#93C5FD] hover:bg-blue-400 text-white text-sm py-2.5 px-10 rounded-xl transition-all shadow-sm font-medium cursor-pointer">
                            Join Class
                        </button>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 h-[320px] flex flex-col relative shadow-sm">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6">All Your Classes</h2>

                    <div class="flex-1 overflow-y-auto -mr-4 pr-2 custom-scrollbar">
                        <table class="w-full text-sm border-separate border-spacing-0" style="table-layout: fixed;">
                            <thead>
                                <tr>
                                    <th class="sticky top-0 z-10 bg-white text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[8%] border-b border-gray-100">No</th>
                                    <th class="sticky top-0 z-10 bg-white text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[30%] border-b border-gray-100">Class Name</th>
                                    <th class="sticky top-0 z-10 bg-white text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[30%] border-b border-gray-100">Creator</th>
                                    <th class="sticky top-0 z-10 bg-white text-right text-xs font-medium text-gray-400 tracking-wide pb-3 pr-1 w-[32%] border-b border-gray-100">Created At</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $user_class_query = "SELECT * FROM user_class WHERE user_id = '$user_id'";

                                    $user_class_result = mysqli_query($connection, $user_class_query);

                                    $row = 1;
                                    while($user_class = mysqli_fetch_assoc($user_class_result)):

                                        $class_id = $user_class['class_id'];
                                        $class_query = "SELECT * FROM class WHERE id = '$class_id'";
                                        $class = mysqli_fetch_assoc(mysqli_query($connection, $class_query));

                                        $creator_id = $class['created_by'];
                                        $creator_query = "SELECT username FROM user WHERE id = '$creator_id'";
                                        $creator = mysqli_fetch_assoc(mysqli_query($connection, $creator_query));

                                ?>
                                <tr>
                                    <td class="border-b border-gray-50 py-4 text-xs text-gray-400"><?=$row++?></td>
                                    <td class="border-b border-gray-50 py-4 pl-3">
                                        <span class="inline-flex items-center gap-1.5 bg-gray-100 rounded-md px-2.5 py-1 text-xs max-w-[100px] text-gray-500">
                                            <i data-lucide="shield" class="w-3 h-3 shrink-0"></i>
                                            <span class="truncate"><?= htmlspecialchars($class["name"])?></span>
                                        </span>
                                    </td>
                                    <td class="border-b border-gray-50 py-4 pl-3">
                                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-500 max-w-[140px]">
                                            <span class="w-[22px] h-[22px] rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-medium text-blue-500 shrink-0"> <?= strtoupper(substr($creator['username'], 0, 1)) ?></span>
                                            <span class="truncate"><?= htmlspecialchars($creator["username"])?></span> 
                                        </span>
                                    </td>
                                    <td class="border-b border-gray-50 pr-1 py-4 text-right">
                                        <span class="inline-flex items-center justify-end gap-1.5 text-xs text-gray-400">
                                            <i data-lucide="calendar" class="w-3 h-3 shrink-0"></i>
                                            <?= date('d-m-Y', strtotime($class['created_at']))?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white rounded-[2rem] p-8 h-[320px] flex flex-col relative shadow-sm">
                    <h2 class="text-xs font-medium text-gray-400 uppercase tracking-widest mb-6">Today Activity</h2>
                    <table class="w-full text-sm border-collapse" style="table-layout: fixed;">
                        <thead>
                        <tr class="border-b border-gray-100">
                            <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 w-[8%]">No</th>
                            <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[32%]">Session</th>
                            <th class="text-left text-xs font-medium text-gray-400 tracking-wide pb-3 pl-3 w-[32%]">Class Name</th>
                            <th class="text-right text-xs font-medium text-gray-400 tracking-wide pb-3 w-[28%]">Date & Time</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="border-b border-gray-50">
                            <td class="py-4 text-xs text-gray-400">1</td>
                            <td class="py-4 pl-3 font-medium text-gray-500 text-sm">Kegiatan Ronda</td>
                            <td class="py-4 pl-3">
                            <span class="inline-flex items-center gap-1.5 bg-gray-100 rounded-md px-2.5 py-1 text-xs text-gray-500">
                                <i data-lucide="shield" class="w-3 h-3"></i>
                                Pos Kamling 21
                            </span>
                            </td>
                            <td class="py-4 text-right">
                            <span class="inline-flex items-center justify-end gap-1.5 text-xs text-gray-400">
                                <i data-lucide="calendar" class="w-3 h-3"></i>
                                Senin, 11-09-2026
                            </span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </main>

        </div>
    </div>

    <!-- Modal Background -->
    <div id="createClassModal" class="fixed inset-0 hidden items-center justify-center z-50">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] max-h-[90vh] overflow-y-auto rounded-2xl border border-[#DEE1E6] p-6 z-10">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Create Class</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Here is Where Your Journey Begins</p>

            <form class="space-y-3" action="../controllers/CreateClassController.php" method="POST" enctype="multipart/form-data">

                <!-- Class Name -->
                <label class="text-sm text-[#565D6D]">Class Name</label>
                <div class="relative">
                    <input type="text" name="class_name" id="class_name"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="create a class name">
                    <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-class_name" class="hidden text-sm text-red-400"></p>

                <!-- Description -->
                <label class="text-sm text-[#565D6D]">Description</label>
                <div class="relative">
                    <input type="text" name="class_description" id="class_description"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="describe your class (optional)">
                    <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>
                <p id="error-class_description" class="hidden text-sm text-red-400"></p>

                <!-- Class Mode -->
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

                <!-- Class Profile -->
                <label class="text-sm text-[#565D6D]">Class Profile</label>

                <!-- Tab Toggle -->
                <div class="flex gap-2">
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
                                <img src="../../storage/class_default_profile/default-profile-<?= $i ?>.svg"
                                    alt="Default <?= $i ?>"
                                    class="w-full h-full object-cover">
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                    <input type="hidden" name="class_default_photo" id="class_default_photo" value="default-profile-1.svg">
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
                        <button type="button" onclick="document.getElementById('class_profile_picture').click()"
                            class="flex-shrink-0 flex items-center gap-1.5 px-3 py-1.5 bg-white border border-[#DEE1E6] rounded-lg text-xs text-[#565D6D] hover:bg-gray-50 transition-all">
                            <i data-lucide="upload" class="w-3.5 h-3.5"></i>
                            Browse
                        </button>
                    </div>
                    <input type="file" name="class_profile_picture" id="class_profile_picture" accept="image/*" class="hidden"
                        onchange="previewClassPhoto(event)">
                </div>

                <p id="error-class_profile_picture" class="hidden text-sm text-red-400"></p>

                <!-- Buttons -->
                <div class="flex gap-3 mt-5">
                    <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl">
                        Create
                    </button>
                    <button type="button" onclick="closeModal()"
                        class="flex-1 py-3 border border-[#DEE1E6] rounded-xl">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Join Modal -->
    <div id="joinClassModal" class="fixed inset-0 hidden items-center justify-center z-50">
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] rounded-2xl border border-[#DEE1E6] p-6 z-10">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Join Class</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Here is Where Your Journey Begins</p>

            <form class="space-y-3" action="../controllers/JoinClassController.php" method="POST">

                <!-- Invitation Code -->
                <label class="text-sm text-[#565D6D]">Invitation Code</label>
                <div class="relative">
                    <input type="text" name="invitation_code" id="invitation_code"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="Enter code">
                    <i data-lucide="key" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>

                <p id="error-invitation_code" class="hidden text-sm text-red-400"></p>

                <!-- Buttons -->
                <div class="flex gap-3 mt-5">
                    <button type="submit"
                        class="flex-1 py-3 bg-gradient-to-r from-[#7B61FF] via-[#3BC5BA] to-[#5D87E8] text-white rounded-xl">
                        Join
                    </button>

                    <button type="button" onclick="closeJoinModal()"
                        class="flex-1 py-3 border border-[#DEE1E6] rounded-xl">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        lucide.createIcons();

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
                    el.classList.remove('border-[#93C5FD]');
                    el.classList.add('border-transparent');
                });
            }
        }

        // ── Pilih Default Photo ───────────────────────────────
        function selectDefaultPhoto(num) {
            document.querySelectorAll('#default-photo-grid > div').forEach(el => {
                el.classList.remove('border-[#93C5FD]');
                el.classList.add('border-transparent');
            });

            const selected = document.getElementById('default-opt-' + num);
            if (selected) {
                selected.classList.remove('border-transparent');
                selected.classList.add('border-[#93C5FD]');
            }

            document.getElementById('class_default_photo').value = 'default-profile-' + num + '.svg';
        }

        // ── Upload Preview ────────────────────────────────────
        function previewClassPhoto(event) {
            const file = event.target.files[0];
            if (!file) return;

            document.getElementById('picture-filename').textContent = file.name;

            const reader = new FileReader();
            reader.onload = (e) => {
                const preview = document.getElementById('picture-preview');
                const icon    = document.getElementById('photo-placeholder-icon');
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