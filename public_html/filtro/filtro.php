<link rel="stylesheet" href="./../css/estilo_body_apli.css">
<?php include_once("./../web/arriba_carpeta.php");
 $url = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; 
 ?>



<title>Filtro</title>
<input type="hidden" id="codigo_filtro" value="" >
        <div class="aplicacion_producto">
  <section class="about_boton_redes">
<a class="impr input_com" onclick="boton_descargar()"></a>
<a class="comp_redes input_com"></a>
</section>

            <section class="filtro_selec" id="detalle_producto">
                <div class="datos4" id="filtro_titulo">
                    
                </div>
                <div class="datos3" id="filtro_carrusel">

                </div>
                <div class="datos2" id="filtro_especificaciones">
                    
                </div>
                <div class="datos" id="filtro_aplicacion">

                </div> 
                <div class="datos" id="filtro_equivalencia">
                        
                </div>                            
            </section>
        </div>
        <?php   
            include_once("./../web/abajo_carpeta.php");
        ?>

<script>
    <?php 
        if( isset( $_GET['codigoVehiculo'] ) ){
            ?>
                getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', codigoVehiculo: '<?php echo $_GET['codigoVehiculo'] ?>'});
            <?php
        }
        else if ( isset( $_GET['cod'] ) ) {
            ?>
                getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', cod: '1'});
            <?php
        }
        else if ( isset( $_GET['esp'] ) ) {
            ?>
                getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', esp: '1'});
            <?php
        }
        else if ( isset( $_GET['tip'] ) ) {
            ?>
                getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>', tip: '<?php echo $_GET['tip']; ?>'});
            <?php
        }
        else {
            ?>
                getFiltroCodigo({codigo: '<?php echo $_GET['codigo'] ?>'});
            <?php
        }
    ?>

    function getFiltroCodigo({codigo, esp = null, tip = null, cod = null, codigoVehiculo = null}){
        formData = new FormData();
        if( esp != null ){
            formData.append('buscarEspecificacion', 1);
        }
        else if( cod != null ){
            formData.append('buscarCodigo', 1);
        }
        else if( tip != null ){
            formData.append('buscarTipo', tip);
        }
        else if( codigoVehiculo != null ){
            formData.append('codigoVehiculo', codigoVehiculo);
        }
        formData.append('codigo', codigo);

        fetch("./../ajax_busquedas/filtro_seleccionado.php", {
            method: 'POST',
            body: formData,
        })
        .then( response => response.json() )
        .then(
            data => {
                document.getElementById('codigo_filtro').value = codigo;
                document.getElementById('detalle_producto').style.display = 'flex';
                document.getElementById('filtro_titulo').innerHTML = data.titulo;
                document.getElementById('filtro_carrusel').innerHTML = data.carrusel;
                document.getElementById('filtro_especificaciones').innerHTML = data.especificaciones;
                document.getElementById('filtro_aplicacion').innerHTML = data.aplicacion;
                document.getElementById('filtro_equivalencia').innerHTML = data.equivalencia;
                zoomImagen();
            } 
        )
        .catch(
            error => console.log(error)
        )

    }

    function boton_descargar(){
     codigo_enviar = document.getElementById('codigo_filtro').value;
        var win = window.open( `./filtro_descarga.php?codigo=${codigo_enviar}`, '_blank');
        win.focus();
    }
    
</script>
 <script src="./../js/main_comp_redes.js"></script>