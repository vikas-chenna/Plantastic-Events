(function(){
 const html=document.documentElement, body=document.body;
 const saved=localStorage.getItem('pe-theme');
 const preferred=saved || (window.matchMedia('(prefers-color-scheme: dark)').matches?'dark':'light');
 html.setAttribute('data-theme',preferred);

 function icon(btn){
   if(!btn)return;
   btn.innerHTML=html.getAttribute('data-theme')==='dark'?'☀':'☾';
   btn.setAttribute('aria-label',html.getAttribute('data-theme')==='dark'?'Switch to light theme':'Switch to dark theme');
 }
 const header=document.querySelector('.main-header.header-style2')||document.querySelector('.main-header');
 if(!header)return;
 const host=header.querySelector('.inner-container')||header;
 const actions=document.createElement('div'); actions.className='pe-nav-actions';
 const theme=document.createElement('button'); theme.type='button';theme.className='pe-theme-toggle';icon(theme);
 const menu=document.createElement('button');menu.type='button';menu.className='pe-mobile-toggle';menu.innerHTML='☰';menu.setAttribute('aria-label','Open menu');
 actions.append(theme,menu);host.appendChild(actions);
 const backdrop=document.createElement('div');backdrop.className='pe-mobile-backdrop';document.body.appendChild(backdrop);

 theme.addEventListener('click',()=>{const n=html.getAttribute('data-theme')==='dark'?'light':'dark';html.setAttribute('data-theme',n);localStorage.setItem('pe-theme',n);icon(theme)});
 function close(){body.classList.remove('pe-menu-open');menu.innerHTML='☰';menu.setAttribute('aria-label','Open menu')}
 menu.addEventListener('click',()=>{body.classList.toggle('pe-menu-open');const o=body.classList.contains('pe-menu-open');menu.innerHTML=o?'×':'☰';menu.setAttribute('aria-label',o?'Close menu':'Open menu')});
 backdrop.addEventListener('click',close);
 document.addEventListener('keydown',e=>{if(e.key==='Escape')close()});
 window.addEventListener('resize',()=>{if(innerWidth>991)close()});

 document.querySelectorAll('.main-menu.style2 .navigation>li.dropdown>a').forEach(a=>{
   a.addEventListener('click',e=>{
     if(innerWidth<=991){e.preventDefault();a.parentElement.classList.toggle('pe-submenu-open')}
   });
 });
 const join=document.getElementById('joinButton');if(join)join.classList.add('pe-auth-link');
})();
