<?php 
    /* 
        FUNCIÓN QUE RETORNA EL NÚMERO DE FILTROS NO ELIMINADOS QUE TIENE UN CÓDIGO
        Parametro 1 $codigo: El código a evaluar
        Parametro 2 $base_de_datos: La variable que permita conectar a la base de datos.
        Parametro 3 $id: En caso de que se quiera hacer una excepción, se puede colocar un tercer parametro
            que es el id del filtro a no tomar en cuenta. (Sirve cuando vas a editar y quieres verificar si 
            existen algún filtro con ese código, menos el que se va a editar).
    */
    function comprobarExistenciaFiltro($codigo, $base_de_datos, $id = null){
        $sql = "SELECT COUNT(*) as total FROM filtro_codificacion 
                    WHERE ( codigo = :codigo ) and ( deleted_at is null )";
        //Si el id existe se agrga una condición más al where, verificando que no tenga ese id
        if( $id != null ){ 
            $sql = $sql . " and ( id != :id )";
        }
        $numeroFiltro = $base_de_datos->prepare($sql);
        $numeroFiltro->bindParam(':codigo', $codigo, PDO::PARAM_STR);
        if( $id != null ){
            $numeroFiltro->bindParam(':id', $id, PDO::PARAM_INT);
        }
        $numeroFiltro->setFetchMode(PDO::FETCH_ASSOC);
        $numeroFiltro->execute();
        $numeroFiltro = $numeroFiltro->fetch();
        return $numeroFiltro['total']; //Se retorna el número de filtros con ese código

//return 0;
    }
?>