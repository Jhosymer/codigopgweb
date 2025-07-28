<?php
   if (isset($_POST['codigo'])) { 
        include("./../../conexion.php");

    $codigo = $_POST['codigo'];
    $caracteres_a_reemplazar = ['-', " ", "_", '/', '%', '*', ',', '.'];
    $codigo_buscar = str_replace($caracteres_a_reemplazar,'',$codigo);
    $codigo_preparado = "%$codigo_buscar%";

    //Coincidencia Exacta Aire Automotriz
    $sql = "SELECT codigo FROM espec_aireautomotriz WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_automotriz = $base_de_datos->prepare($sql);
    $coincidencia_exacta_automotriz->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_automotriz->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_automotriz->execute();

    //Coincidencia Parcial Aire Automotriz
    $sql = "SELECT codigo FROM espec_aireautomotriz WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado->execute();

    //Coincidencia Exacta Aire Industrial
    $sql = "SELECT codigo FROM espec_aireindustrial WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_industrial = $base_de_datos->prepare($sql);
    $coincidencia_exacta_industrial->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_industrial->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_industrial->execute();

    //Coincidencia Parcial Aire Industrial
    $sql = "SELECT codigo FROM espec_aireindustrial WHERE (codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado2 = $base_de_datos->prepare($sql);
    $seleccionado2->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado2->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado2->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado2->execute();

    //Coincidencia Exacta Combustible
    $sql = "SELECT codigo FROM espec_combustiblelinea WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_combustible = $base_de_datos->prepare($sql);
    $coincidencia_exacta_combustible->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_combustible->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_combustible->execute();

    //Coincidencia Parcial Combustible
    $sql = "SELECT codigo FROM espec_combustiblelinea WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado3 = $base_de_datos->prepare($sql);
    $seleccionado3->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado3->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado3->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado3->execute();

    //Coincidencia Exacta Elemento
    $sql = "SELECT codigo FROM espec_elemento WHERE (codigo_buscar = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_elemento = $base_de_datos->prepare($sql);
    $coincidencia_exacta_elemento->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_elemento->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_elemento->execute();

    //Coincidencia Parcial Elemento
    $sql = "SELECT codigo FROM espec_elemento WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo_buscar != :codigo ) and ( deleted_at is null )";
    $seleccionado4 = $base_de_datos->prepare($sql);
    $seleccionado4->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado4->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado4->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado4->execute();

    //Coincidencia Exacta Panel
    $sql = "SELECT codigo FROM espec_panel WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_panel = $base_de_datos->prepare($sql);
    $coincidencia_exacta_panel->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_panel->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_panel->execute();

    //Coincidencia Parcial Panel
    $sql = "SELECT codigo FROM espec_panel WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado5 = $base_de_datos->prepare($sql);
    $seleccionado5->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado5->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado5->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado5->execute();

    //Coincidencia Exacta Sellado
    $sql = "SELECT codigo FROM espec_sellado WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_sellado = $base_de_datos->prepare($sql);
    $coincidencia_exacta_sellado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_sellado->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_sellado->execute();

    //Coincidencia Parcial Sellado
    $sql = "SELECT codigo FROM espec_sellado WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado6 = $base_de_datos->prepare($sql);
    $seleccionado6->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado6->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado6->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado6->execute();

    //Coincidencia Exacta Sellado
    $sql = "SELECT codigo FROM espec_fluidos WHERE (codigo = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_fluidos = $base_de_datos->prepare($sql);
    $coincidencia_exacta_fluidos->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_fluidos->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_fluidos->execute();

    //Coincidencia Parcial Sellado
    $sql = "SELECT codigo FROM espec_fluidos WHERE (codigo LIKE :codigo_preparado or codigo_buscar LIKE :codigo_preparado) and ( codigo != :codigo ) and ( deleted_at is null )";
    $seleccionado7 = $base_de_datos->prepare($sql);
    $seleccionado7->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado7->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado7->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado7->execute();

    $output = [];

    $output['especificaciones'] = "<h3 class='titulo'>Jet-Filter</h3>";
    $output['especificaciones'] .= "<table class='codigos'>";
    $output['especificaciones'] .= "<thead class='codigos'>";
    $output['especificaciones'] .= "<tr><td class='codigos'>Codigos WEB</td></tr>";
    $output['especificaciones'] .= "</thead>";
    $output['especificaciones'] .= "<tbody>";
    while($row = $coincidencia_exacta_automotriz->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireautomotriz&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_industrial->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireaindustrial&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_combustible->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=combustible&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_elemento->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=elemento&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_panel->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=panel&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_sellado->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=sellado&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $coincidencia_exacta_fluidos->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=fluidos&cod=1' class='link resaltar'>";
        $output['especificaciones'] .= $row['codigo'] . "[*]";
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireautomotriz&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado2->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireindustrial&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado3->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=combustible&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado4->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=elemento&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado5->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=panel&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado6->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=sellado&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    while($row = $seleccionado7->fetch()){ 
        $codigo = $row['codigo'];
        $output['especificaciones'] .= "<tr><td class='codigos'><a href='./../filtro/filtro.php?codigo=$codigo&clase=sellado&cod=1' class='link'>";
        $output['especificaciones'] .= $row['codigo'];
        $output['especificaciones'] .= "</a></td></tr>";
    }
    $output['especificaciones'] .= "</tbody>";
    $output['especificaciones'] .= "</table>";

    $output['especificaciones'] = utf8_encode($output['especificaciones']);

    $codigo = $_POST['codigo'];

    //Coincidencia Exacta Aire Automotriz
    $sql = "SELECT codigo_marca, codigo, marca FROM filtro_equivalencia WHERE (codigo_marca = :codigo or codigo_marca_buscar = :codigo) and ( deleted_at is null )";
    $coincidencia_exacta_equivalencias = $base_de_datos->prepare($sql);
    $coincidencia_exacta_equivalencias->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $coincidencia_exacta_equivalencias->setFetchMode(PDO::FETCH_ASSOC); 
    $coincidencia_exacta_equivalencias->execute();

    $sql = "SELECT codigo_marca, codigo, marca FROM filtro_equivalencia WHERE ( codigo_marca LIKE :codigo_preparado or codigo_marca_buscar LIKE :codigo_preparado ) and ( codigo_marca != :codigo and codigo_marca_buscar != :codigo ) and ( deleted_at is null ) ";
    $seleccionado = $base_de_datos->prepare($sql);
    $seleccionado->bindParam(":codigo_preparado", $codigo_preparado, PDO::PARAM_STR);
    $seleccionado->bindParam(":codigo", $codigo, PDO::PARAM_STR);
    $seleccionado->setFetchMode(PDO::FETCH_ASSOC); 
    $seleccionado->execute();

    $output['equivalencias'] = "";
    $output['equivalencias'] .= "<h3 class='titulo'>Equivalencias</h3>";
    $output['equivalencias'] .= "<table class='equivalencias'>";
    $output['equivalencias'] .= "<thead class='equivalencias'>";
    $output['equivalencias'] .= "<tr><td class='equivalencias' colspan='3'>Codigos de Otros Fabricantes</td>";
    $output['equivalencias'] .= "<td class='codigos'>Codigo WEB</td></tr>";
    $output['equivalencias'] .= "</thead>";
    $output['equivalencias'] .= "<tbody>";
    while($row = $coincidencia_exacta_equivalencias->fetch()){ 
        $codigo = $row['codigo'];
        $output['equivalencias'] .= "<tr><td class='equivalencias resaltar'>";
        $output['equivalencias'] .= $row['codigo_marca'] . "[*]";
        $output['equivalencias'] .= "</td><td class='equivalencias'>";
        $output['equivalencias'] .= $row['marca'];
        $output['equivalencias'] .= "</td><td class='equivalencias campo'>";
        $output['equivalencias'] .= "->";
        $output['equivalencias'] .= "</td><td class='equivalencias'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireaindustrial&cod=1' class='link'>";
        $output['equivalencias'] .= $row['codigo'];
        $output['equivalencias'] .= "</a></td></tr>";
    }
    while($row = $seleccionado->fetch()){ 
        $codigo = $row['codigo'];
        $output['equivalencias'] .= "<tr><td class='equivalencias'>";
        $output['equivalencias'] .= $row['codigo_marca'];
        $output['equivalencias'] .= "</td><td class='equivalencias'>";
        $output['equivalencias'] .= $row['marca'];
        $output['equivalencias'] .= "</td><td class='equivalencias campo'>";
        $output['equivalencias'] .= "->";
        $output['equivalencias'] .= "</td><td class='equivalencias'><a href='./../filtro/filtro.php?codigo=$codigo&clase=aireaindustrial&cod=1' class='link'>";
        $output['equivalencias'] .= $row['codigo'];
        $output['equivalencias'] .= "</a></td></tr>";
    }
    $output['equivalencias'] .= "</tbody>";
    $output['equivalencias'] .= "</table>";

    $output['equivalencias'] = utf8_encode($output['equivalencias']);

    echo json_encode($output);
?>




<?php } 
    else {
?>
        <?php header("location: porcodigo2.php") ?>
<?php   
    }
?>