<?php 
    date_default_timezone_set('America/Caracas');

    //Si existe el id, se puede actualizar, sino te redirigirá
    if( isset($_POST['id']) ){
        session_start();
        include_once('../../../config/conexion.php');

        $id = $_POST['id'];

        //Se llenan los datos del formulario en variables
        $codigo = $_POST['codigo'];
        $marca = $_POST['marca'];
        $vehiculo = $_POST['vehiculo'];
        $aplicacion = $_POST['aplicacion'];
        $detalle = $_POST['detalle'] == '' ? 'N/D' : $_POST['detalle'];
        $sincronizado = date("Ymd");
        $fecha_updated = date('Y-m-d H:i:s');

        //Tablas Permitidas
        $tablas = ["espec_aireautomotriz", 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado', 'espec_cabina'];
        $variables_totales = [];

        //COMPROBACIÓN DE EXISTENCIA DEL FILTRO EN UNA TABLA (No incluida la tabla de fluidos)
        //Revisará tabla por tabla
        foreach($tablas as $tabla){
            $sql = "SELECT count(*) as total_registros FROM $tabla WHERE ( codigo = :codigo ) and ( deleted_at is null )";
            $marca_act = $base_de_datos->prepare($sql);
            $marca_act->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $marca_act->setFetchMode(PDO::FETCH_ASSOC);
            $marca_act->execute();
            $num_rows = $marca_act->fetch();
            $variables_totales[$tabla] = $num_rows['total_registros'];
        }

        //En la variable $numeroFiltrosPorTabla se guarda la cantidad de filtros con el código,
        //Se comprobará en cada tabla si hay algún registro. En caso de haber guardará el codigo SQL
        //Para la consulta que busque el id_codigo
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
        //En caso de no existir coincidencia, redirigirá y mostrará una alerta
        else {
            $_SESSION['codigo_inexistente'] = true;
            header("location: editar.php?id=$id") or die('Error');
        }

        //Empezará a actualizar los datos de la aplicación
        try {
            //A partir de este momento los cambios no se guaradan directamente en la base de datos
            $base_de_datos->beginTransaction();

            //Seleccionar ID_Codigo
            $filtro = $base_de_datos->prepare($sql);
            $filtro->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $filtro->setFetchMode(PDO::FETCH_ASSOC);
            $filtro->execute();
            $resultado = $filtro->fetch();
            $id_codigo = $resultado['id_codigo'];

            //Actualizar Aplicacion
            $argumentos = [$marca, $vehiculo, $aplicacion, $id_codigo, $codigo, $detalle, $sincronizado, $fecha_updated, $id];
            $seleccionado = $base_de_datos->prepare("UPDATE aplicacion SET id_marca = ?, id_vehiculo = ?, aplicacion = ?, id_codigo = ?, codigo = ?, detalle = ?, sincronizado = ?, updated_at = ? WHERE id= ? ");
            $seleccionado->execute($argumentos);

            //Este componente actualizara los sincronizado de todos los registros
            include_once('./../plantillas/actualizar_sincronizados.php');
               
            //Instrucción que mandara todos los cambios a la base de datos. 
            //Hasta que no se ejecute, los cambios no se veran
            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            header("location: ./aplicacion_comercial.php");
        }
        //En caso de suceder algún error en el proceso de subida de datos.
        catch(Exception $e){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
            header("location: ./editar.php");
        }
    }
    //Si no existe el id, te redirigirá al inicio
    else {
        header("location: ./aplicacion_comercial.php");
    }
?>