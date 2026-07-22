<nav id="sidebar" class="sidebar js-sidebar">
			<div class="sidebar-content_md js-simplebar">
				<a class="sidebar-brand" href="index.php">
          <span class="align-middle"><img src="../img/logoj.png" alt=""></span>
        </a>

				<ul class="sidebar-nav">
					<li class="sidebar-header_md">
						Pages
					</li>


					<li  <?php if($pag==''){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?> >
						<a class="sidebar-link_md" href="index.php?pag=">
						
              <i class="bx bxs-home" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> <span class="align-middle">Inicio</span>
			
            </a>

			</li>
									
<li <?php if($pag=='estado_cuenta'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=estado_cuenta">
              <i class="bx bx-wallet text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
			  <span class="align-middle">Estado de Cuenta</span>
            </a>
					</li>
					</li>
									
<li <?php if($pag=='disponibilidad'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=disponibilidad">
              <i class="bx bx-search text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
			  <span class="align-middle">Consulta Disponibilidad</span>
            </a>
					</li>

				


					<li <?php if($pag=='pedido'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=pedido">
              <i class="bx bx-clipboard text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
			  <span class="align-middle">Pedidos</span>
            </a>
					</li>


					<li <?php if($pag=='factura'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=factura">
              <i class="bx bx-receipt text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
			  <span class="align-middle">Factura</span>
            </a>
					</li>

					<li <?php if($pag=='pagos'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=pagos">
							<i class="bx bx-check-shield text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
							<span class="align-middle">Recibos de Pagos</span>
						</a>
					</li>				

				<li <?php if($pag=='soporte'){echo 'class="sidebar-item activ"';} else { echo 'class="sidebar-item" ';} ?>>
                 <a class="sidebar-link_md" href="index.php?pag=soporte">

               <i class="bx bx-purchase-tag-alt text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i>
			   <span class="align-middle">Ticket Soporte</span>
        
               </a>
                </li>

				<li <?php if($pag=='backorders'){echo 'class="sidebar-item activ"';} else { echo 'class="sidebar-item" ';} ?>>
					<a class="sidebar-link_md" href="index.php?pag=backorders">

						<i class="bx bx-time-five text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i>
						<span class="align-middle">Backorders</span>
					
						</a>
                </li>

					

				</ul>

		<!--		<div class="sidebar-cta">
					<div class="sidebar-cta-content">
						<strong class="d-inline-block mb-2">Upgrade to Pro</strong>
						<div class="mb-3 text-sm">
							Are you looking for more components? Check out our premium version.
						</div>
						<div class="d-grid">
							<a href="upgrade-to-pro.html" class="btn btn-primary">Upgrade to Pro</a>
						</div>
					</div>
				</div>
			</div>-->
		</nav>