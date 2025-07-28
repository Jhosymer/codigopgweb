<?php
    try{
        $url_arriba_carpeta = './../arriba_carpeta.php';
        if ( !file_exists( $url_arriba_carpeta ) ){
            throw new Exception ('No se encontro el archivo arriba_carpeta.php');
        }
        else {
            include_once($url_arriba_carpeta);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            alert('No se encontro el archivo arriba_carpeta.php');
        </script>";
    }

    try{
        $url_base_datos = './../conexion/conexion.php';
        if ( !file_exists( $url_base_datos ) ){
            throw new Exception ('No encontró la base de datos');
        }
        else {
            include_once($url_base_datos);
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }
    catch(Exception $e){
        echo "
        <script>
            Swal.fire({
                icon: 'error',
                title: '" . $e->getMessage() . "',
            })
        </script>";
    }
    catch(PDOException $e){
        ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Ha sucedido un error con la conexión a la base de datos',
                })
            </script>
        <?php
    }

    $seleccionado = $base_de_datos->prepare("SELECT count(*) FROM especificaciones");
    $seleccionado->execute();
    $contador_especificaciones = $seleccionado->fetch(PDO::FETCH_ASSOC);
    $num_total_rows = $contador_especificaciones['count(*)'];

    if(isset($_GET["registros"])){
        $perPage=$_GET["registros"];
    }
    else {
        $perPage = 10;
    }
    $page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

    if(isset($_GET['id'])){
        $id = $_GET['id'];
        $sql = "SELECT itemname FROM especificaciones WHERE codigo=$id";
        $seleccionado = $base_de_datos->prepare($sql);
        $seleccionado->execute();
        $filtro = $seleccionado->fetch(PDO::FETCH_ASSOC);
        $filtro = $filtro['itemname'];
    }

    $paginas = [10, 25, 50, 100, 250, 500];

?>

    <title>Especificaciones - Especificaciones</title>
    <script src="./../js/sweetAlerta.js"></script>

    <?php
        if(isset($_GET["actualizado"]) && $_GET["actualizado"] == 'true')
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'La información de <?php echo $filtro; ?> se ha actualizado',
                        timer: 1250,
                    }) .then(() => {
                        window.location.replace("especificaciones.php?page="+<?php echo $page; ?>+"&registros="+<?php echo $perPage; ?>);
                    })
                </script>
            <?php
        }
        if(isset($_GET["actualizado_imagen"]) && $_GET["actualizado_imagen"] == true)
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'La imagen de <?php echo $filtro; ?> se ha actualizado',
                        timer: 1250,
                    }) .then(() => {
                        window.location.replace("especificaciones.php?page="+<?php echo $page; ?>+"&registros="+<?php echo $perPage; ?>);
                    })
                </script>
            <?php
        }
        if(isset($_GET["nuevo"]) && $_GET["nuevo"] == true)
        {
            ?>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'El registro ha sido agregado',
                        timer: 1250,
                    }) .then(() => {
                        window.location.replace("especificaciones.php");
                    })
                </script>
            <?php
        }if( isset($_GET["errorBase"]) )
            {
        ?>
            <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Hubo un problema con la base de datos',
                timer: 1250,
                }) .then(() => {
                window.location.replace("especificaciones.php");
            })
        </script>
        <?php
            }
    ?>
                        
    <section class="about_tabla_espe">
        <section class="about-if_tabla_esp">
            <div class="tex_tablas">
                <p>Especificaciones</p>
            </div>
            <div class="tex_tablas">
                <a href="nuevo.php" class="boton">Nuevo</a>
                <div>
            </div>
            </div>
        </section>

        <div class="about_tabla_edi">

        <form action="" method="GET" class="inline">
            <label>Mostrar:</label>
            <select name="registros" id="registros" class="mostar_textbox" onchange="this.form.submit()" >
                <?php 
                    foreach($paginas as $pag){
                        if($pag == $perPage){
                            ?>
                                <option value="<?php echo $perPage; ?>" selected><?php echo $perPage; ?></option>
                            <?php
                        }
                        else{
                            ?>
                                <option value="<?php echo $pag; ?>"><?php echo $pag; ?></option>
                            <?php
                        }
                    }
                ?>         
            </select>
        </form>

        <input type="text" class="textbox inline"  id='texto' size="30" placeholder="Buscar">

    <div class="table-responsive">
        <?php 
            $startAt = $perPage * ($page - 1);
            $totalPages = ceil($num_total_rows / $perPage);

            $links = [];
            for ($i = 1; $i <= $totalPages; $i++) {
                $links[$i] = ($i != $page ) 
                    ? "<a href='especificaciones.php?page=$i&registros=$perPage'>$i</a>"
                    : "<p>$page</p>";
                }
                $anterior = $page - 1;
                $siguiente = $page + 1;
                $links[$i + 1] = ($page != 1) ? "<a href='especificaciones.php?page=$anterior&registros=$perPage'>Anterior</a>" : null;
                $links[$i + 2] = ($page < $totalPages) ? "<a href='especificaciones.php?page=$siguiente&registros=$perPage'>Siguiente</a>" : null;
        ?>
        <table>
            <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Codigo</th>
                <th>Codigo Buscar</th>
                <th>Espec 1</th>
                <th>Desc 1</th>
                <th>Espec 2</th>
                <th>Desc 2</th>
                <th>Espec 3</th>
                <th>Desc 3</th>
                <th>Espec 4</th>
                <th>Desc 4</th>
                <th>Espec 5</th>
                <th>Desc 5</th>
                <th>Espec 6</th>
                <th>Desc 6</th>
                <th>Espec 7</th>
                <th>Desc 7</th>
                <th>Espec 8</th>
                <th>Desc 8</th>
                <th>Espec 9</th>
                <th>Desc 9</th>
                <th>Espec 10</th>
                <th>Desc 10</th>
                <th>Linea</th>
            </tr>
        </thead>
        <tbody>
                <?php
                    $seleccionado = $base_de_datos->prepare("SELECT * FROM especificaciones 
                                                            LIMIT $startAt, $perPage");
                    $seleccionado->execute();
                    while ($fila = $seleccionado->fetch(PDO::FETCH_ASSOC)) {
                        $especificaciones []= $fila;
                    }
                    foreach($especificaciones as $espec){
                ?>
                <tr>
                    <td>
                        <?php
                            echo $espec['codigo'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['Itemname'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['itemcode'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp1'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc1'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp2'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc2'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_escp3'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc3'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp4'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc4'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp5'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc5'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp6'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc6'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp7'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc7'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp8'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc8'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp9'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc9'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Esp10'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['U_Desc10'];
                        ?>
                    </td>
                    <td>
                        <?php
                            echo $espec['linea'];
                        ?>
                    </td>
                    <td>
                        <section class="about_boton">
                            <div class="tex_tablas">
                        <form action="#" method='POST' name="formu" class="formulario-eliminar">
                            <input type="button" onclick="eliminar('<?php echo $espec['codigo'] ?>', '<?php echo $page ?>', '<?php echo $perPage; ?>', '<?php echo $espec['codigo']; ?>')" value="" name="btnEliminar" class="del input" />
                        </form>
                        </div>
                        <div class="tex_tablas">
                            <form action="ver.php" method='POST'>
                                <input value="<?php echo $espec['codigo']?>" name="ver" type="hidden"/>
                                <input value="<?php echo $page?>" name="page" type="hidden"/>
                                <input value="<?php echo $perPage?>" name="registros" type="hidden"/>
                                <input type="submit" value="" name="btnVer"  class="ver input"/>
                            </form>
                        </div>
                        <div class="tex_tablas">
                            <form action="editar.php" method="POST">
                                <input value="<?php echo $espec['codigo']?>" name="editar" type="hidden"/>
                                <input value="<?php echo $page?>" name="page" type="hidden"/>
                                <input value="<?php echo $perPage?>" name="registros" type="hidden"/>
                                <input type="submit" value="" name="btnEditar" class="edi input" />
                            </form>
                        </div>
                        <div class="tex_tablas">
                            <form action="editar_imagenes.php" method="POST">
                                <input value="<?php echo $espec['codigo']?>" name="editar_imagenes" type="hidden"/>
                                <input value="<?php echo $page?>" name="page" type="hidden"/>
                                <input value="<?php echo $perPage?>" name="registros" type="hidden"/>
                                <input type="submit" value="" name="btnimg" class="foto input" />
                            </form>
                        </div>
                        </section>
                    </td>
                </tr>
                <?php
                    }
                ?>
        </tbody>
    </table>
    </div>
    <div class="links">
        <?php
            $numeros = 1;
            foreach($links as $link){
                if($numeros == 1){
                    echo $links[$i+1];
                }
                if($page > $numeros - 3 and $page < $numeros + 3 and $totalPages >= $numeros){
                    echo $link;
                }
                if($numeros == $totalPages){
                    echo $links[$i+2];
                }
                $numeros = $numeros + 1;
            }
        ?>
    </div>

</section>
<?php 
    include_once('./../abajo_carpeta.html');
?>

<script>
    function eliminar(id, page, registros, codigo){
        Swal.fire({
                icon: "warning",
                title: "Eliminar",
                text: `¿Está seguro que desea eliminar el ${codigo}?`,
                showCancelButton: true,
                cancelButtonColor: '#838383',
                confirmButtonColor: '#E2001A',
                confirmButtonText: 'Si, eliminalo',
                buttonsStyling: true,
                cancelButtonText: "Cancelar",
                footer: "Si se elimina, no se podra recuperar el registro",
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "¡Eliminado!",
                        text: `El registro ${codigo} eliminado correctamente`,
                        icon: "success",
                        timer: '1000'
                    }).then(value => {
                        window.location.href = "eliminar.php?page=" + page + "&registros=" + registros + "&id=" + id;
                    });
                } 
            })
    }
</script>

<script src="../js/buscar.js"></script>