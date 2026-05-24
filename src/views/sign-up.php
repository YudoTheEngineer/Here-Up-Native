<?php
session_start();

// Get Old Input from Controller
$input = [];

if (isset($_SESSION["user_input"])) {
    $input = $_SESSION["user_input"];
    unset($_SESSION["user_input"]);
}

if (isset($_SESSION["session"])) {
    header("Location: ../views/dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="icon" type="image/png" href="../../public/favicon.ico">
        <title>Sign-Up | Here Up</title>

        <!-- Tailwind CSS -->
        <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

        <!-- Lucide Icons -->
        <script src="https://unpkg.com/lucide@latest"></script>
    </head> 
    <body>
        <div class="mx-auto my-[61px] flex flex-col items-center justify-center w-[500px] border-[2px] border-[#DEE1E6] rounded-md">

            <!-- Icon and Title -->
            <div class="flex items-center justify-between gap-5 mb-[10px]">
                <img class="h-[35px] mt-[54px]" src="../../public/images/icon.png" alt="Icon">
                <h1 class="text-[30px] mt-13 text-[#87CEEB] font-archivo font-bold">SIGN UP</h1>
            </div>
            <p class="text-[#565D6D] mb-[47px]">Start Your Journey With Here Up</p>

            <form class="space-y-3 w-full max-w-sm mx-auto" action="../controllers/SignupController.php" method="POST" novalidate>

                <!-- Username -->
                <label for="username" class="block text-sm font-medium text-[#565D6D]">Username</label>
                <div class="relative">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="create an username"
                        class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                        value="<?= htmlspecialchars($input['username'] ?? '') ?>" />
                    <i data-lucide="user" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
                </div>
                <p id="error-username" class="hidden text-sm text-red-400 font-inter"></p>

                <!-- Email -->
                <label for="email" class="block text-sm font-medium text-[#565D6D]">Email</label>
                <div class="relative">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="name@example.com"
                        class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                        value="<?= htmlspecialchars($input['email'] ?? '') ?>"
                    />
                    <i data-lucide="mail" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
                </div>
                <p id="error-email" class="hidden text-sm text-red-400 font-inter"></p>

                <!-- Password -->
                <label for="password" class="block text-sm font-medium text-[#565D6D]">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="password123"
                        class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-10 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                    />
                    <i data-lucide="lock" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
                    <button
                        type="button"
                        id="toggle-password"
                        aria-label="Tampilkan password"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#91959C] hover:text-[#565D6D] cursor-pointer focus:outline-none"
                    >
                        <i id="toggle-password-icon" data-lucide="eye" class="w-5 h-5" stroke-width="2"></i>
                    </button>
                </div>
                <p id="error-password" class="hidden text-sm text-red-400 font-inter"></p>

                <!-- Confirm Password -->
                <label for="confirm_password" class="block text-sm font-medium text-[#565D6D]">Confirm Password</label>
                <div class="relative">
                    <input
                        type="password"
                        id="confirm_password"
                        name="confirm_password"
                        placeholder="re-enter your password"
                        class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-10 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                    />
                    <i data-lucide="lock" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
                    <button
                        type="button"
                        id="toggle-confirm-password"
                        aria-label="Tampilkan password"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-[#91959C] hover:text-[#565D6D] cursor-pointer focus:outline-none"
                    >
                        <i id="toggle-confirm-icon" data-lucide="eye" class="w-5 h-5" stroke-width="2"></i>
                    </button>
                </div>
                <p id="error-confirm-password" class="hidden text-sm text-red-400 font-inter"></p>

                <!-- Term of Service and Privacy Policy -->
                <div class="flex justify-between my-[32px] gap-3 mx-4">
                    <input id="tos-checkbox" class="mb-5 shrink-0" type="checkbox" required>
                    <p class="text-[14px] font-inter">
                        I read and agree to the <a class="text-[#636AE8] underline" href="">Terms of Service</a> and <a class="text-[#636AE8] underline" href="">Privacy Policy</a> of HereUp
                    </p>
                </div>

                <button type="submit" class="cursor-pointer mx-auto block w-[335px] py-2.5 rounded text-white text-sm font-medium bg-gradient-to-r from-[#7C58DF] via-[#22CCB2] to-[#626BE8] hover:opacity-90 transition-opacity duration-200">
                    Sign Up
                </button>
                
                <div class="flex items-center w-full max-w-sm mx-auto my-6 gap-10">
                    <div class="flex-1 h-px bg-[#DEE1E6]"></div>
                    <span class="text-[#91959C] text-sm">Or</span>
                    <div class="flex-1 h-px bg-[#DEE1E6]"></div>
                </div>
                
                <button type="button" disabled class="cursor-not-allowed mx-auto flex items-center justify-center gap-2 w-[335px] py-2.5 rounded border border-[#DEE1E6] text-[#1E1E1E] text-sm font-medium bg-white hover:bg-gray-50 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-5 h-5">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    Sign Up With Google
                </button>
                
                <!-- Already Have Account Option -->
                <p class="my-10 text-[14px] text-[#565D6D] text-center">Already have an account?
                    <a class="text-[#636AE8] underline ml-2" href="sign-in.php" target="_self">Sign in</a>
                </p>
            </form>

            <!-- Error Notification -->
            <div id="errorModal" class="hidden fixed inset-0 bg-black/40 flex items-center justify-center z-50">
                <div class="bg-white w-[400px] rounded-xl border border-[#DEDFE3] shadow-lg p-6 text-center">

                    <div class="flex justify-center mb-4">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i data-lucide="x" class="text-red-500"></i>
                        </div>
                    </div>

                    <h2 class="text-lg font-bold text-[#2D3142] mb-2">Error Message</h2>
                    <p id="errorMessage" class="text-sm text-[#91959C] mb-6"></p>

                    <button onclick="closeNotif()" 
                        class="bg-[#87CEEB] text-white px-4 py-2 rounded-lg hover:shadow-md">
                        OK
                    </button>
                </div>
            </div>

        </div>
        
        <!-- Error Notification -->
        <?php if (isset($_SESSION["error"])) :?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const modal = document.getElementById("errorModal");
                const message = document.getElementById("errorMessage");

                message.textContent = "<?= $_SESSION["error"]; ?>";
                modal.classList.remove("hidden");

                window.closeNotif = function () {
                    modal.classList.add("hidden");
                };
            });
        </script>
        <?php 
            unset($_SESSION["error"]); endif;
        ?>
        

        <!-- Initialize Lucide Icons -->
        <script> lucide.createIcons();</script>
        <!-- Toggle Password Visibility -->
        <script src="../scripts/toggle-password.js"></script>
        <!-- Input Validation -->
        <script src="../scripts/sign-up-validation.js"></script>
    </body>
</html>