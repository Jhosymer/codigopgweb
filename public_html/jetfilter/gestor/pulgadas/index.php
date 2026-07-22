<?php
    $loc = "../../../";
     $locj = "../../";
     $title = "Pulgadas";


    include_once('../index/header.php');
    include_once('../../../config/conexion.php');

   $paginas = [10, 25, 50, 100];

    //Alertas a Comprobar
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');
    include_once('./../alertas/alerta_codigo_existe.php');

    alerta_nuevo();
    alerta_actualizado('La aplicacion ha sido actualizada');
    alerta_eliminado("La aplicación se ha eliminado correctamente");
    alerta_codigo_existe("Ya existe una pulgadas con un código similar");
?>

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Pulgadas</h1>
        </div>
        <button type="button" class="btn-icon me-4" onclick="abrirModal(0, '', '', 'nuevo')">
    Nuevo
</button>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>
        <div class=" container bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm border">
            <div class="d-flex align-items-center about_tabla_edi">
            <label for="campo" class="me-2">Mostrar:</label>
            <select name="num_registros" id="num_registros" class="form-select form-select-sm me-2" style="width: auto;">
                <?php 
                    foreach($paginas as $pag){
                        if($pag == $perPage){
                        ?>
                            <option value="<?php echo $pag; ?>" selected><?php echo $pag; ?></option>
                        <?php
                        }
                        else{
                        ?>
                            <option value="<?php echo $pag; ?>"><?php echo $pag; ?></option>
                        <?php
                        }
                    }
                ?> 
            </select>
            <input type="text" class="form-control form-control-sm" name="campo" id="campo" size="30" placeholder="Buscar" style="width: auto;">
        </div>

            <table class="table table-hover table-responsive dataTable mt-5" id="example">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Código</th>
                        <th>Valor Nominal</th>  
                        <th>Acciones</th>
                    </tr>
                </thead>
                 <tbody class="table-group-divider" id="contenido">

                </tbody>
            </table>
                <div id="row" class="mb-5 me-2">
                <label id="lbl-total"></label>
                <div id="paginacion" class="links d-flex justify-content-end">
                </div>
            </div>
            
        

</div>
    <?php 
  


        require_once("../index/footer.php");
    ?>

    <script>
        let paginaActual = 1;
        getData();

        document.getElementById("campo").addEventListener("keyup", function(){
            getData(paginaActual);
        }, false);

        document.getElementById("num_registros").addEventListener("change", function(){
            getData(paginaActual);
        }, false);

        function getData(pagina){
            let input = document.getElementById('campo').value;
            let content = document.getElementById('contenido');
            let num_registros = document.getElementById("num_registros").value;

            $.ajax({
                data: {
                    "campo": input,
                    "num_registros": num_registros,
                    "pagina": pagina,
                },
                url: './plantilla_pulgadas.php',
                dataType: 'json',
                type: 'POST',
                success: function(response){
                    content.innerHTML = response.data;
                    if(response.totalFiltro != response.totalRegistros){
                        document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + response.totalFiltro + " de " + response.totalRegistros + " registros</p>";
                    }
                    else {
                        document.getElementById('lbl-total').innerHTML = "";
                    }
                    document.getElementById('paginacion').innerHTML =  response.paginacion;
                },
                error: function(){
                    alert("Error");
                }
            });
        }

function mostrarFormulario() {
    // Cambiar el título al pasar al formulario
    const modo = document.getElementById('id_pulgadas').value === "" ? "Nueva Pulgadas" : "Editar Pulgadas";
    document.getElementById('modalPulgadasLabel').innerText = modo;

    document.getElementById('contenedorAlerta').classList.add('d-none');
    document.getElementById('contenedorFormulario').classList.remove('d-none');
    document.getElementById('footerFormulario').classList.remove('d-none');
}

function abrirModal(id, codigo, valor, modo) {
    const modalElement = document.getElementById('modalPulgadas');
    const modal = new bootstrap.Modal(modalElement);
    const form = document.getElementById('formModalPulgadas');
    
    // Título dinámico
    const titulo = document.getElementById('modalPulgadasLabel');
    if (modo === 'nuevo') titulo.innerText = "Nueva Pulgadas";
    else if (modo === 'editar') titulo.innerText = "Editar Pulgadas";
    else titulo.innerText = "Detalles de la Pulgadas";

    form.action = "crud.php";
    document.getElementById('id_pulgadas').value = id;
    document.getElementById('codigo').value = codigo;
    document.getElementById('valor_nominal').value = valor;

    // Lógica de visibilidad (Input vs Texto plano)
    const isVer = (modo === 'ver');
    document.getElementById('codigo').classList.toggle('d-none', isVer);
    document.getElementById('text_codigo').classList.toggle('d-none', !isVer);
    document.getElementById('text_codigo').innerText = codigo;
    
    document.getElementById('input_group_valor').classList.toggle('d-none', isVer);
    document.getElementById('text_valor').classList.toggle('d-none', !isVer);
    document.getElementById('text_valor').innerText = valor ? valor + " mm" : "";
    
    // Botón guardar oculto solo en modo "ver"
    document.getElementById('btnGuardar').classList.toggle('d-none', isVer);

    modal.show();
}
    </script>
<?php include('modal_crud.php');?>
<script src="./../js/eliminar.js" ></script>