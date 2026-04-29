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

            <form class="space-y-3 w-full max-w-sm mx-auto" action="../controllers/SignupController.php" method="POST" novlidate>

                <!-- Username -->
                <label for="username" class="block text-sm font-medium text-[#565D6D]">Username</label>
                <div class="relative">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="create an username"
                        class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                    />
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
                    <!-- Toggle: eye = password tersembunyi | eye-off = password terlihat -->
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
                    <!-- Toggle: eye = password tersembunyi | eye-off = password terlihat -->
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
                        I read and agree to the <a class="text-[#87CEEB] underline" href="">Terms of Service</a> and <a class="text-[#87CEEB] underline" href="">Privacy Policy</a> of HereUp
                    </p>
                </div>

                <!-- Sign-Up Button -->
                <button type="submit" class="rounded-sm mx-auto block cursor-pointer">
                    <img src="../../public/images/auth/sign-up__button-image.png" alt="sign-up">
                </button>   
                
                <!-- Separate Line -->
                <img class="my-[40px] mx-auto block" src="../../public/images/auth/sign-up__line-image.png">

                <!-- Sign-Up With Google Button -->
                <button class="rounded-sm mb-[50px] mx-auto block cursor-pointer">
                    <img src="../../public/images/auth/sign-up__google-image.png" alt="sign-up with google">
                </button>
                
                <!-- Already Have Account Option -->
                <p class="my-10 text-[14px] text-[#565D6D] text-center">Already have an account?
                    <a class="text-[#636AE8] underline ml-2" href="sign-in.php" target="_self">Sign in</a>
                </p>
            </form>
        </div>

        <!-- Initialize Lucide Icons -->
        <script>lucide.createIcons();</script>

        <!-- Toggle Password Visibility -->
        <script src="../scripts/toggle-password.js"></script>

        <!-- Input Validation -->
        <script src="../scripts/sign-up-validation.js"></script>
    </body>
</html>