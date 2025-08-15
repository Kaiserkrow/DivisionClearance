let state = true;
function checkUncheckAll() {
  const allCheckBox = document.getElementsByClassName("entry-checkbox");
  for (let i = 0; i < allCheckBox.length; i++) {
    // Only check/uncheck if visible
    if (allCheckBox[i].offsetParent !== null) {
      allCheckBox[i].checked = state;
    }
  }
  state = !state; // Flip the toggle state
}
