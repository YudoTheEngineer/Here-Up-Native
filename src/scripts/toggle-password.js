/**
 * signup-toggle.js
 * Tanggung jawab tunggal: toggle visibilitas password pada field
 * "Password" dan "Confirm Password", disertai pergantian ikon mata.
 *
 * Konvensi ikon:
 *   - eye      → password sedang TERSEMBUNYI  (klik untuk tampilkan)
 *   - eye-off  → password sedang TERLIHAT     (klik untuk sembunyikan)
 */

document.addEventListener("DOMContentLoaded", () => {

    /**
     * Inisialisasi satu pasang tombol toggle + input.
     * @param {string} inputId   - id dari <input type="password">
     * @param {string} btnId     - id dari <button> toggle
     * @param {string} iconId    - id dari <i data-lucide="...">
     */
    function initToggle(inputId, btnId, iconId) {
        const input = document.getElementById(inputId);
        const btn   = document.getElementById(btnId);
        const icon  = document.getElementById(iconId);

        if (!input || !btn || !icon) return;

        btn.addEventListener("click", () => {
            const isHidden = input.type === "password";

            // Ganti tipe input
            input.type = isHidden ? "text" : "password";

            // eye     = password tersembunyi → tampilkan ikon "lihat"
            // eye-off = password terlihat    → tampilkan ikon "sembunyikan"
            icon.setAttribute("data-lucide", isHidden ? "eye-off" : "eye");

            // Re-render hanya ikon ini agar efisien
            lucide.createIcons({ nodes: [icon] });

            // Update aria-label untuk aksesibilitas
            btn.setAttribute(
                "aria-label",
                isHidden ? "Sembunyikan password" : "Tampilkan password"
            );
        });
    }

    // Inisialisasi kedua field password
    initToggle("password",        "toggle-password",         "toggle-password-icon");
    initToggle("confirm_password", "toggle-confirm-password", "toggle-confirm-icon");

});