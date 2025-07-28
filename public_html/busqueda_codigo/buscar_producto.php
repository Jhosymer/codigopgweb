
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width"/>


  <link rel="stylesheet" href="./../css/estilos.css">

    <script type="text/javascript" src='./../js/zoom.js'></script>
    <script type="text/javascript" src='./../js/jquery.min.js'></script>

  <?php
    include ('../../conexion.php');   
    $paginas = [10, 25, 50, 100]; 
    ?> 

    <div class="aplicacion_producto">
        <section class="infor_aplica2" id="buscar">
            <section class="detalle">
                <div class="about_aplicacion_sel">
                    <h3 class="title_busqueda">Buscar filtros por código</h3> 
                    <p class="if">
                        <em>Introduzca un código Web o de otro fabricante para encontrar su equivalencia Web</em><br>
                        <span class="resaltar">[*]</span> Indicará una coincidencia exacta
                    </p>
                    <div class="li_boton">
                    <?php
    $token = '89656c6690d8ff'; // Reemplaza con tu token de acceso
    $Ciudad = '';
    $estado = '';

    $ip = $_SERVER['REMOTE_ADDR'];
$ip_datos_json = @file_get_contents("https://ipinfo.io/$ip/json?token=$token");
$ip_datos = json_decode($ip_datos_json);

if ($ip_datos && isset($ip_datos->city)) {
    $Ciudad = $ip_datos->city;
}

if ($ip_datos && isset($ip_datos->region)) {
    $estado = $ip_datos->region;
}
?>
                
                        <div>
                            <input type="text" name="keywords" class="vBuscar" id="keywords" autocomplete="off" />
                            <input type="text" name="ciudad" class="vBuscar"  style="display: none; " id="ciudad" autocomplete="off"  value ="<?php echo $Ciudad ?>"/>
                            <input type="text" name="estado" class="vBuscar" style="display: none; " id="estado" autocomplete="off"  value ="<?php echo $estado ?>"/>
                           
                        </div>
                        <div>
                            <a onclick="buscar('1')" id="boton-busqueda" name="search" ><img src="./../img/tipo/bt__buscar.png" alt="" class="bt_busq"></a>
                        </div> 
                    </div>
                </div>
            </section>
        </section>

        <section class="infor_aplica" id="busquedas">
            <div class="tabla_resultados" id="resultados_busqueda"></div>
            <div class="tabla_resultados" id="resultados_busqueda_equivalencias"></div>
        </section>
    </div>

    <script src='./../js/busqueda_filtros/cambio_aplicacion.js'></script>
    <script src='./../js/busqueda_filtros/cambio_marca.js'></script>
    <script src='./../js/busqueda_filtros/aplicacion2.js'></script>
    <script src='./../js/busqueda_filtros/aplicacion3.js'></script>
    <script src='./../js/busqueda_filtros/getData.js'></script>
    <script src='./../js/busqueda_filtros/getRegistro.js'></script>
    <script src='./../js/busqueda_filtros/volver_registros.js'></script>   


      <script>
        paginaActual = 1;

        $(document).ready(function(){
            $('#tabla').css("display","none");
            $('#boton_volver').css("display","none");
            $('#volver').css("display","none");
            $('#detalle').css("display","none");
            $('#detalle_producto').css("display","none");
            $('#vuelta_atras').css("display","none");
            $('#formulario_busqueda_aplicacion').css("display","none");
        });

        function buscar(historial = null){
            input = document.getElementById("keywords").value;
            ciudad = document.getElementById("ciudad").value;
            estado = document.getElementById("estado").value;
         
            if( historial == 1){
                history.replaceState(null, "", `./porcodigo.php?codigo=${input}`);
            }
            if (document.getElementById("keywords").value != "") {
                $.ajax({ 
                    data: {
                    'codigo': input,
                    'ciudad': ciudad,
                    'estado': estado,
                    },
                    url: "./busqueda_codigos.php",
                    type: "POST",
                    dataType: 'json',
                    success: function (response){
                        var stateObj = { foo: "bar" };
                        history.pushState(stateObj, "Buscar Codigo", `./porcodigo.php?codigo=${input}`);
                        document.getElementById("resultados_busqueda").innerHTML = response.especificaciones;
                        document.getElementById("resultados_busqueda_equivalencias").innerHTML = response.equivalencias;
                        document.getElementById("resultados_busqueda").style.display = 'block';
                        document.getElementById("resultados_busqueda_equivalencias").style.display = 'block';
                    },
                    error: function(){
                        alert(error);
                    }
                });
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: '¡Busqueda Realizada!',
                    timer: 1250,
                })
            }
        }

        document.addEventListener('keydown', function(event) {
            input = document.getElementById("keywords").value;
            if (event.keyCode  == 13 && input != "") {
                buscar();
            }
        });

        function volver(){
            $('#buscar').css("display","block");
            $('#busquedas').css("display","flex");
            $('#detalle_producto').css("display","none");
        }

      </script>



    <?php 
        if(isset($_GET['codigo']) || isset($_POST['codigo']) ){
    ?>
        <script>
            document.getElementById("keywords").value = "<?php echo $_GET['codigo'] ?>";
            buscar();
        </script>
    <?php
        }   
    ?>