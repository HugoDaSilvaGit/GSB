  document.querySelector('#the_button').addEventListener('click',function(e){
    e.preventDefault();
    let theme = localStorage.getItem('puppertino_theme');
    if (theme == 'p-dark-mode') {
      localStorage.setItem('puppertino_theme','default');
    }else{
      localStorage.setItem('puppertino_theme','p-dark-mode');
    }
    retrieve_theme();
  });

  window.addEventListener("storage",function(){
    retrieve_theme();
  },false);

  retrieve_theme();

  function retrieve_theme(){
    let theme = localStorage.getItem('puppertino_theme');
    if(theme != null){
      document.body.classList.remove('default', 'p-dark-mode'); document.body.classList.add(theme);
    }
  }