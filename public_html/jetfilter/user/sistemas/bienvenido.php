<?php
// --- CONSULTAS SQL (Mantenemos tu lógica funcional) ---
$wsqli = "SELECT COUNT(*) AS total FROM `pedidos` WHERE id_users= '$id_users' AND (na_pedido = '' OR na_pedido IS NULL)";
$total_pedidos = $linki->query($wsqli)->fetch_assoc()['total'];

$wsqli_ticket = "SELECT COUNT(*) AS total 
                 FROM `ticket_soporte` 
                 WHERE id_user = '$id_users' 
                 AND visto_cliente = 'N'"; // Aquí está la clave

$total_ticket = $linki->query($wsqli_ticket)->fetch_assoc()['total'];

$wsqli_factura = "SELECT COUNT(*) AS total FROM `factura` WHERE visto = 'N' AND id_users = '$id_users'";
$total_factura = $linki->query($wsqli_factura)->fetch_assoc()['total'];

$sql_user = "SELECT name, saldo, limitecredito,  vip  FROM users WHERE id = '$id_users'"; // Traemos el nombre completo
$row_user = $linki->query($sql_user)->fetch_assoc();
$nombre_usuario = $row_user['name'] ?? $_SESSION['name'];
$saldo_valor = $row_user['saldo'] ?? 0;
$limite_credito = $row_user['limitecredito'] ?? 0; 
$es_vip         = $row_user['vip'] ?? 'N';

$alerta_limite = false;
// La lógica está bien: si el saldo es positivo y supera al límite, alertamos
if ($saldo_valor > 0 && $limite_credito > 0 && $saldo_valor > $limite_credito) {
    $alerta_limite = true;
}

$wsqli_pagos = "SELECT COUNT(*) AS total FROM `pagos` WHERE id_users = '$id_users' and  Visto = 'N'";
$total_pagos = $linki->query($wsqli_pagos)->fetch_assoc()['total'];

// --- LÓGICA DE COLORES DE SALDO ---
if ($saldo_valor > 0) {
    $txt_estado = "Saldo Deudor";
    $clase_color = "text-danger border-danger";
    $bg_opacity = "rgba(239, 68, 68, 0.1)"; // bg-danger soft
    $icono_anim = "bx-tada"; 
} elseif ($saldo_valor < 0) {
    $txt_estado = "Saldo a Favor";
    $clase_color = "text-success border-success";
    $bg_opacity = "rgba(16, 185, 129, 0.1)"; // bg-success soft
    $icono_anim = "";
} else {
    $txt_estado = "Saldo al Día";
    $clase_color = "text-muted border-secondary";
    $bg_opacity = "rgba(100, 116, 139, 0.1)"; // bg-secondary soft
    $icono_anim = "";
}

$saldo_limpio = abs($saldo_valor);
?>
<style>
   
   
</style>

<div class="dashboard-container py-3">
    
    <div class="row align-items-center mb-5">
        <div class="col-lg-7 col-md-12">
            <h1 class="text-title mb-1">¡Bienvenidos, <?php echo $nombre_usuario; ?>!</h1>
            <p class="text-muted fs-5">Este es el resumen del estado de tu cuenta.</p>
        </div>
        <div class="col-lg-5 col-md-12 d-flex justify-content-lg-end mt-3 mt-lg-0 saldo-card">
            <a href="index.php?pag=estado_cuenta" 
            class="text-decoration-none shadow-hover-mora" 
            style="width: auto;"
            data-bs-toggle="tooltip" 
            data-bs-custom-class="tooltip-info" 
            title="Haz clic para ver tu Estado de Cuenta">
            
                <div class="saldo-card-premium <?php echo $clase_color; ?> d-flex flex-column" 
                    style="cursor: pointer; padding: 1rem; border-radius: 12px; transition: all 0.3s ease;">
                    
                    <div class="d-flex align-items-center">
                        <div class="icon-circle me-3" style="background-color: <?php echo $bg_opacity; ?>; min-width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; border-radius: 10px;">
                            <i class='bx bx-wallet fs-3 <?php echo $icono_anim; ?>'></i>
                        </div>
                        
                        <div class="d-flex flex-column">
                            <small class="text-uppercase fw-bold text-muted mb-0" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <?php echo $txt_estado; ?>
                            </small>
                            
                            <span class="fs-4 fw-bold text-dark lh-1"><?php echo number_format($saldo_limpio, 2, ',', '.'); ?>$</span>
                            
                            <?php if ($es_vip === 'N' && $limite_credito > 0): ?>
                            <small class="fw-semibold mt-1" style="font-size: 0.7rem;">
                                <span class="<?php echo ($alerta_limite) ? 'text-danger fw-bold' : 'text-success'; ?>">
                                    Límite de Crédito:<?php echo number_format($limite_credito, 2, ',', '.'); ?>$
                                    <?php if ($alerta_limite): ?>
                                        <i class='bx bx-error-circle'></i>
                                    <?php endif; ?>
                                </span>
                            </small>
                            <?php endif; ?>
                        </div>

                        <div class="ms-auto">
                            <i class='bx bx-chevron-right fs-2' style="color: currentColor;"></i>
                        </div>
                    </div>

                    <div class="w-100 text-primary fw-bold mt-2 pt-2 border-top" style="font-size: 0.65rem; text-align: center;">
                        Ver estado de cuenta
                    </div>
                </div>
            </a>
        </div>

    <div class="row g-4">
         <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icon-shape-box bg-primary bg-opacity-10 text-primary">
                            <i class='bx bx-clipboard fs-3'></i>
                        </div>
                        <span class="badge badge-pill-custom bg-primary"><?php echo $total_pedidos; ?> Activos</span>
                    </div>
                    <h6 class="text-muted text-uppercase fw-bold small">Pedidos</h6>
                    <h3 class="fw-bold text-title"><?php echo $total_pedidos; ?> <span class="fs-6 fw-normal text-muted">Pedidos en curso</span></h3>
                    <hr class="my-4 opacity-10">
                    <a href="index.php?pag=pedido" class="text-decoration-none text-primary nav-link-custom d-flex align-items-center">
                        VER LISTADO COMPLETO <i class='bx bx-right-arrow-alt fs-4 ms-1'></i>
                    </a>
                </div>
            </div>
        </div>

       <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icon-shape-box bg-info bg-opacity-10 text-info">
                            <i class='bx bx-receipt fs-3'></i>
                        </div>
                        <span class="badge badge-pill-custom bg-info text-white"><?php echo $total_factura; ?> Nuevas</span>
                    </div>
                    <h6 class="text-muted text-uppercase fw-bold small">Facturas</h6>
                    <h3 class="fw-bold text-title"><?php echo $total_factura; ?> <span class="fs-6 fw-normal text-muted">Nueva(s) Factura(s)</span></h3>
                    <hr class="my-4 opacity-10">
                    <a href="index.php?pag=factura" class="text-decoration-none text-info nav-link-custom d-flex align-items-center">
                        VER MIS FACTURAS <i class='bx bx-right-arrow-alt fs-4 ms-1'></i>
                    </a>
                </div>
            </div>
        </div>

         <div class="col-xl-3 col-md-6">
        <div class="card h-100 dashboard-card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="icon-shape-box bg-success bg-opacity-10 text-success">
                        <i class='bx bx-check-shield fs-3'></i>
                    </div>
                    <span class="badge badge-pill-custom bg-success text-white"><?php echo $total_pagos; ?> Nuevos</span>
                </div>
                <h6 class="text-muted text-uppercase fw-bold small">Recibos de Pagos </h6>
                <h3 class="fw-bold text-title"><?php echo $total_pagos; ?> <span class="fs-6 fw-normal text-muted">Nuevos Recibos de Pagos</span></h3>
                <hr class="my-4 opacity-10">
                <a href="index.php?pag=pagos" class="text-decoration-none text-success nav-link-custom d-flex align-items-center">
                     VER MI HISTORIAL <i class='bx bx-right-arrow-alt fs-4 ms-1'></i>
                </a>
            </div>
        </div>
    </div>


        <div class="col-xl-3 col-md-6">
            <div class="card h-100 dashboard-card shadow-sm border-0">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="icon-shape-box bg-warning bg-opacity-10 text-warning">
                            <i class='bx bx-support fs-3'></i>
                        </div>
                        <span class="badge badge-pill-custom bg-warning text-dark"><?php echo $total_ticket; ?> Actualizados</span>
                    </div>
                    <h6 class="text-muted text-uppercase fw-bold small">Ticket Soporte</h6>
                    <h3 class="fw-bold text-title"><?php echo $total_ticket; ?> <span class="fs-6 fw-normal text-muted">Casos Actualizados</span></h3>
                    <hr class="my-4 opacity-10">
                    <a href="index.php?pag=soporte" class="text-decoration-none text-warning nav-link-custom d-flex align-items-center" style="color: #d97706 !important;">
                        GESTIONAR SOPORTE <i class='bx bx-right-arrow-alt fs-4 ms-1'></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

  <div class="row g-4 mt-2">
    <div class="col-md-6">
        <a href="index.php?pag=backorders" class="card dashboard-card shadow-sm text-decoration-none p-2 border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon-shape-box bg-danger bg-opacity-10 text-danger me-3">
                    <i class='bx bx-time-five fs-3'></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Consultar Backorders</h6>
                    <small class="text-muted">Productos pendientes de despacho.</small>
                </div>
                <i class='bx bx-chevron-right ms-auto fs-3 text-muted'></i>
            </div>
        </a>
    </div>

    <div class="col-md-6">
        <a href="index.php?pag=disponibilidad" class="card dashboard-card shadow-sm text-decoration-none p-2 border-0">
            <div class="card-body d-flex align-items-center">
                <div class="icon-shape-box bg-success bg-opacity-10 text-success me-3">
                    <i class='bx bx-search-alt fs-3'></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark">Consulta Disponibilidad</h6>
                    <small class="text-muted">Consulta stock en tiempo real.</small>
                </div>
                <i class='bx bx-chevron-right ms-auto fs-3 text-muted'></i>
            </div>
        </a>
    </div>
</div>