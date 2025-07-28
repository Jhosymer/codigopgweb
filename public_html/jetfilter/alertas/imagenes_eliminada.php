<script src="./../js/sweetAlerta.js"></script>
<?php 
    //Función de alerta al eliminar imagenes
    function alerta_imagenes_eliminada($mensaje = "La imagen ha sido eliminada"){
        if( isset( $_SESSION["imagen_eliminada"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminado',
                        text: '<?php echo $mensaje; ?>',
                        timer: 1250,
                    }) 
                </script>
            <?php
            unset( $_SESSION['imagen_eliminada'] );
        }
    }