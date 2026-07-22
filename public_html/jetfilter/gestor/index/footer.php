</main>
<footer>
</footer>
<script src="<?php echo $loc; ?>js/menu.js?t=<?php echo $rann?>"></script> <?php
/*<script src="<?php echo $loc; ?>js/js_vende/jquery-3.7.1.js"></script>*/ ?>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.js"></script>
<script src="<?php echo $loc; ?>js/js_vende/dataTables.bootstrap5.js"></script>

<?php

 if ($current_page === '/jetfilter/gestor/especificaciones.php') {
    echo ' <script type="text/javascript" src="./js/main.js?t=<?php echo $rann?>"></script><!-- /.js zoom imagen catalogo -->
<script src="./js/archivo.js?t=<?php echo $rann?>"></script><!-- /.archivo --> 
<script src="./js/eliminar_producto.js?t=<?php echo $rann?>"></script>
<script src="./../../vendor/bootstrap/js/bootstrap.bundle.js?t=<?php echo $rann?>"></script>
';
} else if (strpos($current_page, '/jetfilter/gestor/distribuidoras_venezuela/detalle/') !== false || 
         strpos($current_page, '/jetfilter/gestor/distribuidoras_venezuela/estado/') !== false) {
    // Esto se ejecutará para cualquier archivo dentro de la carpeta detalle

    echo '<script type="text/javascript" src="./../../js/main.js?t=<?php echo $rann?>"></script><!-- /.js zoom imagen catalogo -->
               <script src="./../../js/archivo.js?t=<?php echo $rann?>"></script><!-- /.archivo -->
               <script src="./../../js/eliminar_producto.js?t=<?php echo $rann?>"></script>
               <script src="./../../../../vendor/bootstrap/js/bootstrap.bundle.js?t=<?php echo $rann?>"></script>';
} else  if ($current_page === '/jetfilter/gestor/backorders/' || $current_page ==='/jetfilter/gestor/backorders/index.php' ) {
echo '<script src="./../../../js/js_vende/menutables.js?t=<?php echo $rann?>"></script>';
}
else {
     echo ' <script type="text/javascript" src="./../js/main.js?t=<?php echo $rann?>"></script><!-- /.js zoom imagen catalogo -->

<script src="./../js/archivo.js?t=<?php echo $rann?>"></script><!-- /.archivo -->

       
<script src="./../js/eliminar_producto.js?t=<?php echo $rann?>"></script>
<script src="./../../../vendor/bootstrap/js/bootstrap.bundle.js?t=<?php echo $rann?>"></script>';
}

?>


</body>
</html>