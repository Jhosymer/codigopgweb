<link rel="stylesheet" href="./../css/estilo_body_apli.css">
<title>Filtro para Aceite</title>
<?php
    include("./../web/arriba_carpeta.php");
    include("./../../conexion.php");

    $categoria_seleccionada = $_GET['categoria'];
    
    $sql = "SELECT f_c.codigo as codigo FROM tipos as t 
            JOIN filtro_codificacion as f_c ON f_c.id_tipo = t.id
            WHERE ( f_c.id_tipo = :id_tipo and f_c.deleted_at is null and t.deleted_at is null )";
    $tipos_selec = $base_de_datos->prepare($sql);  
    $tipos_selec->bindParam(':id_tipo', $_GET['filtro'], PDO::PARAM_INT );
    $tipos_selec->setFetchMode( PDO::FETCH_ASSOC );
    $tipos_selec->execute();
    while( $fila = $tipos_selec->fetch() ){
        $tipos []= $fila;
    }

    $sql = "SELECT categoria FROM categorias WHERE producto_id = 1 GROUP BY categoria";
    $categorias_selec = $base_de_datos->prepare($sql);  
    $categorias_selec->setFetchMode( PDO::FETCH_ASSOC );
    $categorias_selec->execute();
    while( $fila = $categorias_selec->fetch() ){
        $categorias []= $fila;
    }
?>


<div class="aplicacion_producto" >
    <div class="grid_productos" >
        <p class="nombre_prod" >Filtro para Aceite</p>
        <div class="grid_productos_tabla">
            <div class="about_fitros_productos">
                <?php 
                    foreach( $categorias as $categoria ){
                        $categoria_sin_espacio = str_replace(' ', "", $categoria['categoria']);
                            if( $categoria['categoria'] == $categoria_seleccionada ){
                ?>
                        <div class="div_img_pro" id="<?php echo $categoria_sin_espacio; ?>" name="<?php echo $categoria['categoria']; ?>" style="cursor: pointer;" >
                            <?php 
                            }
                            else {
                                ?>
                                     <div class="div_img_pro clase2" id="<?php echo $categoria_sin_espacio; ?>" name="<?php echo $categoria['categoria']; ?>" style="cursor: pointer;" >
                                <?php
                            }
                                if( $categoria['categoria'] == "SELLADO ROSCABLE" ) { 
                            ?>
                                    <img src="./../img/productos/w-2010.png"  class="img_prod" alt="">
                            <?php } 
                                else if( $categoria['categoria'] == "ELEMENTO" ){ 
                            ?>
                                    <img src="./../img/productos/ISU_4.png"  class="img_prod" alt="">
                            <?php
                                }
                                else {
                            ?>
                                    <img src="./../img/productos/w-2010.png"  class="img_prod" alt="">
                            <?php
                                } 
                            ?>
                        </div> 
                        <div class="div_img_pro" id="<?php echo $categoria['categoria']; ?>" >
                            <p class="titulo_prod"><?php echo $categoria['categoria'] ?></p>
                        </div>
                <?php 
                    }
                ?>     
            </div>   
            <div class="tabla_prod" id="tabla_prod" style="display: block;">
                    <label>Mostrar:</label>
                    <select id="registros" name="">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>               
                    </select>
                    <input type="text" id="texto" name="texto" class="vBuscar">
                    <table class="pro table-responsive" id="filtros" >
                        <thead class="pro">
                            <tr class="pro"> 
                                <td class="pro">Codigo</td>
                                <td class="pro">tipo</td>
                                <td class="pro">Año</td>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach( $tipos as $tipo ){ ?>
                            <tr>
                                <td class="pro">
                                    <a><?php echo $tipo['codigo']; ?></a>
                                </td>
                                <td class="pro">N/D</td>
                                <td class="pro">N/D</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>        
    </div>
</div>
<?php
    include("./../web/abajo_carpeta.php");
?>

<script>
    paginaActual = 1;

    document.getElementById("texto").addEventListener("keyup", function(){
        getData(1);
    }, false);

    document.getElementById("registros").addEventListener("change", function(){
        getData(1);
    }, false);

    document.addEventListener('DOMContentLoaded', () => {
        div_img_pro = document.querySelectorAll('.div_img_pro');
        div_img_pro.forEach(function( div_img ) {
            div_img.addEventListener('click', function(){
                categoria = typeof categoria !== "undefined" ? categoria.replace(/\s+/g, '') : 0;
                if( categoria != 0 ){
                    cat = document.querySelectorAll(`#${categoria.replace(/\s+/g, '')}`);
                    cat.forEach(function( c ){
                        c.classList.add('clase2');
                    });
                }
                categoria = div_img.getAttribute("name");
                let elemento = document.getElementById(categoria.replace(/\s+/g, ''));
                elemento.classList.remove('clase2');
                
                formData = new FormData();
                formData.append('categoria', categoria);
                fetch("./../ajax_busquedas/tipos_tabla.php", {
                    method: 'POST',
                    body: formData,
                })
                .then( response => response.json() )
                .then(
                    data => {
                        document.getElementById('tabla_prod').style.display = "table";
                        document.getElementById('tipos').style.display = "table";
                        document.getElementById('resultados_tipos').innerHTML = data.data;
                        if(data.totalFiltro != data.totalRegistros){
                            document.getElementById("lbl-total").innerHTML = "<p>Mostrando " + data.totalFiltro + " de " + data.totalRegistros + " registros</p>";
                        }
                        else {
                            document.getElementById('lbl-total').innerHTML = "";
                        }
                        document.getElementById('paginacion').innerHTML =  data.paginacion;
                    }
                )
                .catch(
                    error => alert(error)
                )
            });
        })

    })
</script>

<style>
    .clase2 {
        opacity: 0.5;
    }
</style>
