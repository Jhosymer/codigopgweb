<div class="buscar">
    <form action="<?php echo $loc; ?>catalogo/busqueda_por_codigo/index.php" method="GET" id="form-submit-2">
        <input type="text" name="codigo" id="campo_busqueda-2" placeholder="Buscar" required>
        <button type="submit" class="btn_buscar" id="boton_busqueda-2">
            <img src="<?php echo $loc; ?>img/svg/buscar.svg" class="lupa" alt="Buscar">
        </button>
    </form>
</div>





<div class="buscar_apli">
    <form action="<?php echo $loc; ?>catalogo/busqueda_por_aplicacion" method="GET" id="form-submit-aplicacion-2">
        <select name="aplicacion" id="aplicacion-select-2" required onchange="this.form.submit()">
            <option value="" disabled selected>Aplicación:</option>
            <option value="1">Liviano / SUV</option>
            <option value="2">Comercial</option>
            <option value="3">Fuera de carretera</option>
            <option value="4">Agrícola</option>
        </select>
    </form>
</div>


