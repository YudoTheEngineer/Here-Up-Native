document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("signin-form");

    const fields = {
        username: {
            input: document.getElementById("username"),
            error: document.getElementById("error-username"),
        },
        password: {
            input: document.getElementById("password"),
            error: document.getElementById("error-password"),
        },
    };

    const CLS = {
        base:    ["border-[#DEE1E6]"],
        focusDef:["focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
        error:   ["border-red-400",   "focus:border-red-400",   "focus:ring-red-400"],
        success: ["border-[#87CEEB]", "focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
    };

    const ALL_STATE_CLASSES = [...CLS.base, ...CLS.focusDef, ...CLS.error, ...CLS.success];

    function showError(input, errorEl, message) {
        errorEl.textContent = message;
        errorEl.classList.remove("hidden");
        input.classList.remove(...ALL_STATE_CLASSES);
        input.classList.add(...CLS.error);
    }

    function markSuccess(input, errorEl) {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
        input.classList.remove(...ALL_STATE_CLASSES);
        input.classList.add(...CLS.success);
    }

    // ── Validator ──────────────────────────────────────────────────────────

    function validateUsername() {
        const { input, error } = fields.username;
        if (input.value.trim() === "") {
            showError(input, error, "You Forgot to Fill Username.");
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

    // ── Listener ────────────────────────────────────────────────────────────

    fields.username.input.addEventListener("blur", validateUsername);
    fields.username.input.addEventListener("input", () => {
        if (!fields.username.error.classList.contains("hidden")) validateUsername();
    });

    fields.password.input.addEventListener("blur", validatePassword);
    fields.password.input.addEventListener("input", () => {
        if (!fields.password.error.classList.contains("hidden")) validatePassword();
    });

    // ── Submit handler ──────────────────────────────────────────────────────

    form.addEventListener("submit", (e) => {
        const isUsernameValid = validateUsername();
        const isPasswordValid = validatePassword();

        if (!isUsernameValid || !isPasswordValid) {
            e.preventDefault();
            
            // Fokus ke field pertama yang error
            if (!isUsernameValid) {
                fields.username.input.focus();
            } else if (!isPasswordValid) {
                fields.password.input.focus();
            }
        }
    });
});