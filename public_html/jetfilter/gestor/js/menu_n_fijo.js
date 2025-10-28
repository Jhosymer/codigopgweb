const nav = document.querySelector ('.menu');
    window.addEventListener('scroll', function(){
        nav.classList.toggle('active',window.scrollY >0)
    })