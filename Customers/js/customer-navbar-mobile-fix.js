(function(){
  function init(){
    if(window.innerWidth > 991) return;
    document.querySelectorAll('.main-header.header-style2').forEach(function(header){
      var btn = header.querySelector('.navbar-toggle');
      var collapse = header.querySelector('.navbar-collapse');
      if(!btn || !collapse || btn.dataset.peReady) return;

      btn.dataset.peReady = '1';
      btn.removeAttribute('data-toggle');
      btn.removeAttribute('data-target');
      btn.setAttribute('aria-expanded','false');

      btn.addEventListener('click', function(e){
        e.preventDefault();
        e.stopPropagation();
        var open = collapse.classList.toggle('pe-open');
        btn.setAttribute('aria-expanded', open ? 'true' : 'false');
      });

      header.querySelectorAll('.navigation > li.dropdown > a').forEach(function(a){
        a.addEventListener('click', function(e){
          if(window.innerWidth > 991) return;
          e.preventDefault();
          e.stopPropagation();
          a.parentElement.classList.toggle('pe-submenu-open');
        });
      });
    });
  }

  document.addEventListener('DOMContentLoaded', init);
  window.addEventListener('resize', function(){
    if(window.innerWidth > 991){
      document.querySelectorAll('.navbar-collapse.pe-open').forEach(function(el){el.classList.remove('pe-open')});
      document.querySelectorAll('.pe-submenu-open').forEach(function(el){el.classList.remove('pe-submenu-open')});
    }
  });
})();
