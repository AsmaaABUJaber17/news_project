
// Small UX helpers (kept minimal)
document.addEventListener('DOMContentLoaded', () => {
  // Make any [data-toggle="nav"] work for mobile menus if present
  const toggle = document.querySelector('[data-toggle="nav"]');
  const nav = document.querySelector('.nav');
  if (toggle && nav){
    toggle.addEventListener('click', () => {
      nav.classList.toggle('open');
    });
  }
});
