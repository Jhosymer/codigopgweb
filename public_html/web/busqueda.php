<div class="buscar">
    <form action="./busqueda_codigo/porcodigo.php" method="GET" id="form-submit-2">
        <input type="text" name="codigo" id="campo_busqueda-2" placeholder="Buscar" required>
        <div class="btn">
            <img src="./img/svg/buscar.svg" id="boton_busqueda-2" class="lupa" alt="buscar"> 
        </div>    
    </form>   
</div>

    <script>   
        boton_busqueda = document.getElementById('boton_busqueda-2');

        boton_busqueda.addEventListener('click', function(){
            codigo = document.getElementById('campo_busqueda-2');


            if( codigo.value != null && codigo.value != "" ){
                document.getElementById('form-submit-2').submit();
            }
            else {

            }
        })
    </script>

<div class="buscar_apli">
    <form action="./busqueda_aplicacion/aplicaciones.php" method="POST" id="form-submit-aplicacion-2">
        <select name="aplicacion" id="aplicacion-select-2" >
            <option value="0" disabled selected>Aplicación:</option>
            <option value="1">Liviano / SUV</option>
            <option value="2">Comercial</option>
            <option value="3">Fuera de carretera</option>
            <option value="4">Agrícola</option>
        </select>
    </form>
</div>

<script>   
    select = document.getElementById('aplicacion-select-2');

    select.addEventListener('change', function(){
        document.getElementById('form-submit-aplicacion-2').submit();
    })
</script>