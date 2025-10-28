document.getElementById('und_empaque').addEventListener('keypress', function(evt) {
    /*-----------SI SUCEDE EL EVENTO----------------*/
    /*-------SE GUARDA EL EVENTO Y EL CAMPO--------*/
    if (filterInteger(evt, evt.target) === false) {
       /*Se evita que el evento suceda */
       evt.preventDefault();
    }
});