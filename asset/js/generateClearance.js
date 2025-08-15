document.getElementById("generate-pdf").addEventListener("click", function () {
  let ids = [];
  document.querySelectorAll(".entry-checkbox:checked").forEach((cb) => {
    const style = window.getComputedStyle(cb);
    if (style.display !== "none" && style.visibility !== "hidden") {
      ids.push(cb.value);
    }
  });

  if (ids.length === 0) {
    alert("Please select at least one visible entry.");
    return;
  }

  // Open PDF in new tab
  window.open("generate_clearance.php?ids=" + ids.join(","), "_blank");
});
