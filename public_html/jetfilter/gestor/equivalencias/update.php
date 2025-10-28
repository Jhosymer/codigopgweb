<?php 
    date_default_timezone_set('America/Caracas');
    session_start();
    
    if( !isset( $_POST['id'] ) ){
        header("location: ./equivalencias.php");
    }


    include_once('../../../config/conexion.php');


    if( isset( $_POST['equivalencia_marca'] ) ){

        $codigo = $_POST['codigo'];
        $marca = $_POST['marca'];
        $codigo_marca = $_POST['codigo_marca'];
        $fecha_updated = date('Y-m-d H:i:s');
        $id = $_POST['id'];
        
        $tablas = ["espec_aireautomotriz", 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado'];
        $variables_totales = [];

        foreach($tablas as $tabla){
            $marca_act = $base_de_datos->prepare("SELECT count(*) as total_registros FROM $tabla WHERE ( codigo = :codigo ) and ( deleted_at is null )");
            $marca_act->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $marca_act->setFetchMode(PDO::FETCH_ASSOC);
            $marca_act->execute();
            $num_rows = $marca_act->fetch();
            $variables_totales[$tabla] = $num_rows['total_registros'];
        }

        if( $variables_totales['espec_aireautomotriz'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_aireautomotriz WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_aireautomotriz';
        } 
        else if( $variables_totales['espec_aireindustrial'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_aireindustrial WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_aireindustrial';
        }
        else if( $variables_totales['espec_combustiblelinea'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_combustiblelinea WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_combustiblelinea';
        }
        else if( $variables_totales['espec_elemento'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_elemento WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_elemento';
        }
        else if( $variables_totales['espec_panel'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_panel WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_panel';
        }
        else if( $variables_totales['espec_sellado'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_sellado WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_sellado';
        }
        else {
            $_SESSION['codigo_inexistente'] = true;
            header("location: editar.php?id=$id");
        }
        
        try {
            $base_de_datos->beginTransaction();

            $filtro = $base_de_datos->prepare($sql);
            $filtro->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $filtro->setFetchMode(PDO::FETCH_ASSOC);
            $filtro->execute();
            $resultado = $filtro->fetch();
            $id_codigo = $resultado['id_codigo'];
            $codigo_buscar = $resultado['codigo_buscar'];
            $caracteres_a_reemplazar = ['-'," ","_"];
            $codigo_marca_buscar = str_replace($caracteres_a_reemplazar,'',$codigo_marca);

            $marca_datos = $base_de_datos->prepare("SELECT id FROM equivalencia_marca WHERE marca = :marca");
            $marca_datos->bindParam(':marca', $marca, PDO::PARAM_STR);
            $marca_datos->setFetchMode(PDO::FETCH_ASSOC);
            $marca_datos->execute();
            $resultado = $marca_datos->fetch();
            $id_marca = $resultado['id'];
            $sincronizado = date("Ymd");

            $argumentos = [$id_codigo, $codigo, $codigo_buscar, $marca, $codigo_marca, $codigo_marca_buscar, $id_marca, $sincronizado, $fecha_updated, $id];
            $sql = 'UPDATE filtro_equivalencia SET id_codigo = ?, codigo = ?, codigo_buscar = ?, marca = ?, codigo_marca = ?, codigo_marca_buscar = ?, id_marca = ?, sincronizado = ?, updated_at = ? WHERE id= ?';
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->execute($argumentos);

            include_once('./../plantillas/actualizar_sincronizados.php');

            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            header("location: ./equivalencias.php");
        }
        catch(PDOException $exception){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
            header("location: ./editar.php?id=$id");
        }
    }
?>