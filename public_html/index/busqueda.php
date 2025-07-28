<div class="buscar">
    <form action="./busqueda_codigo/porcodigo.php" method="GET" id="form-submit">
        <input type="text" name="codigo" id="campo_busqueda" placeholder="Buscar" required>
        <div class="btn">
            <img src="./img/svg/buscar.svg" id="boton_busqueda" class="lupa"> 
        </div>    
    </form>   
</div>

    <script>   
        boton_busqueda = document.getElementById('boton_busqueda');

        boton_busqueda.addEventListener('click', function(){
            codigo = document.getElementById('campo_busqueda');

            if(codigo.value != null && codigo.value != ""){
                document.getElementById('form-submit').submit();
            }
            else {

            }
        })
    </script>

<div class="buscar_apli">
    <form action="./busqueda_aplicacion/aplicaciones.php" method="POST" id="form-submit-aplicacion">
        <select name="aplicacion" id="aplicacion-select" >
            <option value="0" disabled selected>Aplicacion:</option>
            <option value="1">Liviano / SUV</option>
            <option value="2">Comercial</option>
            <option value="3">Fuera de carretera</option>
            <option value="4">Agrícola</option>
        </select>
    </form>
</div>

<script>   
    select = document.getElementById('aplicacion-select');

    select.addEventListener('change', function(){
        document.getElementById('form-submit-aplicacion').submit();
    })
</script>