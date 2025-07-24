document.addEventListener("DOMContentLoaded", () => {
  const entryDataScript = document.getElementById("entry-data");
  let entryData = {};

  if (entryDataScript) {
    try {
      entryData = JSON.parse(entryDataScript.textContent);
    } catch (e) {
      console.error("Invalid JSON in entry data:", e);
      return;
    }
  }

  // Prefill basic fields
  document.getElementById("fullName").value = entryData.fullName || "";
  document.getElementById("position").value = entryData.position || "";

  // Determine elementary or high school
  const isElem =
    typeof districtElem !== "undefined" &&
    districtElem.some((d) => d.district === entryData.district);
  const level = isElem ? "elem" : "hs";

  const levelRadio = document.querySelector(
    `input[name="elem-or-hs"][value="${level}"]`
  );
  if (levelRadio) {
    levelRadio.checked = true;
    districtFilter(); // call after setting level
  }

  // Wait for dropdowns to populate before setting values
  const districtSelect = document.getElementById("district");
  const schoolSelect = document.getElementById("school");

  const waitForOptions = (selectEl, callback) => {
    const check = setInterval(() => {
      if (selectEl && selectEl.options.length > 1) {
        clearInterval(check);
        callback();
      }
    }, 100);
  };

  waitForOptions(districtSelect, () => {
    districtSelect.value = entryData.district || "";
    schoolFilter();

    waitForOptions(schoolSelect, () => {
      schoolSelect.value = entryData.school || "";
    });
  });

  // Set purpose and wait for dynamic fields
  if (entryData.purposeOfClearance) {
    const purposeRadio = document.querySelector(
      `input[name="purpose"][value="${entryData.purposeOfClearance}"]`
    );
    if (purposeRadio) {
      purposeRadio.checked = true;
      purposeChoice();

      // Wait for DOM render
      setTimeout(() => {
        const dateOfActionInput = document.getElementById("dateOfAction");
        const noteTextarea = document.getElementById("additionalNote");
        const startDateInput = document.getElementById("startDate");
        const endDateInput = document.getElementById("endDate");
        const dateSigned = document.getElementById("dateSigned");
        const divisionDateSigned = document.getElementById("divisionSigned");

        if (dateOfActionInput) {
          dateOfActionInput.value = entryData.dateOfAction || "";
        }

        if (noteTextarea) {
          noteTextarea.value = entryData.additionalNote || "";
        }

        if (startDateInput) {
          startDateInput.value = entryData.startDate || "";
        }

        if (endDateInput) {
          endDateInput.value = entryData.endDate || "";
        }
        if (dateSigned) {
          dateSigned.value = entryData.schoolDistrictSigned || "";
        }
        if (divisionDateSigned) {
          divisionDateSigned.value = entryData.divisionSigned || "";
        }
      }, 300);
    }
  }
});
