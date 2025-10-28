<?php 
    date_default_timezone_set('America/Caracas');
    if( isset($_POST['id']) ){
        try {
            $url_base_datos = '../../../config/conexion.php';
            if ( !file_exists( $url_base_datos ) ){
                header("location: aplicacion_fueracaterretera.php?errorBase=true");
            }
            else {
                session_start();
                include_once($url_base_datos);
                $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos, $usuario, $contraseña);
                $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
        }
        catch(PDOException $e){
            echo "
                <script>
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Hubo un problema con la base de datos',
                    timer: 1250,
                })
                </script>
            ";
        }

        $id = $_POST['id'];

        $codigo = $_POST['codigo'];
        $marca = $_POST['marca'];
        $vehiculo = $_POST['vehiculo'];
        $aplicacion = $_POST['aplicacion'];
        $detalle = $_POST['detalle'] == '' ? 'N/D' : $_POST['detalle'];
        $sincronizado = date("Ymd");
        $fecha_updated = date('Y-m-d H:i:s');

        //Tablas
        $tablas = ["espec_aireautomotriz", 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado', 'espec_cabina'];
        $variables_totales = [];

        foreach($tablas as $tabla){
            $sql = "SELECT count(*) as total_registros FROM $tabla WHERE ( codigo = :codigo ) and ( deleted_at is null )";
            $marca_act = $base_de_datos->prepare($sql);
            $marca_act->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $marca_act->setFetchMode(PDO::FETCH_ASSOC);
            $marca_act->execute();
            $num_rows = $marca_act->fetch();
            $variables_totales[$tabla] = $num_rows['total_registros'];
        }

        if( $variables_totales['espec_aireautomotriz'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_aireautomotriz WHERE codigo = :codigo";
            
            $tabla = 'espec_aireautomotriz';
        } 
        else if( $variables_totales['espec_aireindustrial'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_aireindustrial WHERE codigo = :codigo";
            
            $tabla = 'espec_aireindustrial';
        }
        else if( $variables_totales['espec_combustiblelinea'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_combustiblelinea WHERE codigo = :codigo";

            $tabla = 'espec_combustiblelinea';
        }
        else if( $variables_totales['espec_elemento'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_elemento WHERE codigo = :codigo";

            $tabla = 'espec_elemento';
        }
        else if( $variables_totales['espec_panel'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_panel WHERE codigo = :codigo";

            $tabla = 'espec_panel';
        }
        else if( $variables_totales['espec_cabina'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_cabina WHERE codigo = :codigo";

            $tabla = 'espec_cabina';
        }
        else if( $variables_totales['espec_sellado'] > 0 ){
            $sql = "SELECT id_codigo FROM espec_sellado WHERE codigo = :codigo";

            $tabla = 'espec_sellado';
        }
        else {
            $_SESSION['codigo_inexistente'] = true;
            header("location: editar.php?id=$id") or die('Error');
        }

        try {
            $base_de_datos->beginTransaction();

            $filtro = $base_de_datos->prepare($sql);
            $filtro->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $filtro->setFetchMode(PDO::FETCH_ASSOC);
            $filtro->execute();
            $resultado = $filtro->fetch();
            $id_codigo = $resultado['id_codigo'];

            $argumentos = [$marca, $vehiculo, $aplicacion, $id_codigo, $codigo, $detalle, $sincronizado, $fecha_updated, $id];
            $seleccionado = $base_de_datos->prepare("UPDATE aplicacion SET id_marca = ?, id_vehiculo = ?, aplicacion = ?, id_codigo = ?, codigo = ?, detalle = ?, sincronizado = ?, updated_at = ? WHERE id= ? ");
            $seleccionado->execute($argumentos);

            include_once('./../plantillas/actualizar_sincronizados.php');
            
            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            header("location: ./aplicacion_fueracarretera.php");
        }
        catch(Exception $e){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
            header("location: ./editar.php");
        }
    }
    else {
        header("location: ./aplicacion_fueracaterretera.php");
    }
?>