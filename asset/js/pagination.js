const tableBody = document.getElementById("tableBody");
const searchInput = document.getElementById("searchInput");
const purposeFilter = document.getElementById("purposeFilter");
const paginationLinks = document.getElementById("paginationLinks");

function loadTable(page = 1) {
  const search = searchInput.value;
  const purpose = purposeFilter.value;

  fetch(
    `fetch_entries.php?page=${page}&search=${encodeURIComponent(
      search
    )}&purpose=${encodeURIComponent(purpose)}`
  )
    .then((res) => res.text())
    .then((data) => {
      const [tableRows, pagination] = data.split("<!-- PAGINATION -->");
      tableBody.innerHTML = tableRows;
      paginationLinks.innerHTML = pagination;
      attachDeleteEvents();
      attachPaginationEvents();
    });
}

function attachDeleteEvents() {
  document.querySelectorAll(".delete-btn").forEach((button) => {
    button.addEventListener("click", () => {
      const id = button.dataset.id;
      if (confirm("Delete this entry?")) {
        fetch(`delete_entry.php?id=${id}`)
          .then((res) => res.text())
          .then((r) => {
            if (r === "success") loadTable();
          });
      }
    });
  });
}

function attachPaginationEvents() {
  document.querySelectorAll(".page-link").forEach((link) => {
    link.addEventListener("click", (e) => {
      e.preventDefault();
      const page = link.dataset.page;
      loadTable(page);
    });
  });
}

searchInput.addEventListener("input", () => loadTable(1));
purposeFilter.addEventListener("change", () => loadTable(1));
document.addEventListener("DOMContentLoaded", () => loadTable(1));
