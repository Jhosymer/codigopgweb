<?php

    include('./../config/conexion.php');
    function queTablaCodigo( $codigo, $base_de_datos ){
        $tablas = ['espec_aireautomotriz', 'espec_aireindustrial', 'espec_combustiblelinea', 'espec_elemento', 'espec_panel', 'espec_sellado', 'espec_fluidos', 'espec_cabina'];
        $k = 0;
        foreach( $tablas as $tabla ){
            $sql = "SELECT COUNT(*) as numero_total FROM $tabla WHERE ( codigo = :codigo ) and deleted_at is null";
            $seleccionado = $base_de_datos->prepare($sql);
            $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
            $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
            $seleccionado->execute();
            $cantidad = $seleccionado->fetch();
            $cantidad_tablas[$tabla] = $cantidad['numero_total'];
            $k++;
        }

        if( $cantidad_tablas['espec_aireautomotriz'] > 0  ){
            $resultado = 'aireautomotriz';
        }
        else if ( $cantidad_tablas['espec_aireindustrial'] > 0 ) {
            $resultado = 'aireindustrial';
        }
        else if ( $cantidad_tablas['espec_combustiblelinea'] > 0 ) {
            $resultado = 'combustiblelinea';
        }
        else if ( $cantidad_tablas['espec_elemento'] > 0 ) {
            $resultado = 'elemento';
        }
        else if ( $cantidad_tablas['espec_panel'] > 0 ) {
            $resultado = 'panel';
        }
        else if ( $cantidad_tablas['espec_sellado'] > 0 ) {
            $resultado = 'sellado';
        }
        else if ( $cantidad_tablas['espec_fluidos'] > 0 ) {
            $resultado = 'fluidos';
        }
        else if ( $cantidad_tablas['espec_cabina'] > 0 ) {
            $resultado = 'cabina';
        }

        return json_encode($resultado);
    }