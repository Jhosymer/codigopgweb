<?php 
    if( isset($_POST['id']) ){
        include("./../../conexion.php");

        $id_aplicacion = isset( $_POST['id'] ) ? $_POST['id'] : null;
        $id_marca = isset( $_POST['id_marca'] ) ? $_POST['id_marca'] : null;

        if($id_marca == null){
            $sentencia = $base_de_datos->prepare("SELECT a.id_marca, a_m.marca 
                                                                FROM aplicacion as a 
                                                                JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
                                                                WHERE id_tipo = :aplicacion and (a.deleted_at is null) and (a_m.deleted_at is null)
                                                                GROUP BY a.id_marca
                                                                ORDER BY a_m.marca");
            $sentencia->bindParam(':aplicacion', $id_aplicacion, PDO::PARAM_INT);													
            $sentencia->setFetchMode(PDO::FETCH_ASSOC); 
            $sentencia->execute();
            $cadena = "<option value= 0' disabled selected>Seleccionar Marca</option>";
            while ($row = $sentencia->fetch()) {
                $cadena= $cadena . '<option value=';
                $cadena= $cadena . $row['id_marca'];
                $cadena= $cadena . '>';
                $cadena= $cadena . $row['marca'];
                $cadena= $cadena . '</option>';
            } 
            echo $cadena;
        }
        else {
            $sentencia = $base_de_datos->prepare("SELECT a.id_marca, a_m.marca 
                                                                FROM aplicacion as a 
                                                                JOIN aplicacion_marca as a_m ON a.id_marca = a_m.id 
                                                                WHERE id_tipo = :aplicacion and (a.deleted_at is null) and (a_m.deleted_at is null)
                                                                GROUP BY a.id_marca
                                                                ORDER BY a_m.marca");
            $sentencia->bindParam(':aplicacion', $id_aplicacion, PDO::PARAM_INT);													
            $sentencia->setFetchMode(PDO::FETCH_ASSOC); 
            $sentencia->execute();
            $cadena = "<option value= 0' disabled>Seleccionar Marca</option>";
            while ($row = $sentencia->fetch()) {
                if($row['id_marca'] == $id_marca){
                    $cadena= $cadena . '<option value=';
                    $cadena= $cadena . $row['id_marca'];
                    $cadena= $cadena . ' selected>';
                    $cadena= $cadena . $row['marca'];
                    $cadena= $cadena . '</option>';
                }
                else {
                    $cadena= $cadena . '<option value=';
                    $cadena= $cadena . $row['id_marca'];
                    $cadena= $cadena . '>';
                    $cadena= $cadena . $row['marca'];
                    $cadena= $cadena . '</option>';
                }
            } 
            echo $cadena;
        }
    }
?>