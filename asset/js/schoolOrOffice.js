function schoolOrOffice() {
  const label = document.getElementById("schoolOrOffice");
  let e = document.getElementById("district");

  let text = e.options[e.selectedIndex].text;

  if (text == "Division Office") {
    label.textContent = "Office";
  } else {
    label.textContent = "School";
  }
}
setTimeout(schoolOrOffice, 200);
