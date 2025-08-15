document.addEventListener("DOMContentLoaded", function () {
  const searchInput = document.getElementById("searchInput");
  const purposeFilter = document.getElementById("purposeFilter");
  const tableBody = document.getElementById("entriesTableBody");

  function fetchEntries() {
    const search = searchInput.value.trim();
    const purpose = purposeFilter.value;

    fetch(
      `fetch_entries.php?search=${encodeURIComponent(
        search
      )}&purpose=${encodeURIComponent(purpose)}`
    )
      .then((res) => res.text())
      .then((html) => {
        tableBody.innerHTML = html;
        totalNumberEntries();
      })
      .catch((err) => console.error("Error fetching entries:", err));
  }

  searchInput.addEventListener("input", fetchEntries);
  purposeFilter.addEventListener("change", fetchEntries);

  // Initial load
  fetchEntries();
});

function totalNumberEntries() {
  const entryNum = document.getElementById("totalNumber");
  const entryCheck = document.getElementById("no-entries-available");

  if (entryCheck === null) {
    const allRows = document.querySelectorAll("tr");
    let visibleCount = 0;

    for (let i = 0; i < allRows.length; i++) {
      if (allRows[i].offsetParent !== null) {
        // only visible rows
        visibleCount++;
      }
    }

    entryNum.textContent = visibleCount - 1; // subtract header row
  } else {
    entryNum.textContent = 0;
  }
}

setTimeout(totalNumberEntries, 300);
