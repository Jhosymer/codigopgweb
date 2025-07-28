<?php 
    if( isset($_POST['especificacion']) ){
        include("./../../conexion.php");

        $especificacion = $_POST['especificacion'];

        $sentencia = $base_de_datos->prepare("SELECT t.tipo as tipo, f_c.clase as clase FROM filtro_codificacion as f_c
                                                    JOIN tipos as t ON t.id = f_c.id_tipo
                                                    WHERE ( f_c.clase = :especificacion ) and ( t.deleted_at is null )
                                                    GROUP BY tipo
                                                    ORDER BY tipo");
            $sentencia->bindParam(':especificacion', $especificacion, PDO::PARAM_STR);
            $sentencia->setFetchMode(PDO::FETCH_ASSOC); 
            $sentencia->execute();
            $cadena = "<option value='vacio' disabled selected>Seleccionar Tipo</option>";
            $i = 0;
            while ($row = $sentencia->fetch()) {
                $tipo = $row['tipo'];

                $cadena= $cadena . '<option value="';
                $cadena= $cadena . $tipo;
                $cadena= $cadena . '">';
                $cadena= $cadena . $tipo;
                $cadena= $cadena . '</option>';
                $i++;
            } 
            echo json_encode($cadena);
    }
?>