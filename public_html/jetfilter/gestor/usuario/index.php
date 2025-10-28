<?php
$loc = "../../../";
$locj = "./../../";
$title = "Usuario Cliente";
include_once('../index/header.php');
include_once('./../alertas/alerta_error.php');
include_once('./../alertas/alerta_nuevo.php');
include_once('./../alertas/alerta_eliminado.php');
include_once('./../alertas/alerta_actualizado.php');

// Lógica para mostrar mensajes de éxito desde la sesión
if (isset($_SESSION['nuevo']) && isset($_SESSION['mensaje'])) {
    alerta_nuevo($_SESSION['mensaje']); 
    // Limpia las variables de sesión para que no se muestren de nuevo
    unset($_SESSION['nuevo']);
    unset($_SESSION['mensaje']);
}

// Lógica para mostrar mensajes de éxito de actualización
if (isset($_SESSION['actualizado']) && isset($_SESSION['mensaje'])) {
    alerta_actualizado($_SESSION['mensaje']); 
    unset($_SESSION['actualizado']);
    unset($_SESSION['mensaje']);
}

// Lógica para mostrar mensajes de error
if (isset($_SESSION['error']) && isset($_SESSION['mensaje'])) {
    alerta_error($_SESSION['mensaje']);
    unset($_SESSION['error']);
    unset($_SESSION['mensaje']);
}
?>

<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>
 

  <h1 class="titulo text-center ">Usuarios Clientes</h1>

  <div class="col-12 content mb-5">
   <form action="nuevo.php" method="post" id="nuevo">
  <button type="submit" class="btn-icon mt-5 mb-3" name="nuevo">nuevo</button>
  </form>
     
      
    <table class="table table-striped table-hover color_blanco table-responsive table-bordered" id="example" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">Nombre</th>
          <th scope="col">Rif</th>
          <th scope="col">Email</th>
          <th scope="col">Operaciones</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $contador = 1;
        $wsqli = "SELECT * FROM `users` where rol = 2";
        $result = $base_de_datos->query($wsqli);
        
        if ($base_de_datos->errorCode() !== '00000') {
          $errorInfo = $base_de_datos->errorInfo();
          die("Query failed: " . $errorInfo[2]);
        }
        
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
          $id = $row['id'];
          $rif = $row['rif'];
          $nombre = $row['name'];
          $email = $row['email'];
        ?>
        <tr>
          <th scope="row"><?php echo $contador; ?></th>
          <td><?php echo $nombre; ?></td>
          <td><?php echo $rif; ?></td>
          <td><?php echo $email; ?></td>
          <td class="text-center">
            <button type="button" class='btn btn-primary mb-2' data-bs-toggle="modal" data-bs-target="#editarusuarioModal-<?php echo $id; ?>">
              <i class="bx bx-edit"></i>
            </button>
          </td>
        </tr>
        <?php
          $contador++;
        }
        ?>
      </tbody>
      <tfoot>
        <tr>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
          <th scope="col"></th>
        </tr>
      </tfoot>
    </table>  
  </div>
</div>

<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>

<script src="<?php echo $loc; ?>js/js_vende/menutables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/calculoporprecios.js"></script>

<?php  
  include("modal_editar_usuario.php");
  include("../index/script_usuario.php");
  
  include("../index/footer.php");

?>