<?php $wsqli_ticket = "SELECT COUNT(*) AS total 
                 FROM `ticket_soporte` 
                 WHERE id_user = '$id_users' 
                 AND visto_cliente = 'N'"; // Aquí está la clave

$total_ticket = $linki->query($wsqli_ticket)->fetch_assoc()['total'];

?>

<div class='bg-white py-4 px-3 overflow-auto rounded-4 shadow-sm mt-5 border'>
    <div class="col-12 content">
        
        <div class="mb-3">
            <button type="submit" class='btn btn-primary fw-bold shadow-sm' data-bs-toggle="modal" data-bs-target="#nuevoTicketModal">
                <i class="align-middle me-1" data-feather="plus"></i> Nuevo
            </button>
        </div>
        
        <div class="container-fluid px-0"> 
             <form action="index.php?pag=soporte" method="post">
                <div class="d-flex justify-content-lg-around my-4 align-content-center gap-2" id="btnTenRec">
                    <button type="submit" class="btn text-white fw-bold w-100 py-2 shadow-sm" name="U" style="background-color: #0984e3; border: none;">Actualizados<span class="badge bg-white text-primary ms-2"><?php echo $total_ticket; ?></span></button>
                    <button type="submit" class="btn text-white fw-bold w-100 py-2 shadow-sm" name="A" style="background-color: #1abc9c; border: none;">Abiertos</button>
                    <button type="submit" class="btn text-white fw-bold w-100 py-2 shadow-sm" name="R" style="background-color: #f39c12; border: none;">En Revisión</button>
                    <button type="submit" class="btn text-white fw-bold w-100 py-2 shadow-sm" name="C" style="background-color: #d63031; border: none;">Cerrado</button>
                    <button type="submit" class="btn text-white fw-bold w-100 py-2 shadow-sm" name="T" style="background-color: #636e72; border: none;">Todos</button>
                </div> 
            </form>
        </div> 

        <table class="table table-striped table-hover table-bordered dataTable align-middle" id="example">
            <thead >
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Ticket</th>
                    <th scope="col">Asunto</th> 
                    <th> Producto Relacionado</th>
                    <th scope="col">Soporte</th>
                    <th scope="col">Anexo</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Fecha Creado</th>
                    <th scope="col">Revisión</th>
                    <th scope="col">Anexo Rev.</th>
                    <th scope="col">Fecha Cerrado</th>
                    <th scope="col" class="text-center">Operaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $contador = 1;
                // Logica de filtrado (mantiene tu logica original)
          
                $base_query = "SELECT ts.*, t.nombre AS tipo_soporte_nombre, fc.codigo AS producto_codigo 
                            FROM ticket_soporte ts 
                            JOIN tipo_soporte t ON ts.id_tp_soporte = t.id 
                            LEFT JOIN filtro_codificacion fc ON ts.id_producto = fc.id 
                            WHERE ts.id_user = '$id_users'";

                if(isset($_POST['C'])) {
                    $wsqli = $base_query . " AND ts.stado ='C' ORDER BY ts.id DESC";
                } else if(isset($_POST['R'])) {
                    $wsqli = $base_query . " AND ts.stado ='R' ORDER BY ts.id DESC";
                } else if(isset($_POST['T'])) {
                    $wsqli = $base_query . " ORDER BY ts.id DESC";
                } else if(isset($_POST['A'])) {
                    $wsqli = $base_query . " AND ts.stado ='A' ORDER BY ts.id DESC";
                } else {
                    // CAMBIO: Ahora "Actualizados" es el estado por defecto
                    $wsqli = $base_query . " AND ts.visto_cliente = 'N' ORDER BY ts.id DESC";
                }
                $result = $linki->query($wsqli);
                while($row = $result->fetch_array()){
                    $id = $row['id'];
                    $stado_raw = $row['stado'];
                    
                    // Definición de colores según tus imágenes
                    $color_bg = "";
                    $texto_stado = "";
                    if ($stado_raw == 'A') { $color_bg = "#1abc9c"; $texto_stado = "Abierto"; }
                    elseif ($stado_raw == 'R') { $color_bg = "#f39c12"; $texto_stado = "En Revisión"; }
                    elseif ($stado_raw == 'C') { $color_bg = "#d63031"; $texto_stado = "Cerrado"; }
                ?>
                <tr>
                    <th scope="row"><?php echo $contador++; ?></th>
                    <td class="fw-bold text-primary"><?php echo $id; ?></td>
                    <td><?php echo $row['asunto']; ?></td>
                    <td class="text-center">
    <small>
        <?php if (!empty($row['producto_codigo'])): ?>
            <div class="badge bg-light text-primary border border-primary-subtle px-2 py-1" style="font-size: 0.75rem;">
                Código: <?php echo $row['producto_codigo']; ?>
            </div>
        <?php else: ?>
            &nbsp;
        <?php endif; ?>
    </small>
</td>
                    <td><small><?php echo $row['tipo_soporte_nombre']; ?></small></td>
                    <td class="text-center"><?php echo !empty($row['anexo']) ? '<span class="badge bg-primary">Sí</span>' : 'No'; ?></td>
                    <td>
                        <span class="badge w-100 py-2" style="background-color: <?php echo $color_bg; ?>; font-size: 0.9em;">
                            <?php echo $texto_stado; ?>
                        </span>
                    </td>
                    <td><small><?php echo $row['fecha_creado']; ?></small></td>
                    <td><small><?php echo $row['fecha_revision']; ?></small></td>
                    <td class="text-center"><?php echo !empty($row['anexo_r']) ? '<span class="badge bg-success">Sí</span>' : 'No'; ?></td>
                    <td><small><?php echo $row['fecha_cerrado']; ?></small></td>
                    <td class="text-center"> 
                        <button type="button" class='btn btn-outline-primary btn-sm rounded-circle' data-bs-toggle="modal" data-bs-target="#verTicketModal-<?php echo $id; ?>">
                            <i class="align-middle" data-feather="search"></i>
                        </button>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('modal_nuevo_ticket.php');
include('modal_ver_ticket.php');
?>