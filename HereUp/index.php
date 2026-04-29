<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

    <!-- Icon and Title -->
    <link rel="icon" href="public/favicon.ico" type="image/x-icon">
    <title>HereUp</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- CSS Smooth Scroll -->
    <style>
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body class="font-sans bg-[#FFFFFF]">

    <!-- Header -->
    <header class="flex items-center justify-between fixed top-0 z-50 h-[81px] bg-white w-full border-b
    border-[#DEDFE3] mx-auto">
        <!-- Content Wrapper -->
        <div class="flex items-center justify-between w-full mx-[116.1px]">
            <!-- Header Content 1 -->
            <div class="flex items-center justify-between gap-[44.1px]">
                <!-- HereUp Icon -->
                <a href="#">
                    <div class="flex items-center justify-between gap-[13.5px] cursor-pointer">
                        <img class="h-[31.5px]" src="public/images/icon.png" alt="Icon">
                        <h1 class="text-[20.7px] font-extrabold text-[#87CEEB]">HERE UP</h1>
                    </div>
                </a>

                <!-- Navigation -->
                <nav class="flex items-center justify-between gap-5 text-[14.4px]">

                    <!-- Go To Features -->
                    <a class="text-[#91959C] hover:text-[#87CEEB] transition-colors" href="#features">Features</a>

                    <!-- Go To About -->
                    <a class="text-[#91959C] hover:text-[#87CEEB] transition-colors" href="#about">About</a>
                    
                    <!-- Go To Contact -->
                    <a class="text-[#91959C] hover:text-[#87CEEB] transition-colors" href="#contact">Contact</a>
                </nav>
            </div>
            
            <!-- Header Content 2 -->
            <div class="flex items-center justify-between gap-[9px]">
                <!-- Sign In Buttons -->
                <a href="src/views/sign-in.php" target="_self">
                    <button class='bg-white border border-[#DEDFE3] text-sm py-[6.75px] px-[13.5px] rounded-lg hover:shadow-sm cursor-pointer'>
                    SIGN IN
                    </button>
                </a>

                <!-- Sign Up Buttons -->
                <a href="src/views/sign-up.php" target="_self">
                    <button  class='bg-[#87CEEB] text-white text-sm py-[6.75px] px-[13.5px] rounded-lg hover:shadow-sm cursor-pointer'>
                    SIGN UP NOW
                    </button>
                </a>
            </div>
        </div>       
    </header>

    <!-- Main -->
    <main>
        <!-- Hero Section -->
        <section id="hero" class="flex items-center justify-between mt-[175px] mx-[116.1px]">
            <!-- Hero Content 1 -->
            <div class="flex-col">
                <!-- Motto and Short Description -->
                <h1 class="text-[#87CEEB] font-extrabold text-[43.2px]"> 
                    Dimensional <br> Attendance <br> Solutions
                </h1>
                <p class="ml-1 mt-[26.1px] text-[16.2px] text-[#91959C] font-light">
                    A web-based attendance solution for modern <br>
                    workplaces. Track, manage, and report attendance <br>
                    seamlessly from any device with internet access.
                </p>

                <div class="flex items-center gap-3">
                    <!-- Learn More Button -->
                    <a href="#about">
                        <button class="mt-[38.7px] w-[196.2px] h-[35px] bg-[#87CEEB] shadow-sm hover:shadow-md cursor-pointer rounded-lg text-white px-[10px]">
                            Learn More
                        </button>
                    </a>

                    <!-- Get Started Button -->
                    <a href="src/views/sign-up.php" target="_self">
                        <button class="mt-[38.7px] w-[199px] h-[35px] border border-[#87CEEB] hover:shadow-md cursor-pointer rounded-lg px-[10px] flex items-center justify-center bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 border border-[#87CEEB]/20 text-[#87CEEB]">
                            Get Started <i data-lucide="chevrons-right" style="stroke-width: 1.5;"></i>
                        </button>
                    </a>
                </div>
            </div>

            <!-- Hero Content 2 -->
            <img class="rounded-xl shadow-lg" src="public/images/index/index__hero-image.png" alt="Hero Image">
        </section>

        <!-- Features Section -->
        <section id="features" class="pt-[160px] mt-[160px] mx-[116.1px] mb-[80px]">
            <!-- Section Header -->
            <div class="text-center mb-[60px]">
                <h2 class="text-[#87CEEB] font-extrabold text-[36px] mb-[16px]">
                    Our Features
                </h2>
                <p class="text-[16.2px] text-[#91959C] font-light">
                    Powerful tools designed to streamline your attendance management
                </p>
            </div>

            <!-- Feature Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[32px]">
                <!-- Feature Card 1 - QR-Code Attendance -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="qr-code" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">QR-Code Attendance</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Scan and done in seconds, ensuring accurate timestamped records automatically.
                    </p>
                </div>

                <!-- Feature Card 2 - Real Time Reports -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="bar-chart-3" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">Real Time Reports</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Get comprehensive insights with detailed analytics and customizable reports for better decision making.
                    </p>
                </div>

                <!-- Feature Card 3 - Multi Mode Input -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="layers" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">Multi Mode Input</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Adapt to any situation with three flexible attendance modes, control over your attendance workflow.
                    </p>
                </div>

                <!-- Feature Card 4 - Secure Cloud Storage -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="shield-check" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">Secure Cloud Storage</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Your data is protected with enterprise-grade encryption and automatic cloud backups.
                    </p>
                </div>

                <!-- Feature Card 5 - Cross-Device Access -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="globe" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">Cross-Device Access</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Manage attendance from any device, anywhere in the world with just an internet connection.
                    </p>
                </div>

                <!-- Feature Card 6 - Smart Notifications -->
                <div class="bg-white border border-[#DEDFE3] rounded-xl p-[32px] hover:shadow-lg transition-shadow duration-300 cursor-pointer">
                    <div class="w-[64px] h-[64px] bg-[#87CEEB]/10 rounded-lg flex items-center justify-center mb-[24px]">
                        <i data-lucide="bell" class="text-[#87CEEB]" width="32" height="32"></i>
                    </div>
                    <h3 class="text-[20.7px] font-bold text-[#1a1a1a] mb-[12px]">Smart Notifications</h3>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        Stay on track with automated reminders for attendance deadlines and custom messages from admin to all members.
                    </p>
                </div>
            </div>
        </section>
        
        <!-- About Section -->
        <section id="about" class="pt-[160px] mt-[175px] mx-[116.1px]">
            <!-- Section Header -->
            <div class="text-center mb-[72px]">
                <h2 class="text-[36px] font-extrabold text-[#87CEEB] mb-[18px]">
                    About HereUp
                </h2>
                <p class="text-[16.2px] text-[#91959C] font-light">
                    Transforming attendance management with modern technology
                </p>
            </div>

            <!-- About Content Grid -->
            <div class="grid grid-cols-2 gap-[54px]">
                <!-- About Content 1: Our Story -->
                <div class="flex flex-col gap-[27px]">
                    <div class="flex items-center gap-4">
                        <div class="w-[54px] h-[54px] bg-gradient-to-br from-[#87CEEB] to-[#6BB6D6] rounded-xl flex items-center justify-center">
                            <i data-lucide="book-open" class="text-white" style="width: 28px; height: 28px; stroke-width: 2;"></i>
                        </div>
                        <h3 class="text-[24px] font-bold text-[#2D3142]">Our Story</h3>
                    </div>
                    <p class="text-[15px] text-[#91959C] leading-relaxed">
                        HereUp was born from the need to modernize attendance tracking in educational and workplace environments. We understand the frustrations of manual attendance systems—lost records, time consumption, and data inaccuracy. That's why we created a solution that's fast, secure, and accessible from anywhere.
                    </p>
                </div>
                
                <!-- About Content 2: Our Mission -->
                <div class="flex flex-col gap-[27px]">
                    <div class="flex items-center gap-4">
                        <div class="w-[54px] h-[54px] bg-gradient-to-br from-[#87CEEB] to-[#6BB6D6] rounded-xl flex items-center justify-center">
                            <i data-lucide="target" class="text-white" style="width: 28px; height: 28px; stroke-width: 2;"></i>
                        </div>
                        <h3 class="text-[24px] font-bold text-[#2D3142]">Our Mission</h3>
                    </div>

                    <p class="text-[15px] text-[#91959C] leading-relaxed">
                        To provide a dimensional attendance solution that empowers organizations to track, manage, and analyze attendance data with unprecedented ease and accuracy. We believe that effective attendance management should be simple, reliable, and accessible to everyone.
                    </p>
                </div>

                <!-- About Content 3: Why Choose Us -->
                <div class="flex flex-col gap-[27px]">
                    <div class="flex items-center gap-4">
                        <div class="w-[54px] h-[54px] bg-gradient-to-br from-[#87CEEB] to-[#6BB6D6] rounded-xl flex items-center justify-center">
                            <i data-lucide="award" class="text-white" style="width: 28px; height: 28px; stroke-width: 2;"></i>
                        </div>
                        <h3 class="text-[24px] font-bold text-[#2D3142]">Why Choose Us</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="text-[#87CEEB]" style="width: 22px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">Easy to Use:</span> Intuitive interface designed for all user levels, making attendance management effortless for everyone.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="text-[#87CEEB]" style="width: 14px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">Secure & Reliable:</span> Your data is protected with enterprise-level security.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="check-circle" class="text-[#87CEEB]" style="width: 14px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">24/7 Access:</span> Track attendance anytime, anywhere, from any device.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- About Content 4: Our Values -->
                <div class="flex flex-col gap-[27px]">
                    <div class="flex items-center gap-4">
                        <div class="w-[54px] h-[54px] bg-gradient-to-br from-[#87CEEB] to-[#6BB6D6] rounded-xl flex items-center justify-center">
                            <i data-lucide="heart-handshake" class="text-white" style="width: 28px; height: 28px; stroke-width: 2;"></i>
                        </div>
                        <h3 class="text-[24px] font-bold text-[#2D3142]">Our Values</h3>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <i data-lucide="sparkles" class="text-[#87CEEB]" style="width: 14px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">Innovation:</span> Continuously improving our platform to meet your needs.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="users" class="text-[#87CEEB]" style="width: 14px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">User-Centric:</span> Your needs drive our development.
                            </p>
                        </div>
                        <div class="flex items-start gap-3">
                            <i data-lucide="shield-check" class="text-[#87CEEB]" style="width: 14px; height: 28px; stroke-width: 2;"></i>
                            <p class="text-[15px] text-[#91959C]">
                                <span class="font-semibold text-[#2D3142]">Integrity:</span> Transparent and honest in everything we do.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="mt-[72px] grid grid-cols-4 gap-[36px]">
                <!-- Stat 1 -->
                <div class="bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 border border-[#87CEEB]/20 rounded-xl p-[27px] text-center hover:shadow-lg transition-shadow">
                    <div class="flex justify-center mb-[18px]">
                        <i data-lucide="users-round" class="text-[#87CEEB]" style="width: 36px; height: 36px; stroke-width: 2;"></i>
                    </div>
                    <h4 class="text-[32px] font-bold text-[#2D3142] mb-[9px]">1000+</h4>
                    <p class="text-[14px] text-[#91959C]">Active Users</p>
                </div>

                <!-- Stat 2 -->
                <div class="bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 border border-[#87CEEB]/20 rounded-xl p-[27px] text-center hover:shadow-lg transition-shadow">
                    <div class="flex justify-center mb-[18px]">
                        <i data-lucide="building-2" class="text-[#87CEEB]" style="width: 36px; height: 36px; stroke-width: 2;"></i>
                    </div>
                    <h4 class="text-[32px] font-bold text-[#2D3142] mb-[9px]">50+</h4>
                    <p class="text-[14px] text-[#91959C]">Organizations</p>
                </div>
                
                <!-- Stat 3 -->
                <div class="bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 border border-[#87CEEB]/20 rounded-xl p-[27px] text-center hover:shadow-lg transition-shadow">
                    <div class="flex justify-center mb-[18px]">
                        <i data-lucide="calendar-check" class="text-[#87CEEB]" style="width: 36px; height: 36px; stroke-width: 2;"></i>
                    </div>
                    <h4 class="text-[32px] font-bold text-[#2D3142] mb-[9px]">99.9%</h4>
                    <p class="text-[14px] text-[#91959C]">Accuracy Rate</p>
                </div>

                <!-- Stat 4 -->
                <div class="bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 border border-[#87CEEB]/20 rounded-xl p-[27px] text-center hover:shadow-lg transition-shadow">
                    <div class="flex justify-center mb-[18px]">
                        <i data-lucide="clock" class="text-[#87CEEB]" style="width: 36px; height: 36px; stroke-width: 2;"></i>
                    </div>
                    <h4 class="text-[32px] font-bold text-[#2D3142] mb-[9px]">24/7</h4>
                    <p class="text-[14px] text-[#91959C]">Support Available</p>
                </div>
            </div>
        </section>
        
        <!-- Contact Section -->
        <section id="contact" class="pt-[160px] mt-[175px] mx-[116.1px] mb-[80px]">
            <!-- Section Header -->
            <div class="text-center mb-[72px]">
                <h2 class="text-[36px] font-extrabold text-[#87CEEB] mb-[18px]">
                    Get In Touch
                </h2>
                <p class="text-[16.2px] text-[#91959C] font-light">
                    Have questions? We'd love to hear from you. Send us a message and we'll respond as soon as possible.
                </p>
            </div>

            <!-- Contact Content Grid -->
            <div class="grid grid-cols-2 gap-[54px]">
                <!-- Contact Form -->
                <div class="bg-white rounded-xl border border-[#DEDFE3] p-[36px] shadow-sm hover:shadow-md transition-shadow">
                    <h3 class="text-[24px] font-bold text-[#87CEEB] mb-[24px]">Send us a Message</h3>
                    <form class="space-y-[20px]">
                        <!-- Name Input -->
                        <div>
                            <label class="block text-[14px] font-medium text-[#91959C] mb-[8px]">Your Name</label>
                            <input type="text" placeholder="John Doe" 
                                class="w-full px-[16px] py-[12px] border border-[#DEDFE3] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#87CEEB] focus:border-transparent">
                        </div>

                        <!-- Email Input -->
                        <div>
                            <label class="block text-[14px] font-medium text-[#91959C] mb-[8px]">Email Address</label>
                            <input type="email" placeholder="john@example.com" 
                                class="w-full px-[16px] py-[12px] border border-[#DEDFE3] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#87CEEB] focus:border-transparent">
                        </div>

                        <!-- Subject Input -->
                        <div>
                            <label class="block text-[14px] font-medium text-[#91959C] mb-[8px]">Subject</label>
                            <input type="text" placeholder="How can we help?" 
                                class="w-full px-[16px] py-[12px] border border-[#DEDFE3] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#87CEEB] focus:border-transparent">
                        </div>

                        <!-- Message Input -->
                        <div>
                            <label class="block text-[14px] font-medium text-[#91959C] mb-[8px]">Message</label>
                            <textarea rows="5" placeholder="Your message..." 
                                class="w-full px-[16px] py-[12px] border border-[#DEDFE3] rounded-lg focus:outline-none focus:ring-2 focus:ring-[#87CEEB] focus:border-transparent resize-none"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                            class="w-full bg-[#87CEEB] text-white py-[12px] rounded-lg hover:bg-[#6BB6D6] transition-colors font-medium flex items-center justify-center gap-2">
                            Send Message
                            <i data-lucide="send" class="w-4 h-4"></i>
                        </button>
                    </form>
                </div>

                <!-- Contact Information -->
                <div class="space-y-[24px]">
                    <!-- Developer Info Card -->
                    <div class="bg-white rounded-xl border border-[#DEDFE3] p-[36px] shadow-sm hover:shadow-md transition-shadow">
                        <h3 class="text-[24px] font-bold text-[#87CEEB] mb-[32px]">Developer Contact</h3>
                        
                        <!-- Developer Profile -->
                        <div class="flex flex-col items-center mb-[32px]">
                            <!-- Photo with circular frame -->
                            <div class="relative mb-[16px] group">
                                <div class="relative w-[160px] h-[160px] rounded-full shadow-lg overflow-hidden">
                                    <img src="public/images/dev.png" 
                                        alt="Developer Photo" 
                                        class="w-full h-full object-cover">
                                </div>
                            </div>

                            <!-- Developer Name & Title -->
                            <h4 class="text-[20px] font-bold text-[#91959C] mb-[4px]">Yudo Harun Wardhana</h4>
                            <p class="text-[14px] text-[#91959C] font-medium mb-[8px]">Full Stack Developer</p>
                            <div class="flex items-center gap-[8px] text-[#91959C]">
                                <i data-lucide="map-pin" class="w-4 h-4"></i>
                                <p class="text-[13px]">Bandung, Indonesia</p>
                            </div>
                        </div>

                        <!-- Developer Info -->
                        <div class="space-y-[16px]">
                            <!-- GitHub -->
                            <a href="https://github.com/yudotheengineer" target="_blank" 
                                class="flex items-center gap-[16px] p-[16px] rounded-lg hover:bg-[#87CEEB]/5 transition-colors group">
                                <div class="w-[48px] h-[48px] bg-[#24292e] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-[28px] h-[28px] text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[14px] text-[#91959C] font-medium">GitHub</p>
                                    <p class="text-[16px] text-[#24292e] font-semibold group-hover:text-[#87CEEB]">@YudoTheEngineer</p>
                                </div>
                            </a>

                            <!-- Email -->
                            <a href="mailto:yudotheengineer@gmail.com" 
                                class="flex items-center gap-[16px] p-[16px] rounded-lg hover:bg-[#87CEEB]/5 transition-colors group">
                                <div class="w-[48px] h-[48px] bg-gradient-to-br from-[#EA4335] to-[#FBBC04] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-[28px] h-[28px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[14px] text-[#91959C] font-medium">Email</p>
                                    <p class="text-[16px] text-[#EA4335] font-semibold group-hover:text-[#87CEEB]">yudotheengineer@gmail.com</p>
                                </div>
                            </a>

                            <!-- Phone -->
                            <a href="tel:+6285523715835" 
                                class="flex items-center gap-[16px] p-[16px] rounded-lg hover:bg-[#87CEEB]/5 transition-colors group">
                                <div class="w-[48px] h-[48px] bg-gradient-to-br from-[#25D366] to-[#128C7E] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-[28px] h-[28px] text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[14px] text-[#91959C] font-medium">Phone</p>
                                    <p class="text-[16px] text-[#25D366] font-semibold group-hover:text-[#87CEEB]">+62 855-2371-5835</p>
                                </div>
                            </a>

                            <!-- LinkedIn -->
                            <a href="https://linkedin.com/in/YudoTheEngineer" target="_blank"
                                class="flex items-center gap-[16px] p-[16px] rounded-lg hover:bg-[#87CEEB]/5 transition-colors group">
                                <div class="w-[48px] h-[48px] bg-[#0A66C2] rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <svg class="w-[28px] h-[28px] text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[14px] text-[#91959C] font-medium">LinkedIn</p>
                                    <p class="text-[16px] text-[#0A66C2] font-semibold group-hover:text-[#87CEEB]">Yudo Wardhana</p>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Info Card -->
                    <div class="bg-gradient-to-br from-[#87CEEB]/10 to-[#6BB6D6]/5 rounded-xl border border-[#87CEEB]/20 p-[36px]">
                        <div class="flex items-start gap-[16px]">
                            <div class="w-[48px] h-[48px] bg-[#87CEEB] rounded-lg flex items-center justify-center flex-shrink-0">
                                <i data-lucide="info" class="text-white w-[24px] h-[24px]"></i>
                            </div>
                            <div>
                                <h4 class="text-[18px] font-bold text-[#87CEEB] mb-[8px]">Response Time</h4>
                                <p class="text-[14px] text-[#91959C]">
                                    We typically respond within 24 hours on business days. For urgent matters, please call us directly.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <footer class="bg-[#F5F5F5] border-t border-[#DEDFE3] mt-[160px]">
        <div class="mx-[116.1px] py-[80px]">
            <!-- Footer Content Grid -->
            <div class="grid grid-cols-4 gap-[54px] mb-[60px]">
                <!-- Footer Col 1: Brand Info -->
                <div>
                    <div class="flex items-center justify-start gap-[13.5px] mb-[24px]">
                        <img class="h-[31.5px]" src="public/images/icon.png" alt="Icon">
                        <h3 class="text-[20.7px] font-extrabold text-[#87CEEB]">HERE UP</h3>
                    </div>
                    <p class="text-[14.4px] text-[#91959C] font-light leading-relaxed">
                        A modern attendance solution designed for the future of work. Track, manage, and report with ease.
                    </p>
                </div>

                <!-- Footer Col 2: Product -->
                <div>
                    <h4 class="text-[16.2px] font-bold text-[#2C2E31] mb-[20px]">Product</h4>
                    <ul class="space-y-[12px]">
                        <li><a href="#features" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Features</a></li>
                        <li><a href="https://github.com/YudoTheEngineer/Here-Up" target="_blank" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Updates</a></li>
                    </ul>
                </div>

                <!-- Footer Col 3: Company -->
                <div>
                    <h4 class="text-[16.2px] font-bold text-[#2C2E31] mb-[20px]">Developer</h4>
                    <ul class="space-y-[12px]">
                        <li><a href="#about" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">About Us</a></li>
                        <li><a href="#" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Blog</a></li>
                        <li><a href="#contact" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Contact</a></li>
                    </ul>
                </div>

                <!-- Footer Col 4: Support -->
                <div>
                    <h4 class="text-[16.2px] font-bold text-[#2C2E31] mb-[20px]">Support</h4>
                    <ul class="space-y-[12px]">
                        <li><a href="#" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Documentation</a></li>
                        <li><a href="#" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Support</a></li>
                    </ul>
                </div>
            </div>

            <!-- Footer Divider -->
            <div class="border-t border-[#DEDFE3] py-[30px]">
                <!-- Footer Bottom -->
                <div class="flex items-center justify-between">
                    <!-- Copyright -->
                    <p class="text-[14.4px] text-[#91959C] font-light">
                        &copy; 2026 HereUp | Dimensional Attendance Solutions.
                    </p>

                    <!-- Legal Links -->
                    <div class="flex items-center justify-between gap-[30px]">
                        <a href="#" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Privacy Policy</a>
                        <a href="#" class="text-[14.4px] text-[#91959C] hover:text-[#87CEEB] transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Initialize Lucide Icon
        lucide.createIcons();
    </script>
</body>
</html> 