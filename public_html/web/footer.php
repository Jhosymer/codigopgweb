</main>
<footer>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
           
            <a href="<?php echo $loc; ?>distribuidores/" class="btn-icon">DONDE COMPRAR</a>

           
            <a href="https://linktr.ee/webfiltros" class="redes linktree"></a>

          
            <a href="<?php echo $loc; ?>contacto/" class="btn-icon">CONTÁCTANOS</a>
        </div>

        <div class="text-center pt-3 pb-2 border-top border-secondary-subtle my-2">
            <p class="text-white small mb-0">
                © <?php echo date('Y'); ?> <strong>Jet-Filter, C.A.</strong> Todos los derechos reservados. | RIF: J-00059322-1
            </p>
        </div>
    </div>
</footer>
 

<?php
// Verificar si estamos en la página ./distribuidores/index distribuidores y estado 
// Verificar si estamos exactamente en /distribuidores/index.php
if ($current_page === '/distribuidores/' || $current_page === '/distribuidores/index.php') {
    echo '<script src="../js/estados_distribuidores.js"></script>';
}



// Verificar si estamos en la página ./manual_corporativo o simplemente en la raíz del sitio
if ($current_page === '/manual_corporativo/' || $current_page === '/manual_corporativo/index.php') {
    echo '<script src="../js/download_log.js"></script>';
}
if ($current_page === '/catalogo/' || $current_page === '/catalogo/index.php') {
    echo '<script src="../js/splide.min.js"></script>';
     echo '<script src="../js/carusel_prod_nuevos.js"></script>';
   
} 
if ($current_page === '/' || $current_page === '/index.php') {
   /* echo '<script src="./js/alerta_img.js"></script>';*/
    echo "<script src='./js/slaiderheader.js'></script>";
     echo "<script src='./js/menu_activar.js'></script>";

    
}


?>


<script src="<?php echo $loc; ?>js/JsBarcode.all.min.js"></script><!-- menumovil -->
<script src="<?php echo $loc; ?>js/menu.js"></script><!-- menumovil -->

<script src="<?php echo $loc; ?>js/sweetalert2@11.js"></script><!-- mensjes sweetalert -->
<script src="<?php echo $loc; ?>vendor/bootstrap/js/bootstrap.bundle.js"></script>
<script src="<?php echo $loc; ?>js/menu_compartir_url.js"></script><!-- menu url -->








</body>
</html>