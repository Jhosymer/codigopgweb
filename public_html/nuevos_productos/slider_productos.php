<?php 

  $rann = date('H:i:s');
	try{
		$url_base_datos = './../../conexion.php';
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

	include_once('./../funciones/buscar_imagenes.php');

	$sql = "SELECT * FROM nuevos_filtros";
	$seleccion_nuevos = $base_de_datos->prepare("SELECT * FROM nuevos_filtros") or die('Error al ver');
	$seleccion_nuevos->execute();
	while ($fila = $seleccion_nuevos->fetch(PDO::FETCH_ASSOC)) {
		$filtros []= $fila;
	}
?>

	 <!-- <link href="./../css/snadisplay.css" rel="stylesheet"> -->
	<link rel="stylesheet" href="./../css/normalize_min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glider-js@1.7.3/glider.min.css">
	<link rel="stylesheet" href="./../css/estilos_slaide_filtro.css">
</head>
<body>	

	<div class="galerias" >
		<div class="carousel" >
       
		<h1 class="tutulo_np">NUEVOS PRODUCTOS DE LA MARCA WEB </h1>
		<BR>
			<div class="carousel__contenedor slider_tex">
				<button aria-label="Anterior" class="carousel__anterior" >
					<i class="fas fa-chevron-left"></i>
				</button>

				<div class="carousel__lista" id="lista_producto" >
					<?php 
						foreach($filtros as $filtro){ 
							$imagenes = buscar_imagenes($filtro['linea'], $base_de_datos, $filtro['codigo']);
							$codigo_url = str_replace(" ","%20",$filtro['codigo'])

					?>
							<div class="carousel__elemento">
							
								<div class="overlay" data-bs-toggle="modal" data-bs-target="#detalleModal" data-operation="<?php echo $filtro['codigo']; ?>">
								<img data-codigo="<?php echo $filtro['codigo']; ?>" src="./../images/fichas-filtros/web/<?php echo $imagenes['imagen'] ?>.jpg?t=<?php echo $rann?>"  class="img_galeria" alt="Filtro de <?php echo $filtro['linea'] ." ".$filtro['codigo'] ?>" width="300" height="200" loading="lazy">
									
									<a href="#" class="banda btn-primary d-grid" data-bs-toggle="modal" data-bs-target="#detalleModal" name="" data-operation="<?php echo $filtro['codigo']; ?>"><h2 class="subtitle_slider"><?php echo $filtro['codigo']; ?></h2></a>
								
								
									


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
			<input type="hidden" name="">
		</div>
	</div>

	<!-- Modal -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
        <div class="modal-content" >
		<div class="modal-body " id="detalle">

		</div>
        </div>
        </div>
    </div>


	<script src="./../js/app_slider_filtro.js"></script>
	<script src="./../js/kit_fontawesome.js" crossorigin="anonymous"></script>
	<script type="text/javascript" src="./../js/main_img.js"></script><!-- /.js zoom imagenes galeria incio -->
	<script src="./../js/glider_min.js"></script>
	
	
