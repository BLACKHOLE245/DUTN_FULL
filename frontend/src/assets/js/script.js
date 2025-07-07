
window.addEventListener('DOMContentLoaded', function () {
  const overlay = document.querySelector("[data-overlay]");
  const navOpenBtn = document.querySelector("[data-nav-open-btn]");
  const navbar = document.querySelector("[data-navbar]");
  const navCloseBtn = document.querySelector("[data-nav-close-btn]");

  if (overlay && navOpenBtn && navbar && navCloseBtn) {
    [overlay, navOpenBtn, navCloseBtn].forEach(elem => {
      elem.addEventListener("click", () => {
        navbar.classList.toggle("active");
        overlay.classList.toggle("active");
      });
    });
  }

  const header = document.querySelector("[data-header]");
  const goTopBtn = document.querySelector("[data-go-top]");

  if (header && goTopBtn) {
    window.addEventListener("scroll", () => {
      if (window.scrollY >= 80) {
        header.classList.add("active");
        goTopBtn.classList.add("active");
      } else {
        header.classList.remove("active");
        goTopBtn.classList.remove("active");
      }
    });
  }
});
