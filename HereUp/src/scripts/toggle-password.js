document.addEventListener("DOMContentLoaded", () => {

    function initToggle(inputId, btnId) {
        const input = document.getElementById(inputId);
        const btn   = document.getElementById(btnId);

        if (!input || !btn) return;

        btn.addEventListener("click", (e) => {
            // Mencegah form submit jika tombol tidak sengaja dianggap submit
            e.preventDefault();

            // 1. Cek tipe saat ini
            const isPassword = input.type === "password";

            // 2. Toggle tipe input
            input.type = isPassword ? "text" : "password";

            // 3. Cari ikon di dalam tombol (baik itu <i> atau <svg>)
            const icon = btn.querySelector("[data-lucide]");
            
            if (icon) {
                // Set atribut baru: jika sekarang teks (tadi password), ikon jadi eye-off
                const newIconName = isPassword ? "eye-off" : "eye";
                icon.setAttribute("data-lucide", newIconName);
                
                // 4. Re-render ikon khusus untuk tombol ini
                lucide.createIcons({
                    attrs: {
                        class: ["w-5", "h-5"] // Pastikan class tetap terjaga
                    }
                });
            }

            // 5. Update aksesibilitas
            btn.setAttribute(
                "aria-label",
                isPassword ? "Sembunyikan password" : "Tampilkan password"
            );
        });
    }

    // Inisialisasi untuk kedua field
    initToggle("password", "toggle-password");
    initToggle("confirm_password", "toggle-confirm-password");
});