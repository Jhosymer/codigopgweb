<?php
// 1. Conteo de nuevas
$stmt_check = $base_de_datos->prepare("SELECT COUNT(*) as total FROM factura WHERE visto = 'N'");
$stmt_check->execute();
$res_check = $stmt_check->fetch(PDO::FETCH_ASSOC);
$total_nuevas = $res_check['total'];
$hay_nuevas = $total_nuevas > 0;

$filtro = $_GET['filtro'] ?? '';
?>

<div class='bg-white py-3 overflow-auto rounded'>
    <?php if ($hay_nuevas || !empty($filtro)): ?>
    <div class="container"> 
        <form action="index.php" method="get">
            <input type="hidden" name="pag" value="factura">
            <div class="d-flex flex-wrap justify-content-md-around my-4">
                <?php if ($hay_nuevas): ?>
                <button type="submit" name="filtro" value="nueva" class="btn btn-primary m-2 flex-grow-1">
                    <i class='bx bxs-bell-ring'></i> Nuevas (<?php echo $total_nuevas; ?>)
                </button>
                <?php endif; ?>
                <button type="submit" name="filtro" value="revisadas" class="btn btn-info m-2 flex-grow-1 text-white">
                    <i class='bx bx-check-double'></i> Revisadas
                </button>
                <button type="submit" name="filtro" value="todos" class="btn btn-secondary m-2 flex-grow-1">
                    <i class='bx bx-list-ul'></i> Todas
                </button>
            </div> 
        </form>
    </div>
    <?php endif; ?>

    <div class="col-12 content px-3">
        <table class="table table-striped table-hover table-bordered dataTable" id="example">
            <thead class="table-light">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Usuario</th> 
                    <th scope="col">RIF</th> 
                    <th scope="col">Nro. Factura</th>
                    <th scope="col">Fecha</th>
                    <th scope="col">Total</th>
                    <th scope="col">Nota de Crédito</th>
                    <th scope="col">Estado</th> <th scope="col">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $base_sql = "SELECT f.*, u.rif, u.name 
                             FROM factura f 
                             INNER JOIN users u ON f.id_users = u.id";

                if ($filtro == 'nueva') {
                    $sql = "$base_sql WHERE f.visto = 'N' ORDER BY f.id DESC";
                } else if ($filtro == 'revisadas') {
                    $sql = "$base_sql WHERE f.visto = 'Y' ORDER BY f.id DESC";
                } else if ($filtro == 'todos') {
                    $sql = "$base_sql ORDER BY f.id DESC";
                } else {
                    $sql = ($hay_nuevas) ? "$base_sql WHERE f.visto = 'N' ORDER BY f.id DESC" : "$base_sql ORDER BY f.id DESC";
                }

                $stmt = $base_de_datos->prepare($sql);
                $stmt->execute();
                
                $contador = 1;
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $total_factura = number_format($row['total_fact'], 2, ',', '.') . '$';
                    $modalID = "modalFactura" . $row['id'];
                ?>
                <tr>
                    <th scope="row"><?php echo $contador; ?></th>
                    <td><?php echo $row['name']; ?></td> 
                    <td><?php echo $row['rif'] ?? $row['id_users']; ?></td>
                    <td><?php echo $row['num_fact']; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['fecha_contab'])); ?></td>
                    <td class="fw-bold"><?php echo $total_factura; ?></td>
                                       <td>
    <?php echo $row['nota_credito'] ?? ''; ?>
</td>
                    
                    <td>
                        <?php if ($row['visto'] == 'N'): ?>
                            <span class="badge rounded-pill bg-warning text-whiter">
                                <i class='bx bxs-circle me-1 text-dark' ></i> Sin leer
                            </span>
                        <?php else: ?>
                            <span class="badge rounded-pill bg-success text-whiter">
                                <i class='bx bx-check-double me-1'></i> Revisada
                            </span>
                        <?php endif; ?>
                    </td>

                    <td class="text-center"> 
                        <button type="button" class="btn btn-primary text-white" data-bs-toggle="modal" data-bs-target="#<?php echo $modalID; ?>">
                            <i class='bx bx-edit'></i> Editar
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
    </tr>
  </tfoot>
        </table>
    </div>
</div>



<?php include ('modal_factura.php'); ?>