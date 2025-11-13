const menuBtn = document.querySelector(".btn-menu")
const nav = document.querySelector(".nav")

if (menuBtn && nav) {
  menuBtn.addEventListener("click", () => {
    nav.style.display = nav.style.display === "flex" ? "none" : "flex"
  })
}

const dropdownToggle = document.querySelector(".dropdown-toggle")
const dropdownMenu = document.querySelector(".dropdown-menu")

console.log("[v0] Dropdown toggle found:", dropdownToggle)
console.log("[v0] Dropdown menu found:", dropdownMenu)

if (dropdownToggle && dropdownMenu) {
  dropdownToggle.addEventListener("click", (e) => {
    e.preventDefault()
    console.log("[v0] Dropdown toggle clicked")
    dropdownMenu.classList.toggle("show")
    console.log("[v0] Dropdown menu classes:", dropdownMenu.classList)
  })

  // Close dropdown when clicking outside
  document.addEventListener("click", (e) => {
    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
      dropdownMenu.classList.remove("show")
    }
  })
}
