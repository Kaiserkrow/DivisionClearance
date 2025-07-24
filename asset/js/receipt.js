function generateReceipt() {
  const modalBody = document.getElementById("modal-body");
  const startDate = document.getElementById("startDate")?.value || "";
  const endDate = document.getElementById("endDate")?.value || "";
  const fullName = document.getElementById("fullName").value;
  const position = document.getElementById("position").value;
  const district = document.getElementById("district").value;
  const school = document.getElementById("school").value;
  const dateSigned = document.getElementById("dateSigned").value;
  const divisionSigned = document.getElementById("divisionSigned").value;
  const selectedPurpose =
    document.querySelector('input[name="purpose"]:checked')?.value || "";
  const dateOfAction = document.getElementById("dateOfAction")?.value || "";
  const additionalNote = document.getElementById("additionalNote")?.value || "";

  let purposeLabel = "";

  switch (selectedPurpose) {
    case "travel":
      purposeLabel = "Travel Date";
      break;
    case "retirement":
      purposeLabel = "Retirement Date";
      break;
    case "resigned":
      purposeLabel = "Resignation Date";
      break;
    case "separated":
      purposeLabel = "Separation Date";
      break;
    case "transferred Out":
      purposeLabel = "Transfer Date";
      break;
    default:
      purposeLabel = "Date of Action";
  }

  modalBody.innerHTML = `
    <div class="receipt-container">

      <div class="receipt-item">
        <label>Full Name:</label>
        <p>${fullName}</p>
      </div>

      <div class="receipt-item">
        <label>Position:</label>
        <p>${position}</p>
      </div>

      <div class="receipt-item">
        <label>District:</label>
        <p>${district}</p>
      </div>

      <div class="receipt-item">
        <label>School:</label>
        <p>${school}</p>
      </div>

      <div class="receipt-item">
        <label>Purpose of Clearance:</label>
        <p>${capitalize(selectedPurpose)}</p>
      </div>

      <div class="receipt-item">
        <label>Date of Action:</label>
        <p>${dateOfAction}</p>
      </div>
      <div class="receipt-item">
        <label>Start Date:</label>
        <p>${startDate}</p>
      </div>
      <div class="receipt-item">
        <label>End Date:</label>
        <p>${endDate}</p>
      </div>
      <div class="receipt-item">
        <label>Additional Note:</label>
        <p>${additionalNote}</p>
      </div>

      <div class="receipt-item">
        <label>School/Division - Date Signed:</label>
        <p>${dateSigned}</p>
      </div>

      <div class="receipt-item">
        <label>Division Clearance - Date Signed:</label>
        <p>${divisionSigned}</p>
      </div>
    </div>
  `;
}

function capitalize(s) {
  return s.charAt(0).toUpperCase() + s.slice(1);
}
