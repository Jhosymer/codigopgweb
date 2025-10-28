
<div class=' bg-white py-3  overflow-auto rounded mt-5'>


<div class="col-12 content">


    <button type="submit" class ='btn btn-primary mb-2' data-bs-toggle="modal" data-bs-target="#nuevoTicketModal">Nuevo</button>
      
    <div class="container"> 
         <form action="index.php?pag=soporte" method="post" >
            <div class="d-flex justify-content-lg-around my-4 align-content-center" id="btnTenRec">
                <button type="submit" class="btn btn-info m-2 w-100" name="A" id="">Abiertos</button>
                <button type="submit" class="btn btn-info m-2 w-100" name="R" id="">En Revisión</button>
                <button type="submit" class="btn btn-info m-2 w-100" name="C" id="">Cerrado</button>
                <button type="submit" class="btn btn-info m-2 w-100" name="T" id="">Todos</button>
          
               
              </div> 
          </form>
        </div> 
    

<table class="table table-striped table-hover color_blanco table-responsive table-bordered dataTable"  id="example">
  <thead>
    <tr>
    <th scope="col">#</th>
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
          $contador=1;
          if(isset($_POST['C']) ){
          $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre
          FROM ticket_soporte ts
          JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
          WHERE ts.id_user = '$id_users' and ts.stado ='C'";
          }  else if(isset($_POST['R']) ){
            $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre
          FROM ticket_soporte ts
          JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
          WHERE ts.id_user = '$id_users' and ts.stado ='R'";
          } else if(isset($_POST['T']) ){
            $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre
          FROM ticket_soporte ts
          JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
          WHERE ts.id_user = '$id_users'";
          } else if(isset($_POST['A']) ){
            $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre
          FROM ticket_soporte ts
          JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
          WHERE ts.id_user = '$id_users' and ts.stado ='A'";
          } else {
             $wsqli="SELECT ts.*, t.nombre AS tipo_soporte_nombre
          FROM ticket_soporte ts
          JOIN tipo_soporte t ON ts.id_tp_soporte = t.id
          WHERE ts.id_user = '$id_users' and ts.stado ='A'";
          }
            $result=$linki->query($wsqli);
            if($linki->errno) die($linki->error);
            while($row=$result->fetch_array()){

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
               $fecha_cerrado = $row['fecha_cerrado'];
                $anexo_r = $row['anexo_r'];
                if (!empty($anexo_r)) {
                    $anexo_r = 'Sí'; // Hay anexo
                } else {
                    $anexo_r = 'No'; // No hay anexo
                }

               
             
    ?>


  
    <tr>
      <th scope="row"> <?php echo $contador;   ?></th>
      <td> <?php echo $id;  ?> </td>
      <td> <?php echo $asunto;  ?> </td>
      <td> <?php echo  $tipo;  ?> </td>
      <td> <?php echo $anexo;  ?> </td>
      <td> <?php echo $stado;  ?> </td>
      <td> <?php echo $fecha_creado;  ?> </td>
      <td> <?php echo $fecha_revision;  ?> </td>
      <td> <?php echo $anexo_r;  ?> </td>
      <td> <?php echo  $fecha_cerrado;  ?> </td>
     
  
      <td class="text-center"> 
       <button type="submit" class ='btn btn-primary mb-2' data-bs-toggle="modal" data-bs-target="#verTicketModal-<?php echo $id;  ?>"><i class="align-middle" data-feather="search"></i></button>
    
    
      </td>
     
    </tr>
    


<?php
$contador++; 
}
   

    
?>

</tbody>
</table>


</div>
</div>

<?php
 include('modal_nuevo_ticket.php');
    include('modal_ver_ticket.php');

    ?>