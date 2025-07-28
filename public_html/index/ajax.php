<?php
include ('../conexion.php');
if($_POST['id']){
$id=$_POST['id'];

if($id==0){
	echo "<option>Seleccionar Marcasss</option>";
}else{

  $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,
            $usuario, $contraseña);
  
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sentencia = $base_de_datos->query("SELECT id, marca FROM aplicaciones group by marca WHERE id='$id'");
	while ($row = $sentencia->fetch()) {
// En esta sección estamos llenando el select con datos extraidos de una base de datos.
            // 	echo "<option>Seleccionar Marcasss</option>";
           echo '<option value="'.$row[id].'">'.$row[marca].'</option>';
          }



	}
}
?>


