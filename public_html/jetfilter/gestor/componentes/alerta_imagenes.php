<script src="./../js/sweetAlerta.js"></script>
<?php 
    function alerta_actualizado_imagenes($mensaje = "La información se ha actualizado"){
        if( isset( $_SESSION["actualizado_imagenes"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '¡Actualizado!',
                        text: '<?php echo $mensaje; ?>',
                        timer: 1250,
                    }) 
                </script>
            <?php
            unset( $_SESSION['actualizado_imagenes'] );
        }
    }