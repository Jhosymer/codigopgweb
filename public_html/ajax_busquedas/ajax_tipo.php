<?php 
if( isset($_POST['tipo']) ){
    include("./../config/conexion.php");

    $especificacion = $_POST['especificacion'];
    $tipo = $_POST['tipo'];
    $sql = ""; 

    if( $especificacion == 'sellado' ){
        $sql = "SELECT r.id, r.codigo 
                FROM espec_sellado as e_s
                LEFT JOIN roscas as r ON e_s.id_rosca = r.id
                JOIN filtro_codificacion as f_c ON e_s.id_codigo = f_c.id
                WHERE ( e_s.tipo = :tipo ) 
                AND ( f_c.deleted_at IS NULL ) 
                AND ( e_s.deleted_at IS NULL )
                GROUP BY r.id, r.codigo, r.valor_nominal
                ORDER BY r.valor_nominal ASC";
    }

    if ($sql != "") {
        $rosca = $base_de_datos->prepare($sql); 
        $rosca->bindParam(':tipo', $tipo, PDO::PARAM_STR); 
        $rosca->execute();

        //  inicializamos el selec por defecto
        $cadena = "<option value='' disabled selected>Seleccionar Rosca</option>";
        
        $tiene_null = false;
        $opciones_dinamicas = "";

        while ($row = $rosca->fetch(PDO::FETCH_ASSOC)) {
            if (empty($row['id'])) {
                // Si encontramos un ID nulo, marcamos la bandera
                $tiene_null = true;
            } else {
                //  Guardamos las opciones con ID en una variable temporal
                $opciones_dinamicas .= '<option value="' . $row['id'] . '">' . htmlspecialchars($row['codigo']) . '</option>';
            }
        }

        // Si ID  es nulo , añadimos la opción especial al principio
        if ($tiene_null) {
            $cadena .= "<option value='null'>Sin Rosca / ø int (mm)</option>";
        }

        // Concatenamos el resto de las roscas encontradas
        $cadena .= $opciones_dinamicas;

        echo json_encode($cadena);
    } else {
        echo json_encode("<option value=''>N/A</option>");
    }
}
?>