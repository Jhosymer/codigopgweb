<?php 
    include_once('../../../config/conexion.php');
    session_start();

    if( isset( $_POST['equivalencia_nueva'] ) ){
        $codigo = $_POST['codigo'];
        $marca = $_POST['marca'];
        $codigo_marca = $_POST['codigo_marca'];
        
        $tablas = ["espec_aireautomotriz", 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado' , 'espec_cabina'];
        $variables_totales = [];

        foreach($tablas as $tabla){
            $marca_act = $base_de_datos->prepare("SELECT count(*) as total_registros FROM $tabla WHERE ( codigo = :codigo ) and ( deleted_at is null)");
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
        else if( $variables_totales['espec_cabina'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_cabina WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_cabina';
        }
        else if( $variables_totales['espec_sellado'] > 0 ){
            $sql = "SELECT id_codigo, codigo_buscar FROM espec_sellado WHERE ( codigo = :codigo ) and ( deleted_at is null )";

            $tabla = 'espec_sellado';
        }
        else {
            $_SESSION['codigo_inexistente'] = true;
            header("location: ./nuevo.php") or die('Error');
        }

        try {
            $base_de_datos->beginTransaction();

            $filtro = $base_de_datos->prepare($sql);
            $filtro->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $filtro->execute();
            $resultado = $filtro->fetch(PDO::FETCH_ASSOC);
            $id_codigo = $resultado['id_codigo'];
            $codigo_buscar = $resultado['codigo_buscar'];
            $caracteres_a_reemplazar = ['-'," ","_"];
            $codigo_marca_buscar = str_replace($caracteres_a_reemplazar,'',$codigo_marca);

            $marca_datos = $base_de_datos->prepare("SELECT id FROM equivalencia_marca WHERE ( marca = :marca ) and ( deleted_at is null ) ");
            $marca_datos->bindParam(':marca', $marca, PDO::PARAM_STR);
            $marca_datos->execute();
            $resultado = $marca_datos->fetch(PDO::FETCH_ASSOC);
            $id_marca = $resultado['id'];
            $sincronizado = date('Ymd');
                
            $argumentos = [$id_codigo, $codigo, $codigo_buscar, $marca, $codigo_marca, $codigo_marca_buscar, $id_marca, $sincronizado];
            $sql = "INSERT INTO filtro_equivalencia (id_codigo, codigo, codigo_buscar, marca, codigo_marca, codigo_marca_buscar, id_marca, sincronizado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $insertar_equivalencia = $base_de_datos->prepare($sql);
            $insertar_equivalencia->execute( $argumentos );

            include_once('./../plantillas/actualizar_sincronizados.php');

            $base_de_datos->commit();

            $_SESSION['nuevo'] = "true";
            header("location: ./equivalencias.php");
        }
        catch(Exception $e){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
            header("location: ./nuevo.php");
        }
    }
?>