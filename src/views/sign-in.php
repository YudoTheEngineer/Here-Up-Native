<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

        <form class="space-y-3 w-full max-w-sm mx-auto" action="../controller/SigninController.php" method="POST">
            <!-- Username -->
            <label for="username" class="block text-sm font-medium text-[#565D6D]">Username</label>
            <div class="relative">
                <input
                type="text"
                id="username"
                name="username"
                placeholder="cool_username345"
                class="block w-full rounded border border-[#DEE1E6] py-2 pl-10 pr-3 placeholder-[#91959C] focus:border-[#87CEEB] focus:ring-1 focus:ring-[#87CEEB] focus:outline-none"
                />
                <i data-lucide="user" class="w-5 h-5 absolute left-3 top-1/2 -translate-y-1/2 text-[#91959C]" stroke-width="2"></i>
            </div>

            <!-- Password -->
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
            </div>


            <a class="w-full mr-28 text-[#636AE8] text-[14px] my-4 text-left mx-auto block" href="#">Forgot Password ? </a>

            <button type="submit" class="cursor-pointer mx-auto block">
                <img src="../../public/images/auth/sign-in__button-image.png" alt="sign-in">
            </button>

            <img class="my-7 mx-auto block" src="../../public/images/auth/sign-in__line-image.png">

            <img class="mx-auto block" src="../../public/images/auth/sign-in__google-image.png">

        </form>
        <p class="my-10 mx-auto text-[14px] text-[#565D6D]">Don't have an account yet? <a class="text-[#636AE8] underline ml-2 " href="sign-up.php">Sign Up</a></p>
    </div>


    <script>
        // Initialize Lucide Icon
        lucide.createIcons();
    </script>
</body>
</html>