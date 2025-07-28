<?php

	//<label>Aplicacion :</label><select id='lista2' name='lista2' >
	//<option value="0">Seleccionar aplicacion</option>

	include ('./../../conexion.php');

  	$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
			
  	$marca=$_POST['marca'];
    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	if ( $marca  >= "1" ) {
		$sentencia = $base_de_datos->query("SELECT * FROM aplicacion_marca  where id='$marca' ");
		$sentencia->setFetchMode(PDO::FETCH_ASSOC); 
		$cadena="<select id='lista2' name='lista2' class='selectBR' >
				<option value='0' class='opt' >Seleccionar Marca</option>";
		while ($row = $sentencia->fetch()) {
			$cadena=$cadena.'<option  value='.$row['id'].' >'.utf8_encode($row['marca']).'</option>';
		} 
		echo  $cadena."</select> ";
		
		
	}
	

?>

<script type="text/javascript">
	$(document).ready(function(){
		$('#lista2').val(0);
		recargarLista2();

		$('#lista2').change(function(){
			recargarLista2();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista2(){
		$.ajax({
			type:"POST",
			url:"./index/ajaxtabla.php",
			data:"marca2=" + $('#lista2').val(),
			success:function(r){
				$('#tabla').html(r);
			}
		});
	}
</script>
 
    <script
      src="https://code.jquery.com/jquery-3.2.0.min.js"
      integrity="sha256-JAW99MJVpJBGcbzEuXk4Az05s/XyDdBomFqNlM3ic+I="
      crossorigin="anonymous">
    </script>
    <script>
    

    //Esta es la función que una vez se cargue el documento será gatillada.
  
     $(function(){
        $("#lista2").val('0');
		 
    });
</script>

	
	

