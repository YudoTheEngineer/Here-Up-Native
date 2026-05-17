document.addEventListener("DOMContentLoaded", () => {

    // ── Inisialisasi Lucide Icons ────────────────────────────────────────────
    lucide.createIcons();


    // ── Konstanta class Tailwind ─────────────────────────────────────────────

    const CLS = {
        base:     ["border-[#DEE1E6]"],
        focusDef: ["focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
        error:    ["border-red-400",   "focus:border-red-400",   "focus:ring-red-400"],
        success:  ["border-[#87CEEB]", "focus:border-[#87CEEB]", "focus:ring-[#87CEEB]"],
    };

    const ALL_STATE_CLASSES = [...CLS.base, ...CLS.focusDef, ...CLS.error, ...CLS.success];


    // ── UI helpers ───────────────────────────────────────────────────────────

    function showError(input, errorEl, message) {
        errorEl.textContent = message;
        errorEl.classList.remove("hidden");
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.error);
        }
    }

    function clearError(input, errorEl) {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.base, ...CLS.focusDef);
        }
    }

    function markSuccess(input, errorEl) {
        errorEl.textContent = "";
        errorEl.classList.add("hidden");
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.success);
        }
    }


    // ── Referensi field ──────────────────────────────────────────────────────

    const fields = {
        fullname: {
            input: document.getElementById("fullname"),
            error: document.getElementById("error-fullname"),
        },
        gender: {
            input: document.getElementById("gender"),   // hidden input
            error: document.getElementById("error-gender"),
        },
    };


    // ── Validator per field ──────────────────────────────────────────────────

    function validateFullname() {
        const { input, error } = fields.fullname;
        const value = input.value.trim();

        if (value === "") {
            showError(input, error, "Full name is required.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validateGender() {
        const { input, error } = fields.gender;
        const value = input.value;

        if (value === "") {
            showError(null, error, "Please select a gender.");
            return false;
        }

        clearError(null, error);
        return true;
    }


    // ── Listener validasi live ───────────────────────────────────────────────

    if (fields.fullname.input) {
        fields.fullname.input.addEventListener("blur", validateFullname);
        fields.fullname.input.addEventListener("input", () => {
            if (!fields.fullname.error.classList.contains("hidden")) validateFullname();
        });
    }


    // ── Add Member Modal ─────────────────────────────────────────────────────

    window.openAddMemberModal = function () {
        const modal = document.getElementById("addMemberModal");
        modal.classList.remove("hidden");
        modal.classList.add("flex");
    };

    window.closeAddMemberModal = function () {
        const modal = document.getElementById("addMemberModal");
        modal.classList.add("hidden");
        modal.classList.remove("flex");
        resetModal();
    };

    function resetModal() {
        // Reset field values
        if (fields.fullname.input) fields.fullname.input.value = "";
        if (fields.gender.input)   fields.gender.input.value   = "";

        // Reset error states
        Object.values(fields).forEach(({ input, error }) => {
            clearError(input, error);
        });

        // Reset gender UI
        const maleLabel   = document.getElementById("gender-male-label");
        const femaleLabel = document.getElementById("gender-female-label");
        if (maleLabel)   { maleLabel.classList.remove("border-[#93C5FD]", "bg-blue-50");  maleLabel.classList.add("border-[#DEE1E6]"); }
        if (femaleLabel) { femaleLabel.classList.remove("border-[#93C5FD]", "bg-pink-50"); femaleLabel.classList.add("border-[#DEE1E6]"); }

        // Reset photo tab ke default
        switchPhotoTab("default");
        const defaultPhoto = document.getElementById("default_photo");
        if (defaultPhoto) defaultPhoto.value = "default-profile-1.svg";

        document.querySelectorAll("#default-photo-grid > div").forEach(el => {
            el.classList.remove("border-[#93C5FD]");
            el.classList.add("border-transparent");
        });

        const fileInput = document.getElementById("profile_picture");
        if (fileInput) fileInput.value = "";

        const preview = document.getElementById("picture-preview");
        if (preview) { preview.classList.add("hidden"); preview.src = ""; }

        const icon = document.getElementById("photo-placeholder-icon");
        if (icon) icon.classList.remove("hidden");

        const filename = document.getElementById("picture-filename");
        if (filename) filename.textContent = "No file chosen";
    }


    // ── Gender Selection ─────────────────────────────────────────────────────

    window.selectGender = function (val) {
        const genderInput = document.getElementById("gender");
        if (genderInput) genderInput.value = val;

        const maleLabel   = document.getElementById("gender-male-label");
        const femaleLabel = document.getElementById("gender-female-label");

        // Reset keduanya
        maleLabel.classList.remove("border-[#93C5FD]", "bg-blue-50");
        femaleLabel.classList.remove("border-[#93C5FD]", "bg-pink-50");
        maleLabel.classList.add("border-[#DEE1E6]");
        femaleLabel.classList.add("border-[#DEE1E6]");

        if (val == 1) {
            maleLabel.classList.remove("border-[#DEE1E6]");
            maleLabel.classList.add("border-[#93C5FD]", "bg-blue-50");
        } else {
            femaleLabel.classList.remove("border-[#DEE1E6]");
            femaleLabel.classList.add("border-[#93C5FD]", "bg-pink-50");
        }

        // Hapus error gender setelah dipilih
        clearError(null, fields.gender.error);
    };


    // ── Photo Tab ────────────────────────────────────────────────────────────

    window.switchPhotoTab = function (tab) {
        const isDefault = tab === "default";

        const panelDefault = document.getElementById("panel-default");
        const panelUpload  = document.getElementById("panel-upload");
        const tabDefault   = document.getElementById("tab-default");
        const tabUpload    = document.getElementById("tab-upload");

        if (panelDefault) panelDefault.classList.toggle("hidden", !isDefault);
        if (panelUpload)  panelUpload.classList.toggle("hidden", isDefault);

        const activeClass   = "text-xs px-3 py-1.5 rounded-lg bg-[#93C5FD] text-white transition-all";
        const inactiveClass = "text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-500 hover:bg-gray-200 transition-all";

        if (tabDefault) tabDefault.className = isDefault ? activeClass : inactiveClass;
        if (tabUpload)  tabUpload.className  = !isDefault ? activeClass : inactiveClass;

        if (isDefault) {
            const fileInput = document.getElementById("profile_picture");
            if (fileInput) fileInput.value = "";

            const preview = document.getElementById("picture-preview");
            if (preview) { preview.classList.add("hidden"); preview.src = ""; }

            const icon = document.getElementById("photo-placeholder-icon");
            if (icon) icon.classList.remove("hidden");

            const filename = document.getElementById("picture-filename");
            if (filename) filename.textContent = "No file chosen";
        } else {
            const defaultPhoto = document.getElementById("default_photo");
            if (defaultPhoto) defaultPhoto.value = "";

            document.querySelectorAll("#default-photo-grid > div").forEach(el => {
                el.classList.remove("border-[#93C5FD]");
                el.classList.add("border-transparent");
            });
        }
    };


    // ── Default Photo Selection ──────────────────────────────────────────────

    window.selectDefaultPhoto = function (num) {
        document.querySelectorAll("#default-photo-grid > div").forEach(el => {
            el.classList.remove("border-[#93C5FD]");
            el.classList.add("border-transparent");
        });

        const selected = document.getElementById("default-opt-" + num);
        if (selected) {
            selected.classList.remove("border-transparent");
            selected.classList.add("border-[#93C5FD]");
        }

        const defaultPhoto = document.getElementById("default_photo");
        if (defaultPhoto) defaultPhoto.value = "default-profile-" + num + ".svg";
    };


    // ── Photo Preview ────────────────────────────────────────────────────────

    window.previewPhoto = function (event) {
        const file = event.target.files[0];
        if (!file) return;

        const filename = document.getElementById("picture-filename");
        if (filename) filename.textContent = file.name;

        const reader = new FileReader();
        reader.onload = (e) => {
            const preview = document.getElementById("picture-preview");
            const icon    = document.getElementById("photo-placeholder-icon");
            if (preview) { preview.src = e.target.result; preview.classList.remove("hidden"); }
            if (icon)    icon.classList.add("hidden");
        };
        reader.readAsDataURL(file);
    };


    // ── Handle Submit (Add Member) ───────────────────────────────────────────

    window.handleAddMember = function () {
        const results = [
            validateFullname(),
            validateGender(),
        ];

        const isFormValid = results.every(Boolean);

        if (!isFormValid) {
            // Scroll ke field error pertama
            const firstInvalidInput = Object.values(fields)
                .map(f => f.input)
                .find(input => input && input.classList.contains("border-red-400"));

            if (firstInvalidInput) {
                firstInvalidInput.scrollIntoView({ behavior: "smooth", block: "center" });
                firstInvalidInput.focus();
            }
            return;
        }

        document.getElementById("formAddMember").submit();
    };


    // ── Tutup Modal dengan Escape ────────────────────────────────────────────

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") window.closeAddMemberModal();
    });

});