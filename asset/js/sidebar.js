let isOpen = 1;
let button = document.getElementById("open-close-btn");
let closebtn1 = document.getElementById("close-btn1");
let closebtn2 = document.getElementById("close-btn2");

function openNav() {
  let width = window.innerWidth;
  if (isOpen == 0) {
    button.classList.remove("x-button");
    button.textContent = "☰";
    button.setAttribute;
    document.getElementById("mySidebar").style.width = "0";
    document.getElementById("main").style.marginLeft = "0";
    isOpen = 1;
  } else if (isOpen == 1) {
    button.textContent = "✕";
    if (width < 600) {
      closebtn1.classList.remove("hidden");
      closebtn2.classList.remove("hidden");
      document.getElementById("mySidebar").style.width = "100vw";
    } else {
      closebtn1.classList.add("hidden");
      closebtn2.classList.add("hidden");
      document.getElementById("mySidebar").style.width = "300px";
    }
    document.getElementById("main").style.marginLeft = "300px";
    isOpen = 0;
  }
}
