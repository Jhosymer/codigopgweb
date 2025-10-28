<?php 
     $loc = "../../../";
     $locj = "./../../";
     $title = "Soporte";
    include_once('../index/header.php');
    include_once('../../../config/conexion.php');
    include_once('./../alertas/alerta_error.php');
    include_once('./../alertas/alerta_nuevo.php');
    include_once('./../alertas/alerta_eliminado.php');
    include_once('./../alertas/alerta_actualizado.php');

    alerta_nuevo();
    alerta_actualizado('Ticket Soporte ha sido actualizado');
    alerta_eliminado("Ticket Soporte se ha eliminado correctamente");
?>

<div class='container light color_blanco py-3 mt-5 overflow-auto rounded '>
   

    <h1 class="titulo text-center ">Ticket Soporte</h1>

    <div class="col-12 content">

            <div class="container"> 
                <form action="index.php?pag=soporte" method="post" >
                    <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
                        <button type="submit" class="btn btn-info m-2 w-100 text-white" name="A" id="">Abiertos</button>
                        <button type="submit" class="btn btn-info m-2 w-100 text-white" name="R" id="">En Revisión</button>
                        <button type="submit" class="btn btn-info m-2 w-100 text-white" name="C" id="">Cerrado</button>
                        <button type="submit" class="btn btn-info m-2 w-100 text-white" name="T" id="">Todos</button>
                    </div> 
                </form>
            </div> 
            

            <table class="table table-striped table-hover color_blanco  table-responsive table-bordered"  id="example" cellspacing="0" width="100%">
            <thead>
                <tr>
                <th scope="col">#</th>
                <th scope="col">cliente</th>
                <th scope="col">Ticket Soporte </th>
                <th scope="col">Asunto</th>
                <th scope="col">Soporte</th>
                <th scope="col">Anexo</th>
                <th scope="col">Estado</th>
                <th scope="col">Fecha Creado</th>
                <th scope="col">Fecha Revisión</th>
                <th scope="col">Anexo Revisión</th>
                <th scope="col">Fecha Cerrado</th>
                <th scope="col">Operaciones</th>
                </tr>
            </thead>
            <tbody>

                <?php 
                    $contador = 1;
                if(isset($_POST['C']) ){
                $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre,  u.name AS user_name
                FROM ticket_soporte ts
                JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                JOIN users u ON ts.id_user = u.id
                WHERE  ts.stado ='C'";
                }  else if(isset($_POST['R']) ){
                $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre,  u.name AS user_name
                FROM ticket_soporte ts
                JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                JOIN users u ON ts.id_user = u.id
                WHERE  ts.stado = 'R'";
                } else if(isset($_POST['T']) ){
                $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre,  u.name AS user_name
                FROM ticket_soporte ts
                JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                JOIN users u ON ts.id_user = u.id";
                } else if(isset($_POST['A']) ){
                $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre,  u.name AS user_name
                FROM ticket_soporte ts
                JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                JOIN users u ON ts.id_user = u.id
                WHERE  ts.stado ='A'";
                } else {
                $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre,  u.name AS user_name
                FROM ticket_soporte ts
                JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
                JOIN users u ON ts.id_user = u.id
                WHERE  ts.stado ='A'";
                }

                $result = $base_de_datos->query($wsqli);
                if ($base_de_datos->errorCode() !== '00000') {
                    $errorInfo = $base_de_datos->errorInfo();
                    die("Query failed: " . $errorInfo[2]);
                }
            
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {

                    $id=$row['id'];  
                    $asunto=$row['asunto'];  
                    $tipo=$row['tipo_soporte_nombre'];
                    $anexo = $row['anexo'];
                        if (!empty($anexo)) {
                            $anexo = 'Sí'; // Hay anexo
                        } else {
                            $anexo = 'No'; // No hay anexo
                        }
                    
                    $stado = $row['stado'];
                        if ($stado == 'A') {
                            $stado = 'Abierto';
                        } elseif ($stado == 'R') {
                            $stado = 'En Revisión';
                        } elseif ($stado == 'C') {
                            $stado = 'Cerrado';
                        }
                    $fecha_creado = $row['fecha_creado'];
                    $fecha_revision = $row['fecha_revision'];
                        $anexo_r = $row['anexo_r'];
                        if (!empty($anexo_r)) {
                            $anexo_r = 'Sí'; // Hay anexo
                        } else {
                            $anexo_r = 'No'; // No hay anexo
                        }
                    $fecha_cerrado = $row['fecha_cerrado'];
                    $user_name = $row['user_name'];
                    
            ?>


        
            <tr>
            <th scope="row"> <?php echo $contador;   ?></th>
            <td> <?php echo $user_name;  ?> </td>
            <td> <?php echo $id;  ?> </td>
            <td> <?php echo $asunto;  ?> </td>
            <td> <?php echo  $tipo;  ?> </td>
            <td> <?php echo $anexo;  ?> </td>
            <td> <?php echo $stado;  ?> </td>
            <td> <?php echo $fecha_creado;  ?> </td>
            <td> <?php echo $fecha_revision;  ?> </td>
            <td> <?php echo $anexo_r;  ?> </td>
            <td> <?php echo $fecha_cerrado;  ?> </td>
            
        
            <td class="text-center"> 
            <button type="submit" class ='btn btn-primary mb-2' data-bs-toggle="modal" data-bs-target="#editarTicketModal-<?php echo $id;  ?>">
                <?php if ($row['stado'] == 'C'): ?>
                        <i class="bx bx-search"></i>
                        <?php else: ?>
                            <i class="bx bx-edit"></i>
                        <?php endif; ?>
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
                <th scope="col"></th>
                <th scope="col"></th>
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
    include("modal_editar_ticket.php");
    include("../index/footer.php");

?>
    
    