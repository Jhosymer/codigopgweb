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

				


					<li <?php if($pag=='pedido'){echo 'class="sidebar-item activ"';} else {  echo 'class="sidebar-item" ';}  ?>>
				
						<a class="sidebar-link_md" href="index.php?pag=pedido">
              <i class="bx bx-clipboard text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i> 
			  <span class="align-middle">Pedidos</span>
            </a>
					</li>

				

				<li <?php if($pag=='soporte'){echo 'class="sidebar-item activ"';} else { echo 'class="sidebar-item" ';} ?>>
                 <a class="sidebar-link_md" href="index.php?pag=soporte">

               <i class="bx bx-purchase-tag-alt text-white" style="font-size:20px; margin-right: 8px; vertical-align: middle;"></i>
			   <span class="align-middle">Ticket Soporte</span>
        
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