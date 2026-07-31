(function () {
  document.addEventListener('DOMContentLoaded', function () {
    var nav = document.querySelector('.main-menu.style2 .navigation');
    if (!nav) return;

    nav.querySelectorAll('li.dropdown > a').forEach(function (link) {
      link.addEventListener('click', function (e) {
        if (window.innerWidth > 991) return;
        var parent = link.parentElement;
        var submenu = parent.querySelector(':scope > ul');
        if (!submenu) return;

        e.preventDefault();
        var open = submenu.style.display === 'block';

        nav.querySelectorAll('li.dropdown > ul').forEach(function (menu) {
          if (menu !== submenu) menu.style.display = 'none';
        });

        submenu.style.display = open ? 'none' : 'block';
      });
    });

    window.addEventListener('resize', function () {
      if (window.innerWidth > 991) {
        nav.querySelectorAll('li.dropdown > ul').forEach(function (menu) {
          menu.style.display = '';
        });
      }
    });
  });
})();
