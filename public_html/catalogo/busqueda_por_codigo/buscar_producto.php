
 <?php
  include("busqueda_ubicacion.php"); // para saber desde donde hacen la busqueda 
        
?>

  <?php
    $paginas = [10, 25, 50, 100]; 
    ?> 

    <div class="aplicacion_producto">
        <section class="infor_aplica2" id="buscar">
            <section class="detalle">
                <div class="about_aplicacion_sel">
                    <h1 class="titulo_bold rojoweb text-uppercase">Busqueda por código</h1>

   
                    <p  class ="mt-3">
                        <em>Introduzca un código Web o de otro fabricante para encontrar su equivalencia Web</em><br>
                        <span class="resaltar">[*]</span> Indicará una coincidencia exacta
                    </p>


                <div class="d-flex align-items-center mt-5">
                    <div>
                        <input type="text" name="keywords" class="form-control vBuscar" id="keywords" autocomplete="off" />
                        <input type="text" name="ciudad" class="form-control vBuscar" style="display: none;" id="ciudad" autocomplete="off" value="<?php echo $Ciudad ?>"/>
                        <input type="text" name="estado" class="form-control vBuscar" style="display: none;" id="estado" autocomplete="off" value="<?php echo $estado ?>"/>
                    </div>
                    <a onclick="buscar('1')" id="boton-busqueda" name="search" class="btn-icon ms-3">BUSCAR</a>
                </div> 
                    </div>
                </div>
            </section>
        </section>

        <section class="infor_aplica" id="busquedas">
            <h4 class='Roboto-Bold mt-5 mb-3' id='titulo_resultados' style='display:none;'>Resultados para: <span class='rojoweb' id="codigo_buscado"></span> </h4>
            <div class="row">
                <div class="col-12 col-md-6 tabla_resultados" id="resultados_busqueda"></div>
                <div class="col-12 col-md-6 tabla_resultados" id="resultados_busqueda_equivalencias"></div>
            </div>
        </section>
    </div>

 


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
                history.replaceState(null, "", `./index.php?codigo=${input}`);
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
                        history.pushState(stateObj, "Buscar Codigo", `./index.php?codigo=${input}`);
                        document.getElementById("codigo_buscado").innerText = input;
                        document.getElementById("titulo_resultados").style.display = 'block';
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