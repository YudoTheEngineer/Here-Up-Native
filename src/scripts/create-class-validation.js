// ── Modal open / close ────────────────────────────────────────────────────

function openModal() {
    const modal = document.getElementById("createClassModal");
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeModal() {
    const modal = document.getElementById("createClassModal");
    modal.classList.remove("flex");
    modal.classList.add("hidden");
    resetForm();
}

document.getElementById("createClassModal").addEventListener("click", function (e) {
    if (e.target === this) closeModal();
});


// ── Referensi elemen ──────────────────────────────────────────────────────

const fields = {
    className: {
        input: document.getElementById("class_name"),
        error: document.getElementById("error-class_name"),
    },
    classDescription: {
        input: document.getElementById("class_description"),
        error: document.getElementById("error-class_description"),
    },
    classMode: {
        input: document.querySelector("select[name='class_mode']"),
        error: document.getElementById("error-class_mode"),
    },
};


// ── Konstanta class Tailwind ──────────────────────────────────────────────

const CLS = {
    base:     ["border-[#DEE1E6]"],
    focusDef: ["focus:ring-[#87CEEB]", "focus:border-[#87CEEB]"],
    error:    ["border-red-400", "focus:border-red-400", "focus:ring-red-400"],
    success:  ["border-[#87CEEB]", "focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
};

const ALL_STATE_CLASSES = [...CLS.base, ...CLS.focusDef, ...CLS.error, ...CLS.success];


// ── UI helpers ────────────────────────────────────────────────────────────

function showError(input, errorEl, message) {
    errorEl.textContent = message;
    errorEl.classList.remove("hidden");
    input.classList.remove(...ALL_STATE_CLASSES);
    input.classList.add(...CLS.error);
}

function clearError(input, errorEl) {
    errorEl.textContent = "";
    errorEl.classList.add("hidden");
    input.classList.remove(...ALL_STATE_CLASSES);
    input.classList.add(...CLS.base, ...CLS.focusDef);
}

function markSuccess(input, errorEl) {
    errorEl.textContent = "";
    errorEl.classList.add("hidden");
    input.classList.remove(...ALL_STATE_CLASSES);
    input.classList.add(...CLS.success);
}


// ── Validator per field ───────────────────────────────────────────────────

function validateClassName() {
    const { input, error } = fields.className;
    const value = input.value.trim();

    if (value === "") {
        showError(input, error, "You Forgot to Fill Class Name.");
        return false;
    }
    if (value.length < 3) {
        showError(input, error, "Class Name must be at least 3 characters.");
        return false;
    }

    markSuccess(input, error);
    return true;
}

function validateClassDescription() {
    const { input, error } = fields.classDescription;

    if (input.value.trim() === "") {
        showError(input, error, "You Forgot to Fill Description.");
        return false;
    }

    markSuccess(input, error);
    return true;
}

function validateClassMode() {
    const { input, error } = fields.classMode;

    if (!input.value) {
        showError(input, error, "Please Select a Mode.");
        return false;
    }

    markSuccess(input, error);
    return true;
}


// ── Live listeners ────────────────────────────────────────────────────────

fields.className.input.addEventListener("blur", validateClassName);
fields.className.input.addEventListener("input", () => {
    if (!fields.className.error.classList.contains("hidden")) validateClassName();
});

fields.classDescription.input.addEventListener("blur", validateClassDescription);
fields.classDescription.input.addEventListener("input", () => {
    if (!fields.classDescription.error.classList.contains("hidden")) validateClassDescription();
});

fields.classMode.input.addEventListener("change", validateClassMode);


// ── Submit handler ────────────────────────────────────────────────────────

document.querySelector("#createClassModal form").addEventListener("submit", (e) => {
    const results = [
        validateClassName(),
        validateClassDescription(),
        validateClassMode(),
    ];

    if (!results.every(Boolean)) {
        e.preventDefault();

        const firstInvalid = Object.values(fields)
            .map(f => f.input)
            .find(input => input.classList.contains("border-red-400"));

        if (firstInvalid) firstInvalid.focus();
    }
});


// ── Reset form saat modal ditutup ─────────────────────────────────────────

function resetForm() {
    document.querySelector("#createClassModal form").reset();
    Object.values(fields).forEach(({ input, error }) => clearError(input, error));
}