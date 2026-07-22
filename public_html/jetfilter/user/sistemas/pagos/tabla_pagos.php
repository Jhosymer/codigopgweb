<?php
// Contamos pagos nuevos
$check_p = $linki->query("SELECT COUNT(*) as total FROM pagos WHERE visto = 'N' AND id_users = '$id_users'");
$total_nuevos = $check_p->fetch_assoc()['total'];
$hay_nuevas = $total_nuevos > 0;

$filtro = $_GET['filtro'] ?? '';
?>
<div class='bg-white py-3 overflow-auto rounded'>
    <div class="container"> 
        <form action="index.php" method="get">
            <input type="hidden" name="pag" value="pagos">
            <div class="d-flex justify-content-lg-around my-4 align-content-center">
                <?php if ($hay_nuevas): ?>
                <button type="submit" name="filtro" value="nueva" class="btn btn-success m-2 w-100">
                    <i class='bx bxs-bell-ring'></i> Nuevos (<?php echo $total_nuevos; ?>)
                </button>
                <?php endif; ?>
                <button type="submit" name="filtro" value="revisadas" class="btn btn-info m-2 w-100 text-white">
                    <i class='bx bx-check-double'></i> Revisados
                </button>
                <button type="submit" name="filtro" value="todos" class="btn btn-secondary m-2 w-100">
                    <i class='bx bx-list-ul'></i> Todos
                </button>
            </div> 
        </form>
    </div>

    <div class="col-12 content">
        <table class="table table-hover table-bordered dataTable" id="example">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Fecha</th>
                    <th>Nro. Operación</th>
                    <th>Monto Pagado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM pagos WHERE id_users = '$id_users'";
                if ($filtro == 'nueva') $sql .= " AND visto = 'N'";
                else if ($filtro == 'revisadas') $sql .= " AND visto = 'Y'";
                $sql .= " ORDER BY id DESC";

                $res = $linki->query($sql);
                $cont = 1;
                while($row = $res->fetch_assoc()){
                    $monto = number_format($row['total_pago'], 2, ',', '.') . ' ' . $row['moneda'];
                ?>
                <tr>
                    <td><?php echo $cont++; ?></td>
                    <td><?php echo date("d/m/Y", strtotime($row['fecha_pago'])); ?></td>
                    <td><b><?php echo $row['num_pago_sap']; ?></b></td>
                    <td><?php echo $monto; ?></td>
                    <td class="text-center">
                        <a href="index.php?pag=pagos&id_ver=<?php echo $row['id'] ?>" class="btn btn-primary">
                            <i class='bx bx-search-alt'></i> Ver Recibo
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>