document.addEventListener("DOMContentLoaded", () => {

    function initToggle(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn   = document.getElementById(btnId);

        if (!input || !btn) return;

        btn.addEventListener("click", (e) => {
            e.preventDefault();

            const isPassword = input.type === "password";

            input.type = isPassword ? "text" : "password";

            const icon = btn.querySelector("[data-lucide]");
            
            if (icon) {
                const newIconName = isPassword ? "eye-off" : "eye";
                icon.setAttribute("data-lucide", newIconName);
                
                lucide.createIcons({
                    attrs: {
                        class: ["w-5", "h-5"]
                    }
                });
            }

            btn.setAttribute(
                "aria-label",
                isPassword ? "Sembunyikan password" : "Tampilkan password"
            );
        });
    }

    initToggle("password", "toggle-password");
    initToggle("confirm_password", "toggle-confirm-password");
});