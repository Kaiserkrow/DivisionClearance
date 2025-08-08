document.addEventListener("DOMContentLoaded", () => {
  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", function () {
      const id = this.getAttribute("data-id");
      if (confirm("Are you sure you want to delete this entry?")) {
        fetch("delete_entry.php", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: "id=" + encodeURIComponent(id),
        })
          .then((response) => response.json())
          .then((data) => {
            if (data.success) {
              alert("Entry deleted successfully.");
              location.reload();
            } else {
              alert("Failed to delete entry.");
            }
          });
      }
    });
  });
});
