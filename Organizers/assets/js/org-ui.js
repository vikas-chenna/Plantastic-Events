(function () {
  const root = document.documentElement;
  const KEY = 'ems_org_theme';

  function applyTheme(mode) {
    if (mode === 'dark') root.classList.add('dark');
    else root.classList.remove('dark');
    localStorage.setItem(KEY, mode);
    document.querySelectorAll('[data-theme-label]').forEach((el) => {
      el.textContent = mode === 'dark' ? 'Light' : 'Dark';
    });
  }

  const saved = localStorage.getItem(KEY);
  const prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
  applyTheme(saved || (prefersDark ? 'dark' : 'light'));

  document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-theme-toggle]');
    if (btn) {
      applyTheme(root.classList.contains('dark') ? 'light' : 'dark');
    }
    const open = e.target.closest('[data-sidebar-open]');
    const close = e.target.closest('[data-sidebar-close]');
    if (open) document.body.classList.add('sidebar-open');
    if (close) document.body.classList.remove('sidebar-open');
    const navLink = e.target.closest('.side-nav a');
    if (navLink && window.innerWidth <= 860) document.body.classList.remove('sidebar-open');

    const tab = e.target.closest('[data-tab]');
    if (tab) {
      const group = tab.getAttribute('data-tab-group') || 'default';
      const name = tab.getAttribute('data-tab');
      document.querySelectorAll(`[data-tab-group="${group}"][data-tab]`).forEach((b) => b.classList.toggle('active', b === tab));
      document.querySelectorAll(`[data-tab-panel-group="${group}"]`).forEach((p) => {
        p.classList.toggle('active', p.getAttribute('data-tab-panel') === name);
      });
    }
  });

  document.addEventListener('keydown', (e) => { if (e.key === 'Escape') document.body.classList.remove('sidebar-open'); });
  window.addEventListener('resize', () => { if (window.innerWidth > 860) document.body.classList.remove('sidebar-open'); });

  // simple client search for tables/cards
  document.querySelectorAll('[data-search-input]').forEach((input) => {
    input.addEventListener('input', () => {
      const q = input.value.trim().toLowerCase();
      const target = input.getAttribute('data-search-input');
      document.querySelectorAll(`[data-search-item="${target}"]`).forEach((el) => {
        const text = el.textContent.toLowerCase();
        el.style.display = !q || text.includes(q) ? '' : 'none';
      });
    });
  });
})();
