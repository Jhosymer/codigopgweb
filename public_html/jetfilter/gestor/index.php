<?php 
     $loc = "../../";
     $locj = "../";
     $title = "Jet Filter | Administrador de la marca WEB";
     require_once("./index/header.php");
     require_once("./../../config/conexion.php");
   
    $wsqli_ticket = "SELECT COUNT(*) AS total FROM `ticket_soporte` WHERE stado = 'A'";
    $stmt = $base_de_datos->query($wsqli_ticket);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_ticket = $row['total']; // Almacenar el total en una variable

    $wsqli = "SELECT COUNT(*) AS total FROM `pedidos` WHERE (na_pedido = '' OR na_pedido IS NULL)";
    $stmt = $base_de_datos->query($wsqli);
    $row_pedido = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_pedidos = $row_pedido['total']; // Almacenar el total en una variable


?>

<div class="container  mb-2 mt-5" >


<h1 class="titulo text-uppercase text-center mt-5 py-5"> Bienvenido <?php echo $_SESSION['name']; ?> </h1>

<a href="<?php echo $loc; ?>index.php" target="_blank" class="btn-icon mt-5" id="descargar_colores_corporativos">Ir a Webfiltros</a>

 
<div class="row py-5"> 
            <?php 
if (in_array(7, $permisos_usuario)) { ?>
            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                    <h5 class="Roboto-Bold rojoweb">Catálogo</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                            <p class="card-text"> Personalizar y actualizar tu catálogo de productos</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx-book text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="especificaciones.php" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Catálogo</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div> 
<?php
} if (in_array(2, $permisos_usuario)) { ?>
            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                    <h5 class="Roboto-Bold rojoweb"> Distribuidores</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                            <p class="card-text"> Crear y actualizar datos de Distribuidores autorizados </p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bxs-truck text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./distribuidoras_venezuela/detalle/espec_distribuidor.php" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Detalle Distribuidores</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>


            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                    <h5 class="Roboto-Bold rojoweb"> Zona de Distribución</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                               <p class="card-text"> Crear y actualizar zonas de distribución autorizadas </p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx bx-map text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./distribuidoras_venezuela/estado/estados_distribuidores.php" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Zona de distribución </p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>

<?php
} if (in_array(3, $permisos_usuario)) { ?>
            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                       <h5 class="Roboto-Bold rojoweb">Pedidos</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                              <p class="card-text"><span class="Roboto-Bold"><?php echo $total_pedidos; ?></span> Pedidos Por Procesar</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx-clipboard text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./pedido/" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Pedidos</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>
<?php
} if (in_array(4, $permisos_usuario)) { ?>

            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                       <h5 class="Roboto-Bold rojoweb">Ticket Soporte</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                              <p class="card-text"><span class="Roboto-Bold"> <?php echo $total_ticket; ?></span> Ticket Abierto(s)</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx-purchase-tag-alt text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./soporte/" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Ticket</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>

<?php
} if (in_array(5, $permisos_usuario)) { ?>
            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                       <h5 class="Roboto-Bold rojoweb">Usuarios Clientes</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                              <p class="card-text">Crear y actualizar datos de Clientes</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx-user text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./usuario/" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Usuarios</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>
<?php
} if (in_array(6, $permisos_usuario)) { ?>
            <div class="col-xl-3 col-md-6 mb-5">
                <div class="card">
                    <div class="card-body">
                       <h5 class="Roboto-Bold rojoweb">Administradores</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                              <p class="card-text">Crear y actualizar datos de Usuarios Administradores </p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class=" d-inline-block p-2 rounded" style="background-color: #E2001A; border-radius: 10px;">
                                    <i class='bx bx-user text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                            
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="./usuario_asministradores/" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p class="Roboto-Bold">Ver Usuarios</p>
                            <i class='bx bx-right-arrow-alt' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>

<?php  } ?>          


    </div>

</div>


<?php

require_once("./index/footer.php");
?>