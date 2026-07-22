<?php
 $loc = "../../../";
     $locj = "../../";
     $title = "Nuevo - Sellado";
     require_once("../index/header.php");
   //Se incluyen los archivos necesarios

  
   include_once('../../../config/conexion.php');

   //Se incluyen las alertas a verificar
   include_once('./../alertas/alerta_error.php');
   include_once('./../alertas/alerta_codigo_existe.php');

   //Si hubo algún error al hacer una edición del filtro o al eliminar la imagen
   alerta_error();
   //Si el código de filtro que se intento subir ya existe
   alerta_codigo_existe();

   
   /* Se incluye una funcion que busca todos las categorias de la clase */
   include_once('./../componentes/consultaCategoriasDeUnProducto.php');
   //Primer Parametro el Nombre de la Clase
   //Segundo Parametro la variable de conexion a la base de datos
   $categorias = categoriaDeUnProducto('Sellado', $base_de_datos);

   $query_roscas = $base_de_datos->prepare("SELECT id, codigo FROM roscas WHERE deleted_at IS NULL ORDER BY codigo ASC");
   $query_roscas->execute();
   $lista_roscas = $query_roscas->fetchAll(PDO::FETCH_ASSOC);
?>

   
       
       
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Producto y Especificaciones Sellado</h1>
        </div>

       <a href="./espec_sellado.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
            <div class="card-body">
             <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_sellado.php" id="form-especificacion" method="POST" class="formulario_aire" enctype="multipart/form-data">
                <table class="table table-striped table-hover table-responsive dataTable">
                  <tr>
                     <th>Código:</th>
                     <td>
                        <input type="text"  class="form-control" id="codigo" name="codigo" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Categoría:</th>
                     <td>
                        <select name="categoria" id="categoria" class="form-select" >
                           <option value="" selected >¿Qué Categoria es?</option>
                           <?php
                              foreach( $categorias as $categoria ){
                                 ?>
                                    <option value="<?php echo $categoria['id']; ?>"><?php echo $categoria['categoria'] . ' -- ' . $categoria['nombre']; ?></option>
                                 <?php
                              }
                           ?>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Tipo:</th>
                     <td>
                        <select name="tipo" id="tipo" class="form-select" required>
                           <option value="N/D" selected>N/D</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Filtración: (Opcional)</th>
                     <td>
                        <select name="filtracion" id="filtracion" class="form-select" >
                           <option value="" selected >¿Cuál es la filtración?</option>
                           <option value="Ultrafino">Ultrafino</option>
                           <option value="Fino">Fino</option>
                           <option value="Estándar">Estándar</option>
                           <option value="Grueso">Grueso</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Diámetro Externo:</th>
                     <td>
                        <input type="number"  class="form-control" id="diametroext" name="diametroext" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required>
                     </td>
                  </tr>
                  <tr>
    <th>¿Tiene Rosca?</th>
    <td>
        <select id="sw_rosca" class="form-select" onchange="toggleRoscaSellado()" required>
            <option value="" selected disabled>Seleccione una opción...</option>
            <option value="si">Con Rosca</option>
            <option value="no">Sin Rosca</option>
        </select>
    </td>
</tr>

<tr id="fila_rosca_select" style="display:none;">
    <th>Rosca:</th>
    <td>
        <select name="id_rosca" id="id_rosca" class="form-select">
            <option value="" selected disabled>Seleccione una rosca</option>
            <?php foreach($lista_roscas as $rosca): ?>
                <option value="<?php echo $rosca['id']; ?>"><?php echo $rosca['codigo']; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
</tr>

<tr id="fila_diametroint" style="display:none;">
    <th>Diámetro Interno:</th>
    <td>
        <input type="number" class="form-control" id="diametroint" name="diametroint" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)">
    </td>
</tr>
                  
                  <tr>
                     <th>Altura:</th>
                     <td>
                        <input type="number"  class="form-control" id="altura" name="altura" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Diámetro Emp Ext:</th>
                     <td>
                        <input type="number"  class="form-control" id="diametroempext" name="diametroempext" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Diámetro Emp Int:</th>
                     <td>
                        <input type="number"  class="form-control" id="diametroempint" name="diametroempint" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Espesor Emp:</th>
                     <td>
                        <input type="number"   class="form-control"id="espesoremp" name="espesoremp" min="0" step=".01" onblur="this.value = parseFloat(this.value).toFixed(2)" required>
                     </td>
                  </tr>
                  <tr>
                     <th>Valvula Al:</th>
                     <td>
                        <select name="valvulaal" id="valvulal" class="form-select" required>
                           <option value="" selected disabled>¿Existe una Valvula AL?</option>
                           <option value="1" >Sí </option>
                           <option value="0" >No</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Apertura:</th>
                     <td>
                        <input type="text"  class="form-control" id="apertura" name="apertura" >
                     </td>
                  </tr>
                  <tr>
                     <th>Valvula Ad:</th>
                     <td>
                        <select name="valvulaad" id="valvulaad" class="form-select" required>
                           <option value="" selected disabled>¿Existe una Valvula AD?</option>
                           <option value="1" >Sí </option>
                           <option value="0" >No</option>
                        </select>
                     </td>
                  </tr>
                  <tr>
                     <th>Unidades de Empaque: (Opcional)</th>
                     <td>
                        <input type="number"  class="form-control" id="und_empaque" name="und_empaque" min="0" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 1: (Opcional)</th>
                     <td>
                        <input type="text"  class="form-control" id="detalle1" name="detalle1" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 2: (Opcional)</th>
                     <td>
                        <input type="text"   class="form-control" id="detalle2" name="detalle2" >
                     </td>
                  </tr>
                  <tr>
                     <th>Codigo de Barra:</th>
                     <td>
                        <input type="text"  class="form-control" id="codigobarra" name="codigobarra" >
                     </td>
                  </tr>

                  <tr> 
                     <td class="b_td">
                        <input class="btn-icon" type="submit" value="Enviar" name="sellado">
                     </td>
                     <td class="b_td">
                        <input class="btn-icon" type="reset">
                     </td>
                  </tr>
               </table>
                    </div>
                    <div class="col-12 col-md-6">
            <?php
               include_once('./../componentes/galeria_nuevo.php'); //Galeria que permite agregar nueva imagen
            ?>
         </form>
   </div>
                </div>
            </div>
        </div>
   
</div>
<script>
function toggleRoscaSellado() {
    let seleccion = document.getElementById('sw_rosca').value;
    let filaRosca = document.getElementById('fila_rosca_select');
    let filaDiametro = document.getElementById('fila_diametroint');
    
    // Inputs reales
    let inputRosca = document.getElementById('id_rosca');
    let inputDiametro = document.getElementById('diametroint');

    if (seleccion === 'si') {
        // Mostrar Rosca, Ocultar Diámetro
        filaRosca.style.display = 'table-row';
        filaDiametro.style.display = 'none';
        
        // Limpiar el valor que no se usa para que viaje nulo
        inputDiametro.value = ""; 
        inputRosca.setAttribute('required', 'required');
        inputDiametro.removeAttribute('required');
    } else if (seleccion === 'no') {
        // Mostrar Diámetro, Ocultar Rosca
        filaRosca.style.display = 'none';
        filaDiametro.style.display = 'table-row';
        
        // Limpiar el valor que no se usa
        inputRosca.value = "";
        inputDiametro.setAttribute('required', 'required');
        inputRosca.removeAttribute('required');
    }
}
</script>

<?php 
     include('../index/footer.php');
?>

   <script src="./../js/comprobar_imagen.js"></script>  <!-- Función que comprueba que la imagen es del tamaño adecuado -->
   <script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
   <script src="./../js/funciones/cambiar_categoria.js" ></script> <!-- Detecta que hubo un cambio de categoria y trae los tipos de esa categoria -->
   <script src="./../js/funciones/evento_tecla_empaque.js" ></script> <!-- Detecta que se pulso una tecla en unidades de empaque -->
   <script src="./../js/funciones/filterInteger.js"></script> <!-- Funcion para verificar que el valor ingresado es entero -->
   <script src="./../js/funciones/filter.js"></script> <!-- Funcion para verificar que el valor ingresado es entero positivo -->