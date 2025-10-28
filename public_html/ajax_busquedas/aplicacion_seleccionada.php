<?php 
    if( isset($_POST['id_tipo']) ){
        include("./../config/conexion.php");
        include('./../funciones/codigo_existe_en_especificaciones.php');

        $id_tipo = $_POST['id_tipo'];
        $id_vehiculo = $_POST['id_vehiculo'];
        $id_marca = $_POST['id_marca'];
        $cadena = "";

        $sql = "SELECT modelo, motor, ano, cilindrada FROM aplicacion_vehiculo
                        WHERE ( id = :id_vehiculo ) and (deleted_at is null)";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":id_vehiculo", $id_vehiculo, PDO::PARAM_INT);
		$seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
		$seleccionado->execute();

        $row = $seleccionado->fetch();
        $modelo = $row['modelo'];
        $motor = $row['motor'];
        $año = $row['ano'];
        $cilindrada = $row['cilindrada'];
        $cadena = $cadena . '<table class="table table-borderless table-custom table-equivalencias">';
        $cadena = $cadena . '<thead class="text-uppercase">';
        $cadena = $cadena . '<tr>';
        $cadena = $cadena . '<th class="header-negro" colspan="2">Vehiculo</th>';
        $cadena = $cadena . '</tr>';
        $cadena = $cadena . '</thead>';
        $cadena = $cadena . '<tbody>';
        $cadena = $cadena . '<tr>';
        $cadena = $cadena . '<td>Modelo:</td>';
        $cadena = $cadena . "<td>$modelo</td>";
        $cadena = $cadena . '</tr>';
        $cadena = $cadena . '<tr>';
        $cadena = $cadena . '<td>Motor:</td>';
        $cadena = $cadena . "<td>$motor</td>";
        $cadena = $cadena . '</tr>';
        $cadena = $cadena . '<tr>';
        $cadena = $cadena . '<td>Año:</td>';
        $cadena = $cadena . "<td>$año</td>";
        $cadena = $cadena . '</tr>';
        $cadena = $cadena . '<tr class="cilindrada">';
        $cadena = $cadena . '<td>Cilindrada:</td>';
        $cadena = $cadena . "<td>$cilindrada</td>";
        $cadena = $cadena . '</tr>';
        $cadena .= "</tbody></table><table class='table table-sm table-borderless table-custom table-codigos-web'>";
        $cadena .= '<thead class=="text-uppercase"><tr><th  colspan="2">Filtros</th></tr></thead>';

        $sql = "SELECT id, aplicacion, codigo FROM aplicacion
                        WHERE ( id_tipo = :id_tipo ) and ( id_marca = :id_marca ) and ( id_vehiculo = :id_vehiculo ) and ( deleted_at is null )";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->bindParam(":id_tipo", $id_tipo, PDO::PARAM_INT);
        $seleccionado->bindParam(":id_vehiculo", $id_vehiculo, PDO::PARAM_INT);
        $seleccionado->bindParam(":id_marca", $id_marca, PDO::PARAM_INT);
        $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
		$seleccionado->execute();
        while( $row2 = $seleccionado->fetch() ){
            $codigo = $row2['codigo'];
            $id = $row2['id'];
            $clase = json_decode(queTablaCodigo($codigo, $base_de_datos));
            $cadena .= "<tr>";
            $cadena .= "<td>" . $row2['aplicacion'] . ":</td>";
            $cadena .= "<td><a href='./../ficha_tecnica/index.php?codigo=$codigo&clase=$clase&codigoVehiculo=$id' class='link'>" . $codigo . "</a></td>";
            $cadena .= "</tr>";
        }
        $cadena = $cadena . '</table>';

    
        
        echo json_encode($cadena, JSON_UNESCAPED_UNICODE);
    }