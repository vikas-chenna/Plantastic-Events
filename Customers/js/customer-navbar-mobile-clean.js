document.addEventListener('DOMContentLoaded', function () {
  if (window.innerWidth > 991) return;

  document.querySelectorAll('.main-header.header-style2').forEach(function (header) {
    const button = header.querySelector('.navbar-toggle');
    const menu = header.querySelector('.navbar-collapse');
    if (!button || !menu) return;

    // Neutralize Bootstrap/template collapse ownership.
    button.removeAttribute('data-toggle');
    button.removeAttribute('data-target');
    button.removeAttribute('data-bs-toggle');
    button.removeAttribute('data-bs-target');

    // Replace button to discard old click listeners attached directly to it.
    const cleanButton = button.cloneNode(true);
    button.parentNode.replaceChild(cleanButton, button);

    cleanButton.setAttribute('aria-expanded', 'false');

    cleanButton.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopImmediatePropagation();
      const isOpen = menu.classList.toggle('pe-open');
      cleanButton.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
    }, true);
  });
});
