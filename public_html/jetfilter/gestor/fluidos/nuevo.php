<?php
 $loc = "../../../";
     $locj = "../../";
     $title = "Nuevo - Fluidos";
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
   $categorias = categoriaDeUnProducto('Fluidos', $base_de_datos);
?>

             

<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mt-5 mb-2">
        <div class="text-center flex-grow-1">
            <h1 class="titulo">Crear Producto y Especificaciones Fluidoss</h1>
        </div>

       <a href="./espec_fluidos.php"  class="btn-icon me-4" >Atrás</a>
    </div>

    <div class="stats-progress progress mb-5" style="height:3px"></div>

</div>

<div class="container mb-2 mt-5">

       <div class="card h-100 mb-5">
            <div class="card-body">
             <div class="row">
               <div class="col-12 col-md-6">
            <form action="crear_fluidos.php" id="form-especificacion" method="POST" class="formulario_aire" enctype="multipart/form-data">
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
                           <option value="" selected >¿Qué Categoría es?</option>
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
                     <th>Unidades de Empaque: (Opcional)</th>
                     <td>
                        <input type="number"  class="form-control" id="und_empaque" name="und_empaque" min="0" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 1:</th>
                     <td>
                        <input type="text"  class="form-control" id="detalle1" name="detalle1" >
                     </td>
                  </tr>
                  <tr>
                     <th>Detalle 2:</th>
                     <td>
                        <input type="text"  class="form-control" id="detalle2" name="detalle2" >
                     </td>
                  </tr>
                   <tr>
                     <th>Codigo de Barra:</th>
                     <td>
                        <input type="text"  class="form-control" id="codigobarra" name="codigobarra" >
                     </td>
                  </tr>
               <tr> 

                  <tr> 
                     <td class="b_td"> 
                        <input class="btn-icon" type="submit" value="Enviar" name="fluidos">
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

<?php 
     include('../index/footer.php');
?>

   <script src="./../js/comprobar_imagen.js"></script>  <!-- Función que comprueba que la imagen es del tamaño adecuado -->
   <script src="./../js/colocar_validacion.js"></script> <!-- Selecciona los input a los cuales se van a verificar el tamaño de la imagen -->
   <script src="./../js/funciones/cambiar_categoria.js" ></script> <!-- Detecta que hubo un cambio de categoria y trae los tipos de esa categoria -->
   <script src="./../js/funciones/evento_tecla_empaque.js" ></script> <!-- Detecta que se pulso una tecla en unidades de empaque -->
   <script src="./../js/funciones/filterInteger.js"></script> <!-- Funcion para verificar que el valor ingresado es entero -->
   <script src="./../js/funciones/filter.js"></script> <!-- Funcion para verificar que el valor ingresado es entero positivo -->
