   <?php

$wsqli = "SELECT COUNT(*) AS total FROM `pedidos` where id_users= '$id_users' and (na_pedido = '' OR na_pedido IS NULL) ORDER BY `pedidos`.`id` DESC";

$result = $linki->query($wsqli);
if ($linki->errno) die($linki->error);

$row = $result->fetch_assoc(); // Obtener el resultado como un array asociativo
$total_pedidos = $row['total']; // Almacenar el total en una variable



$wsqli_ticket = "SELECT COUNT(*) AS total FROM `ticket_soporte` WHERE stado = 'A' and id_user = '$id_users'";

$result_ticket = $linki->query($wsqli_ticket);
if ($linki->errno) die($linki->error);

$row = $result_ticket->fetch_assoc(); // Obtener el resultado como un array asociativo
$total_ticket = $row['total']; // Almacenar el total en una variable


   ?>
   
   <div class="row py-5"> 
            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pedidos</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                            <p class="card-text"><strong style="font-size: 24px;"><?php echo $total_pedidos; ?> </strong> Pedidos</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class="bg-primary d-inline-block p-2 rounded" style="border-radius: 10px;">
                                <i class='bx bx-clipboard text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                           
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="index.php?pag=pedido" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p >Ver Pedidos</p>
                            <i class='bx bx-right-arrow-alt ' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>
                      

        

            <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Ticket Soporte</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                                <p class="card-text"><strong style="font-size: 24px;"><?php echo $total_ticket; ?> </strong> Ticket abierto(s)</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class="bg-primary d-inline-block p-2 rounded" style="border-radius: 10px;">
                                <i class='bx bx-purchase-tag-alt text-white' style="font-size:30px;"></i>
                                   
                                </div>
                            </div> 
                        </div>
                           
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="index.php?pag=soporte" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p >Ver Ticket</p>
                            <i class='bx bx-right-arrow-alt ' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div>

           <!-- <div class="col-xl-3 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Pagos</h5>
                        <div class="d-flex"> 
                            <div class="mb-3">
                                <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                            </div> 

                            <div class="mb-3 ms-auto">
                                <div class="bg-primary d-inline-block p-2 rounded" style="border-radius: 10px;">
                                    <i class='bx bxs-credit-card text-white' style="font-size:30px;"></i>
                                </div>
                            </div> 
                        </div>
                      
                        <div class="stats-progress progress mb-2" style="height:3px"></div>
                        
                        <a href="" class="text-decoration-none text-black d-flex justify-content-end mt-2">
                            <p >Ver Pagos</p>
                            <i class='bx bx-right-arrow-alt ' style="font-size:20px"></i>
                        </a>
                    </div>
                </div>
            </div> -->


    </div>
         

   
