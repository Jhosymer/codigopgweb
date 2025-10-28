<option	value="">Seleccione una opcion</option>
<?php

$wsqli="select * from tipo_soporte";
$result = $linki->query($wsqli);
if($linki->errno) die($linki->error);
while($row2 = $result->fetch_array()){ 
$ide=$row2['id'];

	?>
<option	value="<?php echo $row2['id'] ?>"><?php echo $row2['nombre'] ?></option>

				  
<?php }?> 