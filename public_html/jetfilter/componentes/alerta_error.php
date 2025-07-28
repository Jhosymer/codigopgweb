<script src="./../js/sweetAlerta.js"></script>
<?php 
    function alerta_error($mensaje = "No se pudo subir el registro"){
        if( isset( $_SESSION["error"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '<?php echo $mensaje; ?>',
                        timer: 1250,
                    })
                </script>
            <?php
            unset( $_SESSION['error'] );
        }
    }