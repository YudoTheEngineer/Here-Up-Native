document.addEventListener("DOMContentLoaded", () => {

    // ── Referensi elemen ─────────────────────────────────────────────────────

    const form = document.querySelector("form");

    const fields = {
        username: {
            input: document.getElementById("username"),
            error: document.getElementById("error-username"),
        },
        email: {
            input: document.getElementById("email"),
            error: document.getElementById("error-email"),
        },
        password: {
            input: document.getElementById("password"),
            error: document.getElementById("error-password"),
        },
        confirmPassword: {
            input: document.getElementById("confirm_password"),
            error: document.getElementById("error-confirm-password"),
        },
    };

    const tosCheckbox  = document.getElementById("tos-checkbox");
    const errorCheckbox = document.getElementById("error-checkbox");


    // ── Konstanta class Tailwind ─────────────────────────────────────────────

    const CLS = {
        base:    ["border-[#DEE1E6]"],
        focusDef:["focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
        error:   ["border-red-400",   "focus:border-red-400",   "focus:ring-red-400"],
        success: ["border-[#87CEEB]", "focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
    };

    const ALL_STATE_CLASSES = [...CLS.base, ...CLS.focusDef, ...CLS.error, ...CLS.success];


    // ── UI helpers ───────────────────────────────────────────────────────────

    /** Tampilkan pesan error + border merah pada sebuah field. */
    function showError(input, errorEl, message) {
        errorEl.textContent = message;
        errorEl.classList.remove("hidden");
        input.classList.remove(...ALL_STATE_CLASSES);
        input.classList.add(...CLS.error);
    }

    /** Hapus error dan kembalikan border ke default. */
    function clearError(input, errorEl) {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
        input.classList.remove(...ALL_STATE_CLASSES);
        input.classList.add(...CLS.base, ...CLS.focusDef);
    }

    /** Tandai field valid dengan border hijau. */
    function markSuccess(input, errorEl) {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
        input.classList.remove(...ALL_STATE_CLASSES);
        input.classList.add(...CLS.success);
    }


    // ── Validator per field ──────────────────────────────────────────────────

    function validateUsername() {
        const { input, error } = fields.username;
        const value = input.value.trim();

        if (value === "") {
            showError(input, error, "You Forgot to Fill Username.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validateEmail() {
        const { input, error } = fields.email;
        const value = input.value.trim();

        if (value === "") {
            showError(input, error, "You Forgot to Fill Email.");
            return false;
        }

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showError(input, error, "Wrong Format Email.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validatePassword() {
        const { input, error } = fields.password;

        if (input.value === "") {
            showError(input, error, "You Forgot to Fill Password.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validateConfirmPassword() {
        const { input, error } = fields.confirmPassword;
        const password        = fields.password.input.value;
        const confirmPassword = input.value;

        if (confirmPassword === "") {
            showError(input, error, "You Forgot to Fill Confirm Password.");
            return false;
        }

        if (password !== confirmPassword) {
            showError(input, error, "Password Doesn't Match.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    // ── Listener validasi live ───────────────────────────────────────────────

    // Username: validasi saat keluar field; perbaiki live jika error sudah muncul
    fields.username.input.addEventListener("blur", validateUsername);
    fields.username.input.addEventListener("input", () => {
        if (!fields.username.error.classList.contains("hidden")) validateUsername();
    });

    // Email: sama dengan username
    fields.email.input.addEventListener("blur", validateEmail);
    fields.email.input.addEventListener("input", () => {
        if (!fields.email.error.classList.contains("hidden")) validateEmail();
    });

    // Password: validasi saat blur; jika confirm sudah diisi, re-check juga
    fields.password.input.addEventListener("blur", validatePassword);
    fields.password.input.addEventListener("input", () => {
        if (!fields.password.error.classList.contains("hidden")) validatePassword();
        if (fields.confirmPassword.input.value !== "") validateConfirmPassword();
    });

    // Confirm password: validasi live setiap keystroke
    fields.confirmPassword.input.addEventListener("blur", validateConfirmPassword);
    fields.confirmPassword.input.addEventListener("input", validateConfirmPassword);


    // ── Submit handler ───────────────────────────────────────────────────────

    form.addEventListener("submit", (e) => {
        // Jalankan semua validator sekaligus
        const results = [
            validateUsername(),
            validateEmail(),
            validatePassword(),
            validateConfirmPassword()
        ];

        const isFormValid = results.every(Boolean);

        if (!isFormValid) {
            e.preventDefault();

            // Fokus ke field invalid pertama agar UX lebih baik
            const firstInvalid = Object.values(fields)
                .map(f => f.input)
                .find(input => input.classList.contains("border-red-400"));

            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
                firstInvalid.focus();
            }
        }
    });

});