<?php 
	try{
		$url_base_datos = './../conexion.php';
		if ( !file_exists( $url_base_datos ) ){
			throw new Exception ('No encontró la base de datos');
		}
		else {
			include_once($url_base_datos);
			$base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
			$base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
	}
	catch(Exception $e){
		echo "
			<script>
				Swal.fire({
					icon: 'error',
					title: '" . $e->getMessage() . "',
				})
			</script>";
	}

	include_once('./funciones/buscar_imagenes.php');

	$sql = "SELECT * FROM nuevos_filtros";
	$seleccion_nuevos = $base_de_datos->prepare("SELECT * FROM nuevos_filtros") or die('Error al ver');
	$seleccion_nuevos->execute();
	while ($fila = $seleccion_nuevos->fetch(PDO::FETCH_ASSOC)) {
		$filtros []= $fila;
	}
?>

<link href="css/snadisplay.css" rel="stylesheet"> 
	<link rel="stylesheet" href="css/normalize_min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glider-js@1.7.3/glider.min.css">
	<link rel="stylesheet" href="css/estilos_slaide_filtro.css">
</head>
<body>	

	<div class="galerias" >
		<div class="carousel" >
		<h1 class="subtitle slider_tex">NUEVOS PRODUCTOS</h1>
		<BR>
			<div class="carousel__contenedor slider_tex">
				<button aria-label="Anterior" class="carousel__anterior" >
					<i class="fas fa-chevron-left"></i>
				</button>

				<div class="carousel__lista" id="lista_producto" >
					<?php 
						foreach($filtros as $filtro){ 
							$imagenes = buscar_imagenes($filtro['linea'], $base_de_datos, $filtro['codigo']);
					?>
							<div class="carousel__elemento">
								<div class="overlay" data="./../images/fichas-filtros/web/<?php echo $imagenes['imagen3'] ?>.jpg?t=<?php echo $rann?>" >
									<img src="./../images/fichas-filtros/web/<?php echo $imagenes['imagen'] ?>.jpg?t=<?php echo $rann?>" id="li">
									<div class="banda">
										<h2 class="subtitle_slider"><?php echo $filtro['codigo'] ?></h2>
									</div>	
								</div>
							</div>
					<?php 
						}
					?>
				</div>

				<button aria-label="Siguiente" class="carousel__siguiente"  >
					<i class="fas fa-chevron-right"></i>
				</button>
			</div>

		</div>
	</div>
	
	<script type="text/javascript" src="js/main_img.js"></script><!-- /.js zoom imagenes galeria incio -->
	<script src="js/glider_min.js"></script>
	<script src="js/kit_fontawesome.js" crossorigin="anonymous"></script>
	<script src="js/app_slider_filtro.js"></script>
