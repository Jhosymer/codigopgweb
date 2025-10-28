<div class="row">
        <?php 
                for($i = 0; $i < 4; $i++){ //Se van a ir comprobando las 4 imagenes una por una
                    if( $imagenes[$i] != "" ){ //Si hay alguna imagen guardada en la base de datos
                        if( file_exists("./../../../images/fichas-filtros/web/$imagenes[$i].jpg") ){ //En caso de que la imagen este guardada en la carpeta
                            ?>
                           <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                                <div class="card">
                                    <img src="<?php echo $loc; ?>images/fichas-filtros/web/<?php echo $imagenes[$i] . ".jpg"; ?>?t=<?php echo $rann?>" class="imagen card-img-top img-fluid" data="<?php echo $loc; ?>images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg?t=<?php echo $rann?>"></img>
                                </div>
                            </div>
                            <?php 
                        }
                        else { //Si no esta guardada en la carpeta se colocará una por defecto (no-img.jpg)
                            ?>
                            <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                                 <div class="card">
                                    <img src="<?php echo $loc; ?>images/fichas-filtros/web/no-img.jpg" class="card-img-top img-fluid"></img>
                                </div>
                            </div>
                            <?php 
                        }
                    }
                    else{ //Si no hay imagen en la base de datos se colocará una por defecto (no-img.jpg)
                        ?>
                        <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                            <div class="card">
                                <img src="<?php echo $loc; ?>images/fichas-filtros/web/no-img.jpg" class="card-img-top img-fluid"></img>
                            </div>
                        </div>
                        <?php 
                    }
                }
            ?>
</div>