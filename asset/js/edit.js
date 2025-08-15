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

  // Determine elementary or high school with better logic
  let level = "elem"; // default to elementary

  console.log("Entry data:", entryData);
  console.log("District from data:", entryData.district);
  console.log("School from data:", entryData.school);

  // Method 1: Check if level is stored directly in entryData
  if (entryData.level) {
    level = entryData.level;
    console.log("Using stored level:", level);
  }
  // Method 2: Determine from school name patterns (most reliable)
  else if (entryData.school) {
    const schoolName = entryData.school.toLowerCase();

    // Check for high school indicators
    if (
      schoolName.includes("high school") ||
      schoolName.includes("hs") ||
      schoolName.includes("nhs") ||
      schoolName.includes("senior") ||
      schoolName.includes("secondary") ||
      schoolName.includes("national high") ||
      schoolName.includes("integrated school")
    ) {
      level = "hs";
      console.log("Determined HIGH SCHOOL from school name:", entryData.school);
    } else if (
      schoolName.includes("es") ||
      schoolName.includes("elementary") ||
      schoolName.includes("primary")
    ) {
      level = "elem";
      console.log("Determined ELEMENTARY from school name:", entryData.school);
    } else {
      // Method 3: Check district arrays as fallback
      let foundInElem = false;
      let foundInHS = false;

      // Check elementary districts
      if (typeof districtElem !== "undefined" && Array.isArray(districtElem)) {
        foundInElem = districtElem.some(
          (d) => d.district === entryData.district
        );
      }

      // Check high school districts
      if (typeof districtHS !== "undefined" && Array.isArray(districtHS)) {
        foundInHS = districtHS.some((d) => d.district === entryData.district);
      }

      console.log("District search results:");
      console.log("- Found in elementary:", foundInElem);
      console.log("- Found in high school:", foundInHS);

      if (foundInHS && !foundInElem) {
        level = "hs";
        console.log("Determined from district arrays: HIGH SCHOOL");
      } else if (foundInElem && !foundInHS) {
        level = "elem";
        console.log("Determined from district arrays: ELEMENTARY");
      } else {
        // Final fallback - guess from district name patterns
        const districtName = (entryData.district || "").toLowerCase();
        if (
          districtName.includes("high") ||
          districtName.includes("secondary")
        ) {
          level = "hs";
          console.log("Guessed HIGH SCHOOL from district name");
        } else {
          // Keep default elementary
          console.log("Could not determine level, defaulting to ELEMENTARY");
        }
      }
    }
  }
  // Method 3: Check district arrays if no school name
  else if (entryData.district) {
    let foundInElem = false;
    let foundInHS = false;

    if (typeof districtElem !== "undefined" && Array.isArray(districtElem)) {
      foundInElem = districtElem.some((d) => d.district === entryData.district);
    }

    if (typeof districtHS !== "undefined" && Array.isArray(districtHS)) {
      foundInHS = districtHS.some((d) => d.district === entryData.district);
    }

    if (foundInHS && !foundInElem) {
      level = "hs";
    } else if (foundInElem && !foundInHS) {
      level = "elem";
    }
  }

  console.log("Final determined level:", level);

  // Set the appropriate radio button
  const levelRadio = document.querySelector(
    `input[name="elem-or-hs"][value="${level}"]`
  );

  if (levelRadio) {
    console.log("Setting radio button to:", levelRadio.value);
    levelRadio.checked = true;

    // Trigger change event to ensure any listeners are called
    levelRadio.dispatchEvent(new Event("change", { bubbles: true }));

    // Call the district filter function to populate the correct dropdown
    if (typeof districtFilter === "function") {
      districtFilter();
    }

    // Also call schoolOrOffice function if it exists (based on your HTML)
    if (typeof schoolOrOffice === "function") {
      schoolOrOffice();
    }
  } else {
    console.error("Could not find radio button for level:", level);
    const allRadios = document.querySelectorAll('input[name="elem-or-hs"]');
    console.log(
      "Available radio buttons:",
      Array.from(allRadios).map((r) => ({ value: r.value }))
    );
  }

  // Wait for dropdowns to populate before setting values
  const districtSelect = document.getElementById("district");
  const schoolSelect = document.getElementById("school");

  const waitForOptions = (selectEl, callback, maxAttempts = 50) => {
    let attempts = 0;
    const check = setInterval(() => {
      attempts++;
      if (
        (selectEl && selectEl.options.length > 1) ||
        attempts >= maxAttempts
      ) {
        clearInterval(check);
        if (attempts >= maxAttempts) {
          console.warn(
            "Timeout waiting for options in select element:",
            selectEl
          );
        }
        callback();
      }
    }, 100);
  };

  // Set district and school values
  waitForOptions(districtSelect, () => {
    if (entryData.district) {
      districtSelect.value = entryData.district;

      // Trigger school filter after setting district
      if (typeof schoolFilter === "function") {
        schoolFilter();
      }

      // Wait for school options to populate, then set school value
      waitForOptions(schoolSelect, () => {
        if (entryData.school) {
          schoolSelect.value = entryData.school;

          // Verify the school was set correctly
          if (schoolSelect.value !== entryData.school) {
            console.warn(
              "Could not set school value:",
              entryData.school,
              "Available options:",
              Array.from(schoolSelect.options).map((o) => o.value)
            );
          }
        }
      });
    }
  });

  // Set purpose and wait for dynamic fields
  if (entryData.purposeOfClearance) {
    const purposeRadio = document.querySelector(
      `input[name="purpose"][value="${entryData.purposeOfClearance}"]`
    );
    if (purposeRadio) {
      purposeRadio.checked = true;

      // Call purpose choice function if it exists
      if (typeof purposeChoice === "function") {
        purposeChoice();
      }

      // Wait for DOM render then populate dynamic fields
      setTimeout(() => {
        const fieldMappings = {
          dateOfAction: "dateOfAction",
          additionalNote: "additionalNote",
          startDate: "startDate",
          endDate: "endDate",
          dateSigned: "schoolDistrictSigned",
          divisionSigned: "divisionSigned",
        };

        // Populate each field if it exists
        Object.entries(fieldMappings).forEach(([elementId, dataKey]) => {
          const element = document.getElementById(elementId);
          if (element && entryData[dataKey]) {
            element.value = entryData[dataKey];
          }
        });
      }, 300);
    }
  }

  // Add debugging information (remove in production)
  console.log("Entry data:", entryData);
  console.log("Determined level:", level);
  console.log("District:", entryData.district);
  console.log("School:", entryData.school);
});
