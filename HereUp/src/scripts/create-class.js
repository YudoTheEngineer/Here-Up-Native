const modal = document.getElementById("createClassModal");

function openModal() {
    modal.classList.remove("hidden");
    modal.classList.add("flex");
}

function closeModal() {
    modal.classList.add("hidden");
    modal.classList.remove("flex");
}

// klik di luar box = close
modal.addEventListener("click", function(e) {
    if (e.target === modal) {
        closeModal();
    }
});

lucide.createIcons();