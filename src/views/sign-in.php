<?php
session_start();
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
    <title>Sign-In | Here Up</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body>
    <div class="mx-auto my-[61px] flex flex-col items-center justify-center w-[500px] border-[2px] border-[#DEE1E6] rounded-md">
        <img class="h-[35px] mt-[88px]" src="../../public/images/icon.png" alt="Icon">
        <h1 class="text-[30px] text-[#87CEEB] text-center font-bold font-archivo mt-[22px] leading-9">Welcome Back, <br> Great To See You!</h1>
        <p class="text-[#91959C] text-[14px] text-center mt-[17px] mb-[40px]">
            Insert your Email and Password <br>
            to start using Here Up!
        </p>

        <form id="signin-form" class="space-y-3 w-full max-w-sm mx-auto" action="../controllers/SigninController.php" method="POST">
            <label for="username" class="block text-sm font-medium text-[#565D6D]">Username</label>
            <div class="relative">
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="cool_username345"
                    class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                    value="<?= isset($_SESSION['user_input']) ? htmlspecialchars($_SESSION['user_input']) : '' ?>"
                />
                <i data-lucide="user" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
            </div>
            <p id="error-username" class="hidden text-xs text-red-500 mt-1"></p>

            <label for="password" class="block text-sm font-medium text-[#565D6D]">Password</label>
            <div class="relative">
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="password123"
                    class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                />
                <i data-lucide="lock" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>

                <button
                    type="button"
                    id="toggle-password"
                    aria-label="Tampilkan password"
                    class="absolute right-3 top-1/2 -translate-y-1/2 text-[#91959C] hover:text-[#565D6D] cursor-pointer focus:outline-none">
                    <i id="toggle-password-icon" data-lucide="eye" class="w-5 h-5" stroke-width="2"></i>
                </button>
            </div>
            <p id="error-password" class="hidden text-xs text-red-500 mt-1"></p>

            <a class="w-full mr-28 text-[#636AE8] text-[14px] mt-3 mb-6 text-left mx-auto block" href="#">Forgot Password ? </a>

            <button type="submit" class="cursor-pointer mx-auto block w-[335px] py-2.5 rounded text-white text-sm font-medium bg-gradient-to-r from-[#7C58DF] via-[#22CCB2] to-[#626BE8] hover:opacity-90 transition-opacity duration-200">
                Sign In
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
                Sign In With Google
            </button>
        </form>

        <p class="my-10 mx-auto text-[14px] text-[#565D6D]">Don't have an account yet? <a class="text-[#636AE8] underline ml-2 " href="sign-up.php">Sign Up</a></p>

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
        unset($_SESSION["user_input"]);
    ?>

    <!-- Initialize Lucide Icons -->
    <script>lucide.createIcons();</script>
    <script src="../scripts/sign-in-validation.js"></script>
    <script src="../scripts/toggle-password.js"></script>
</body>
</html>