<!-- Es el componente de las imagenes de los filtros -->

<div class="row">

        <?php 
            for($i = 0; $i < 4; $i++){ //Se van a ir comprobando las 4 imagenes una por una
                if( $imagenes[$i] != "" ){ //Si hay alguna imagen guardada en la base de datos
                    if( file_exists("./../../../images/fichas-filtros/web/$imagenes[$i].jpg") ){ //En caso de que la imagen este guardada en la carpeta
        ?>
                <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                        <div class="card">
                            <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                            <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                                <p>Esta imagen no tiene las dimensiones adecuadas</p>
                            </div>
                            <!-- Fin del Estracto -->
                            
                            <img src="<?php echo $loc; ?>images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" class="imagen" id="img-<?php echo ($i+1)?>" data="<?php echo $loc; ?>images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" ></img>
                             
                            <div class="flex-d mt-5 mb-2" style="display: flex; align-items: center;">
                                <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="iborrainputfile">Seleccionar archivo</span>
                                </label>
                                <input type="hidden" name="eliminar-imagen<?php echo ($i+1); ?>" value="<?php echo ($i+1); ?>" >
                                <?php if( isset( $colocar_eliminar ) ){ ?>
                                    
                                    <div class="mt-auto">
                                    <button type="button" name="button-eliminar-imagen<?php echo ($i+1); ?>" id="button-eliminar-imagen<?php echo ($i+1); ?>" class="btn_borde_rweb_form ms-2">Eliminar Imagen</button>
                                    </div>
                                    <?php } ?>
                                    
                            </div>
                        </div>
                </div>
                            
                       
                    <?php 
                    }
                    else { //Si no esta guardada en la carpeta se colocará una por defecto (no-img.jpg)
                    ?>
                    <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                        <div class="card">
                            <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                            <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                                <p>Esta imagen no tiene las dimensiones adecuadas</p>
                            </div>
                             <!-- Fin del Estracto -->
                            <img src="<?php echo $loc; ?>images/fichas-filtros/web/no-img.jpg" class="imagen" id="img-<?php echo ($i+1)?>"></img>
                            
                            <div class="flex-d mt-5 mb-2" style="display: flex; align-items: center;">
                                <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="iborrainputfile">Seleccionar archivo</span>
                                </label>
                            </div>
                        </div>
                    </div>
                 
                    <?php 
                        }
                    }
                else{ //Si no hay imagen en la base de datos se colocará una por defecto (no-img.jpg)
                ?>
                    <div class="col-6 mb-4"> <!-- Espaciado inferior de 4 -->
                        <div class="card">
                            <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                            <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                                <p>Esta imagen no tiene las dimensiones adecuadas</p>
                            </div>
                            <!-- Fin del Estracto -->
                            <img src="<?php echo $loc; ?>images/fichas-filtros/web/no-img.jpg" class="imagen" id="img-<?php echo ($i+1)?>"></img>
                            
                            <div class="flex-d mt-5 mb-2" style="display: flex; align-items: center;">
                                <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="iborrainputfile">Seleccionar archivo</span>
                                </label>
                            </div>
                      </div>
                     </div>
                <?php 
                }
            }
        ?>
    </div>
