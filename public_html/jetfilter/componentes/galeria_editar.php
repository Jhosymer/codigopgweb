<!-- Es el componente de las imagenes de los filtros -->

<div class="galeria"></br>
    <div class="contenedor-imagenes">
        <?php 
            for($i = 0; $i < 4; $i++){ //Se van a ir comprobando las 4 imagenes una por una
                if( $imagenes[$i] != "" ){ //Si hay alguna imagen guardada en la base de datos
                    if( file_exists("./../../images/fichas-filtros/web/$imagenes[$i].jpg") ){ //En caso de que la imagen este guardada en la carpeta
        ?>
                        <div class="imag">
                            <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                            <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                                <p>Esta imagen no tiene las dimensiones adecuadas</p>
                            </div>
                            <!-- Fin del Estracto -->
                            <img src="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" class="imagen" id="img-<?php echo ($i+1)?>" data="./../../images/fichas-filtros/web/<?php echo $imagenes[$i]; ?>.jpg" ></img>
                            <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                            <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                <span class="iborrainputfile">Seleccionar archivo</span>
                            </label>
                            <input type="hidden" name="eliminar-imagen<?php echo ($i+1); ?>" value="<?php echo ($i+1); ?>" >
                            <?php if( isset( $colocar_eliminar ) ){ ?>
                                <button type="button" name="button-eliminar-imagen<?php echo ($i+1); ?>" id="button-eliminar-imagen<?php echo ($i+1); ?>" style="width: 40%; margin-top: -5.25em; margin-right: -0.25em;">Eliminar Imagen</button>
                            <?php } ?>
                        </div>
                    <?php 
                    }
                    else { //Si no esta guardada en la carpeta se colocará una por defecto (no-img.jpg)
                    ?>
                        <div class="imag">
                            <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                            <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                                <p>Esta imagen no tiene las dimensiones adecuadas</p>
                            </div>
                             <!-- Fin del Estracto -->
                            <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen" id="img-<?php echo ($i+1)?>"></img>
                            <div style="display: flex; ">
                                <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                                <label class="file-1" for="file-<?php echo ($i+1) ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                    <span class="iborrainputfile">Seleccionar archivo</span>
                                </label>
                            </div>
                        </div>
                    <?php 
                        }
                    }
                else{ //Si no hay imagen en la base de datos se colocará una por defecto (no-img.jpg)
                ?>
                    <div class="imag">
                        <!-- Este estracto de código solo va a aparecer si hay una imagen con tamaño incorrecto -->
                        <div id="precaucion_imagen-<?php echo ($i+1) ?>" style="position: absolute; background-color: white; height: 100%; color:red; justify-content: center; align-items: center">
                            <p>Esta imagen no tiene las dimensiones adecuadas</p>
                        </div>
                         <!-- Fin del Estracto -->
                        <img src="./../../images/fichas-filtros/web/no-img.jpg" class="imagen" id="img-<?php echo ($i+1)?>"></img>
                        <input type="file" accept="image/jpeg" name="imagen<?php echo ($i+1); ?>" id="file-<?php echo ($i+1) ?>" class="inputfile inputfile-1" data-multiple-caption="{count} archivos seleccionados" multiple />
                        <label class="file-1" for="file-<?php echo ($i+1) ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                            <span class="iborrainputfile">Seleccionar archivo</span>
                        </label>
                    </div>
                <?php 
                }
            }
        ?>
    </div>
</div>