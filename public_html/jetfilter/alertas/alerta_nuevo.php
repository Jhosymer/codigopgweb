<script src="./../js/sweetAlerta.js"></script>
<?php
    //Alerta de Registro ha sido agregado
    function alerta_nuevo( $mensaje = "El registro ha sido agregado" ){
        if( isset( $_SESSION["nuevo"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: '<?php echo $mensaje; ?>',
                        timer: 1750,
                    })
                </script>
            <?php
            unset( $_SESSION['nuevo'] );
        }
    }