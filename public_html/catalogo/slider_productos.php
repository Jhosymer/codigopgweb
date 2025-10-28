<?php
try {
    $url_base_datos = './../config/conexion.php';
    if (!file_exists($url_base_datos)) {
        throw new Exception('No se encontró la base de datos');
    } else {
        include_once($url_base_datos);
        $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
        $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
} catch (Exception $e) {
    echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
}

include_once('./../funciones/buscar_imagenes.php');

$seleccion_nuevos = $base_de_datos->prepare("SELECT id, linea, codigo, id_marca, id_marca1, id_marca2 FROM nuevos_filtros") or die('Error al ver');
$seleccion_nuevos->execute();
$filtros = $seleccion_nuevos->fetchAll(PDO::FETCH_ASSOC);


// 2. Crear un mapa (array) de nombres de marca para búsqueda rápida
$marcas_raw = $base_de_datos->query("SELECT id, marca FROM aplicacion_marca")->fetchAll(PDO::FETCH_ASSOC);
$mapa_marcas = [];
foreach ($marcas_raw as $marca) {
    $mapa_marcas[$marca['id']] = $marca['marca'];
}

// Función para obtener el nombre de la marca por ID
// ESTA ES LA FUNCIÓN QUE CAUSABA EL ERROR SI SE CERRABA EL PHP JUSTO DESPUÉS DE ELLA
function obtener_nombre_marca($id, $mapa_marcas) {
    return isset($mapa_marcas[$id]) ? $mapa_marcas[$id] : null;
}
?>

<div class="container my-5 mt-5">
    <h2 class="Roboto-Bold rojoweb text-uppercase">NUEVOS PRODUCTOS</h2>

     <div class="carousel-container position-relative mt-5">
        <div class="splide" id="nuevosProductosSplide">
            <div class="splide__track">
                <ul class="splide__list">
                    <?php
                    // Recorre todos los productos y crea una diapositiva para cada uno
                    foreach ($filtros as $filtro) {
                        $imagenes = buscar_imagenes($filtro['linea'], $base_de_datos, $filtro['codigo']);

                        if (!empty($imagenes['imagen'])) {
                            
                            // CONSTRUCCIÓN DEL TEXTO DE APLICACIONES BASADO EN id_marca, id_marca1, id_marca2
                            $aplicaciones = [];
                            
                            $marca1 = obtener_nombre_marca($filtro['id_marca'], $mapa_marcas);
                            if ($marca1) $aplicaciones[] = $marca1;

                            $marca2 = obtener_nombre_marca($filtro['id_marca1'], $mapa_marcas);
                            if ($marca2) $aplicaciones[] = $marca2;

                            $marca3 = obtener_nombre_marca($filtro['id_marca2'], $mapa_marcas);
                            if ($marca3) $aplicaciones[] = $marca3;

                            // Une las marcas con coma y espacio
                            $texto_aplicaciones = implode(', ', $aplicaciones);
                            
                            // ABRIMOS PHP PARA MOSTRAR EL HTML Y CERRAMOS LA LLAVE DEL IF Y FOREACH ABAJO
                    ?>
                        <li class="splide__slide">
                            <div class="card card-custom h-100 shadow-sm border-0">
                                <a href="ficha_tecnica/index.php?codigo=<?php echo $filtro['codigo'] ?>&clase=<?php echo $filtro['linea'] ?>" class="btn btn-custom btn-light-custom d-flex flex-column align-items-center justify-content-between h-100 py-4 text-decoration-none">
                                    
                                    <img src="./../images/fichas-filtros/web/<?php echo $imagenes['imagen'] ?>.jpg?t=<?php echo time()?>" class="img-fluid my-auto" alt="Filtro de <?php echo $filtro['linea'] . ' ' . $filtro['codigo'] ?>">
                                    
                                    <div class="w-100 text-center mt-3">
                                        <h5 class="card-title-custom fw-bold" style="color: #c90000; font-size: 1.5rem;"><?php echo $filtro['codigo']; ?></h5>
                                    </div>

                                    <div class="d-flex justify-content-center align-items-center w-100 p-2 mt-3 shadow-sm" style="background-color: #f8f8f8; border-radius: 10px;">
                                        <i class="bx bxs-car me-2" style="font-size: 1.5rem; color: #c90000;"></i> 
                                        
                                        <p class="mb-0 text-center text-dark Roboto-Bold" style="font-size: 0.9rem; font-weight: 500;">
                                            <?php echo !empty($texto_aplicaciones) ? $texto_aplicaciones : 'Aplicación no disponible'; ?>
                                        </p>
                                        
                                        <i class="bx bxs-car-check ms-2" style="font-size: 1.5rem; color: #c90000;"></i>
                                    </div>
                                    
                                </a>
                            </div>
                        </li>
                    <?php 
                        } 
                    } 
                    ?>
                </ul>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#nuevosProductosSplide" data-bs-slide="prev">
            <i class="bx bx-chevron-left" aria-hidden="true"></i>
            <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#nuevosProductosSplide" data-bs-slide="next">
            <i class="bx bx-chevron-right" aria-hidden="true"></i>
            <span class="visually-hidden">Siguiente</span>
        </button>
    </div>
    
</div>