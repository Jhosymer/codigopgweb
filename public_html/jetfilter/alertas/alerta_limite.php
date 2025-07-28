<script src="./../js/sweetAlerta.js"></script>
<?php 
    //Función de Alerta de limite, verificará si existe la variable de sessión y mostrará la alerta, 
    //al final eliminará la variable de session
    //Se utilizará cuando en distribuidoras se supere el limite de 4 distribuidoras con calificación 6
    function alerta_lmite($mensaje = "Está en el limite de distribuidoras con calificación 6"){
        if( isset( $_SESSION["limite"] ) )
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia!',
                        text: '<?php echo $mensaje; ?>',
                        timer: 2250,
                    }) 
                </script>
            <?php
            unset( $_SESSION['limite'] );
        }
    }
    