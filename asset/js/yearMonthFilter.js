document.getElementById("monthFilter").addEventListener("change", filterTable);
document.getElementById("yearFilter").addEventListener("change", filterTable);

function filterTable() {
  const month = document.getElementById("monthFilter").value;
  const year = document.getElementById("yearFilter").value;
  const rows = document.querySelectorAll(".division-signed-date");

  for (let i = 0; i < rows.length; i++) {
    const dateText = rows[i].textContent.trim(); // "Aug 20, 2025"
    const [monthAbbrev, , yearText] = dateText.split(" ");
    const cleanYear = yearText.replace(",", "").trim();

    const matchMonth = month === "" || monthAbbrev === month;
    const matchYear = year === "" || cleanYear === year;

    // Go up to the <tr> and hide the entire row
    const tableRow = rows[i].closest("tr");
    tableRow.style.display = matchMonth && matchYear ? "" : "none";
  }
  totalNumberEntries();
}
