function purposeChoice() {
  const purpose = document.querySelector(
    "input[name='purpose']:checked"
  )?.value;
  const purposeDiv = document.getElementById("purposeChoice");

  if (!purpose || !purposeDiv) return;

  purposeDiv.innerHTML = ""; // Clear previous

  let label = "";
  let showTextarea = false;
  let showDateRange = false;
  let showActionDate = true;

  switch (purpose) {
    case "travel":
      showTextarea = true;
      showDateRange = true;
      showActionDate = false;
      break;
    case "sick Leave":
      showDateRange = true;
      showActionDate = false;
      break;
    case "retirement":
      label = "Retirement Date";
      break;
    case "resigned":
    case "separated":
      label = "Resignation/Separation Date";
      break;
    case "transferred Out":
      label = "Transfer Date";
      showTextarea = true;
      break;
    default:
      return;
  }

  let html = "";

  // Only show dateOfAction for applicable purposes
  if (showActionDate) {
    html += `
      <div class="my-4">
        <label for="dateOfAction">${
          label || "Date of Action"
        }:&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="date" id="dateOfAction" name="dateOfAction" required />
      </div>
    `;
  }

  // Show start-end date in one line
  if (showDateRange) {
    html += `
      <div class="my-5">
        <p class="text-center">${
          purpose === "travel" ? "Travel" : "Sick Leave"
        } Date: </p>
        <div class="d-flex justify-content-center align-items-center">
          <label for="startDate">Start Date:&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="date" id="startDate" name="startDate" required />
          &nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;
          <label for="endDate">End Date:&nbsp;&nbsp;&nbsp;&nbsp;</label>
          <input type="date" id="endDate" name="endDate" required />
        </div>
      </div>
    `;
  }

  // Show remarks textarea if applicable
  if (showTextarea) {
    html += `
      <div class="my-5 d-flex flex-column justify-content-center align-items-center">
        <p class="text-center">Remarks:</p>
        <textarea id="additionalNote" class="p-2" name="additionalNote" rows="4" cols="50" required></textarea>
      </div>
    `;
  }

  purposeDiv.innerHTML = html;
}
