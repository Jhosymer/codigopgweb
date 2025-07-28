<?php 
    if( isset($_POST['tipo']) ){
        include("./../../conexion.php");

        $especificacion = $_POST['especificacion'];
        $tipo = $_POST['tipo'];

        if( $especificacion == 'sellado' ){
            $sql = "SELECT e_s.diametroint FROM filtro_codificacion as f_c
                        JOIN espec_sellado as e_s ON e_s.id_codigo = f_c.id
                        WHERE ( e_s.tipo = :tipo ) and ( f_c.deleted_at is null ) and ( e_s.deleted_at is null )
                        GROUP BY e_s.diametroint
                        ORDER BY e_s.diametroint ASC";
        }

        $rosca = $base_de_datos->prepare($sql); 
        $rosca->bindParam(':tipo', $tipo, PDO::PARAM_STR); 
        $rosca->setFetchMode(PDO::FETCH_ASSOC); 
        $rosca->execute();
        $cadena = "";
        $cadena = "<option value='vacio' disabled selected>Seleccionar Rosca</option>";
        while ($row = $rosca->fetch()) {
            $diametroint = htmlspecialchars($row['diametroint']);
            $cadena = $cadena . '<option value="';
            $cadena = $cadena . $diametroint;
            $cadena = $cadena . '" >';
            $cadena = $cadena . $diametroint;
            $cadena = $cadena . '</option>';
        }

        echo json_encode($cadena);
    }
?>