(function(){
 function boot(){
  if(window.innerWidth>991)return;
  document.querySelectorAll('.main-header.header-style2').forEach(function(header){
   var oldBtn=header.querySelector('.navbar-toggle'), menu=header.querySelector('.navbar-collapse');
   if(!oldBtn||!menu)return;

   oldBtn.removeAttribute('data-toggle');oldBtn.removeAttribute('data-target');
   oldBtn.removeAttribute('data-bs-toggle');oldBtn.removeAttribute('data-bs-target');

   var btn=oldBtn.cloneNode(true);oldBtn.parentNode.replaceChild(btn,oldBtn);
   btn.addEventListener('click',function(e){
    e.preventDefault();e.stopPropagation();e.stopImmediatePropagation();
    menu.classList.toggle('pe-open');
   },true);

   header.querySelectorAll('.navigation>li.dropdown').forEach(function(li){
    var oldToggle=li.querySelector(':scope > .dropdown-btn');
    if(oldToggle){
      var clean=oldToggle.cloneNode(true);
      oldToggle.parentNode.replaceChild(clean,oldToggle);
      clean.addEventListener('click',function(e){
       e.preventDefault();e.stopPropagation();e.stopImmediatePropagation();
       var opening=!li.classList.contains('pe-submenu-open');
       li.parentElement.querySelectorAll(':scope > li.dropdown.pe-submenu-open').forEach(function(other){
        if(other!==li)other.classList.remove('pe-submenu-open');
       });
       li.classList.toggle('pe-submenu-open',opening);
      },true);
    } else {
      var link=li.querySelector(':scope > a');
      if(link) link.addEventListener('click',function(e){
       e.preventDefault();e.stopPropagation();
       li.classList.toggle('pe-submenu-open');
      },true);
    }
   });
  });
 }
 document.addEventListener('DOMContentLoaded',boot);
})();


/* =========================================================
   PLANTASTIC — GLOBAL STICKY NAVBAR
   ========================================================= */

(function ($) {
    "use strict";

    function handleStickyNavbar() {

        if ($(window).scrollTop() > 10) {
            $(".sticky-wrapper").addClass("is-sticky");
        } else {
            $(".sticky-wrapper").removeClass("is-sticky");
        }

    }

    $(window).on("scroll", handleStickyNavbar);

    handleStickyNavbar();

})(jQuery);