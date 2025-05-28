let arrow = document.querySelectorAll(".arrow");
for (var i = 0; i < arrow.length; i++) {
  arrow[i].addEventListener("click", (e) => {
    let arrowParent = e.target.parentElement.parentElement;//selecting main parent of arrow
    arrowParent.classList.toggle("showMenu");
  });
}

// REMOVED: No need to find sidebarBtn or add click listener if sidebar is always open
// let sidebar = document.querySelector(".sidebar");
// let sidebarBtn = document.querySelector(".bx-menu");
// console.log(sidebarBtn);
// sidebarBtn.addEventListener("click", () => {
//   sidebar.classList.toggle("close");
// });

// Optional: If you want to log something when the script runs, but no sidebar toggling
console.log("Sidebar functionality to toggle 'close' class has been disabled.");