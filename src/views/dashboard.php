<?php
session_start();

if (!isset($_SESSION["session"])) {
    header("Location: sign-in.php");
    exit();
}
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
        
        <aside class="  w-64 bg-white border-r border-gray-200 flex flex-col fixed h-full">
            <div class="p-6  flex items-center gap-2">
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
                
                <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:bg-gray-50 hover:text-gray-600 rounded-xl transition-all">
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
                            
                            <button class="bg-[#93C5FD] hover:bg-blue-400 text-white text-sm py-2.5 px-10 rounded-xl transition-all shadow-sm font-medium cursor-pointer">
                                Join Class
                            </button>
                        </div>
                    </div>

                </div>
            </main>
        </div>
    </div>

    <!-- Modal Background -->
    <div id="createClassModal" class="fixed inset-0 hidden items-center justify-center z-50">
        
        <!-- Overlay (blur + gelap) -->
        <div class="absolute inset-0 bg-black/30 backdrop-blur-sm"></div>

        <!-- Modal Box -->
        <div class="relative bg-white w-[500px] rounded-2xl  border border-[#DEE1E6] p-6 z-10">

            <!-- Header -->
            <div class="flex items-center gap-3 mb-4">
                <img class="h-[30px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-xl text-[#87CEEB] font-bold">Create Class</h1>
            </div>
            <p class="text-sm text-[#565D6D] mb-4">Here is Where Your Journey Begins</p>

            <form class="space-y-3" action="../controllers/CreateClassController.php" method="POST">

                <!-- Class Name -->
                <label class="text-sm text-[#565D6D]">Class Name</label>
                <div class="relative">
                    <input type="text" name="class_name" id="class_name"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1
                        focus:ring-[#87CEEB]" placeholder="create a class name">
                    <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>

                <p id="error-class_name" class="hidden text-sm text-red-400"></p>

                <!-- Description -->
                <label class="text-sm text-[#565D6D]">Description</label>
                <div class="relative">
                    <input type="text" name="class_description" id="class_description"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none 
                        focus:ring-1 focus:ring-[#87CEEB]"
                        placeholder="describe your class">
                    <i data-lucide="file-text" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>

                <p id="error-class_description" class="hidden text-sm text-red-400"></p>

                <!-- Class Mode -->
                <label class="text-sm text-[#565D6D]">Select Mode</label>
                <div class="relative">
                    <select name="class_mode"
                        class="w-full border border-[#DEE1E6] rounded py-2 pl-10 pr-3 focus:outline-none focus:ring-1 focus:ring-[#87CEEB]">
                        <option disabled selected>What Mode do you prefer?</option>
                        <option value="1">Admin Only</option>
                        <option value="2">Member Participation</option>
                        <option value="3">QR Code</option>
                    </select>
                    <i data-lucide="blend" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2"></i>
                </div>

                <p id="error-class_mode" class="hidden text-sm text-red-400"></p>

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
    <script>
        lucide.createIcons();
    </script>
    <script src="../scripts/create-class-validation.js"></script>
</body>
</html>