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
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove("hidden");
        }
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.error);
        }
    }

    function clearError(input, errorEl) {
        if (errorEl) {
            errorEl.textContent = "";
            errorEl.classList.add("hidden");
        }
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.base, ...CLS.focusDef);
        }
    }

    function markSuccess(input, errorEl) {
        if (errorEl) {
            errorEl.textContent = "";
            errorEl.classList.add("hidden");
        }
        if (input) {
            input.classList.remove(...ALL_STATE_CLASSES);
            input.classList.add(...CLS.success);
        }
    }


    // ════════════════════════════════════════════════════════════════════════
    // ADD MEMBER MODAL
    // ════════════════════════════════════════════════════════════════════════

    const fields = {
        fullname: {
            input: document.getElementById("fullname"),
            error: document.getElementById("error-fullname"),
        },
        gender: {
            input: document.getElementById("gender"),
            error: document.getElementById("error-gender"),
        },
    };

    function validateFullname() {
        const { input, error } = fields.fullname;
        if (!input) return false;
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
        if (!input || input.value === "") {
            showError(null, error, "Please select a gender.");
            return false;
        }
        clearError(null, error);
        return true;
    }

    if (fields.fullname.input) {
        fields.fullname.input.addEventListener("blur", validateFullname);
        fields.fullname.input.addEventListener("input", () => {
            if (!fields.fullname.error.classList.contains("hidden")) validateFullname();
        });
    }

    window.openAddMemberModal = function () {
        const modal = document.getElementById("addMemberModal");
        if (modal) { modal.classList.remove("hidden"); modal.classList.add("flex"); }
    };

    window.closeAddMemberModal = function () {
        const modal = document.getElementById("addMemberModal");
        if (modal) { modal.classList.add("hidden"); modal.classList.remove("flex"); }
        resetAddMemberModal();
    };

    function resetAddMemberModal() {
        if (fields.fullname.input) fields.fullname.input.value = "";
        if (fields.gender.input)   fields.gender.input.value   = "";

        Object.values(fields).forEach(({ input, error }) => clearError(input, error));

        const maleLabel   = document.getElementById("gender-male-label");
        const femaleLabel = document.getElementById("gender-female-label");
        if (maleLabel)   { maleLabel.classList.remove("border-[#93C5FD]", "bg-blue-50");   maleLabel.classList.add("border-[#DEE1E6]"); }
        if (femaleLabel) { femaleLabel.classList.remove("border-[#93C5FD]", "bg-pink-50"); femaleLabel.classList.add("border-[#DEE1E6]"); }

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

    window.selectGender = function (val) {
        const genderInput = document.getElementById("gender");
        if (genderInput) genderInput.value = val;

        const maleLabel   = document.getElementById("gender-male-label");
        const femaleLabel = document.getElementById("gender-female-label");

        if (maleLabel && femaleLabel) {
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
        }
        clearError(null, fields.gender.error);
    };

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

    window.handleAddMember = function () {
        const results = [validateFullname(), validateGender()];
        if (!results.every(Boolean)) {
            const firstInvalid = Object.values(fields)
                .map(f => f.input)
                .find(input => input && input.classList.contains("border-red-400"));
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: "smooth", block: "center" });
                firstInvalid.focus();
            }
            return;
        }
        document.getElementById("formAddMember").submit();
    };


    // ════════════════════════════════════════════════════════════════════════
    // CREATE SESSION MODAL
    // ════════════════════════════════════════════════════════════════════════

    let startPicker = null;
    let endPicker   = null;

    function getMinDate() {
        const d = new Date();
        d.setMinutes(d.getMinutes() - 60);
        return d;
    }

    window.openCreateSessionModal = function () {
        const modal = document.getElementById("createSessionModal");
        if (modal) { modal.classList.remove("hidden"); modal.classList.add("flex"); }

        const minDate = getMinDate();

        if (startPicker) startPicker.destroy();
        if (endPicker)   endPicker.destroy();

        startPicker = flatpickr("#start_time", {
            enableTime:  true,
            dateFormat:  "Y-m-d H:i",
            minDate:     minDate,
            time_24hr:   true,
            minuteIncrement: 5,
            onChange: function (selectedDates) {
                if (selectedDates.length > 0) {
                    endPicker.set("minDate", selectedDates[0]);
                    if (document.getElementById("end_time").value) validateEndTime();
                    markSuccess(document.getElementById("start_time"), document.getElementById("error-start_time"));
                }
            },
        });

        endPicker = flatpickr("#end_time", {
            enableTime:  true,
            dateFormat:  "Y-m-d H:i",
            minDate:     minDate,
            time_24hr:   true,
            minuteIncrement: 5,
            onChange: function () {
                if (document.getElementById("end_time").value) validateEndTime();
            },
        });
    };

    window.closeCreateSessionModal = function () {
        const modal = document.getElementById("createSessionModal");
        if (modal) { modal.classList.add("hidden"); modal.classList.remove("flex"); }
        resetCreateSessionModal();
    };

    function resetCreateSessionModal() {
        const sessionName = document.getElementById("session_name");
        const sessionDesc = document.getElementById("session_description");
        const timezone    = document.getElementById("timezone");

        if (sessionName) sessionName.value = "";
        if (sessionDesc) sessionDesc.value = "";
        if (timezone)    timezone.value = ""; 

        if (startPicker) startPicker.clear();
        if (endPicker)   endPicker.clear();

        ["session_name", "start_time", "end_time", "timezone"].forEach(id => {
            const err = document.getElementById("error-" + id);
            const inp = document.getElementById(id);
            if (err) clearError(inp, err);
        });
    }


    // ── Validator session ────────────────────────────────────────────────────

    function validateSessionName() {
        const input = document.getElementById("session_name");
        const error = document.getElementById("error-session_name");
        if (!input) return false;
        if (input.value.trim() === "") {
            showError(input, error, "Session name is required.");
            return false;
        }
        markSuccess(input, error);
        return true;
    }

    function validateStartTime() {
        const input   = document.getElementById("start_time");
        const error   = document.getElementById("error-start_time");
        if (!input) return false;
        const val     = input.value;

        if (!val) {
            showError(input, error, "Start time is required.");
            return false;
        }

        const selected = new Date(val.replace(" ", "T"));
        const minTime  = getMinDate();

        if (selected < minTime) {
            showError(input, error, "Start time is too far in the past (max 1 hour back).");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validateEndTime() {
        const input    = document.getElementById("end_time");
        const error    = document.getElementById("error-end_time");
        if (!input) return false;
        const startVal = document.getElementById("start_time").value;
        const val      = input.value;

        if (!val) {
            showError(input, error, "End time is required.");
            return false;
        }

        if (startVal && new Date(val.replace(" ", "T")) <= new Date(startVal.replace(" ", "T"))) {
            showError(input, error, "End time must be after start time.");
            return false;
        }

        markSuccess(input, error);
        return true;
    }

    function validateTimezone() {
        const input = document.getElementById("timezone");
        const error = document.getElementById("error-timezone");
        if (!input) return false;

        if (input.value === "" || input.selectedIndex === 0) {
            showError(input, error, "Please select a timezone.");
            return false;
        }
        
        markSuccess(input, error);
        return true;
    }


    // ── Listener validasi live session ───────────────────────────────────────

    document.getElementById("session_name")?.addEventListener("blur", validateSessionName);
    document.getElementById("session_name")?.addEventListener("input", () => {
        const err = document.getElementById("error-session_name");
        if (err && !err.classList.contains("hidden")) validateSessionName();
    });

    document.getElementById("timezone")?.addEventListener("change", () => {
        const err = document.getElementById("error-timezone");
        if (err && !err.classList.contains("hidden")) validateTimezone();
    });


    // ── Handle Submit Create Session ─────────────────────────────────────────

    window.handleCreateSession = function () {
        // Eksekusi paksa seluruh fungsi secara independen (Tanpa short-circuit)
        const isNameValid     = validateSessionName();
        const isStartValid    = validateStartTime();
        const isEndValid      = validateEndTime();
        const isTimezoneValid = validateTimezone();

        if (!isNameValid || !isStartValid || !isEndValid || !isTimezoneValid) {
            
            const invalidId = ["session_name", "start_time", "end_time", "timezone"].find(id => {
                const el = document.getElementById(id);
                return el && el.classList.contains("border-red-400");
            });

            if (invalidId) {
                const targetElement = document.getElementById(invalidId);
                targetElement.scrollIntoView({ behavior: "smooth", block: "center" });
                targetElement.focus();
            }
            return; 
        }

        document.getElementById("formCreateSession").submit();
    };

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") {
            window.closeAddMemberModal();
            window.closeCreateSessionModal();
        }
    });

});