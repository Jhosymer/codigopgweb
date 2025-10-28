<?php
//$linki = new mysqli("localhost", "root", "", "basededatos");
$linki = new mysqli("localhost", "webfiltr_admin", "123abc*1", "webfiltr_webfiltros");
	$linki->query("SET CHARACTER SET utf8");
	if (mysqli_connect_errno()) {
		die("No se puede conectar a la base de datos:" . mysqli_connect_error());
	} 
	//else  {	echo "<h1> se conecto con exito</h1>";}  
?>