
        <section class="about-if_tabla_esp">
           
            <div class="tex_tablas">
                
            
            <form action="porcodigo.php" method="POST" target = "iframe" >
                        <div class="cover">
                        <input type="text" name="keywords" placeholder="por codigo" class="textbox" id="keywords" required autocomplete="off" />
                     
                        <input type="submit" value="" name="search"  class="ver"/>
                    </div>
                </div>
               
             <!--<a href="#" class="price__cta">Buscar</a>–-!-->
        </form>
            </div>
           
          
            <div class="tex_tablas ">
            <section class="about_aplicacion_op_br">
                <div class="about_aplicacion_sel_br about_sel">                   
                      <select id="lista1" name="lista1" class="selectBR" onchange="mostrar()" width="100%">
                        <option value="0">Seleccionar aplicacion</option>
                    <?php
          include ('./../conexion.php');
          
            $base_de_datos = new PDO('mysql:host=' . $rutaServidor . ';dbname=' . $nombreBaseDeDatos,$usuario, $contraseña);
            
                    $base_de_datos->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sentencia = $base_de_datos->query("SELECT aplicacion , id FROM aplicacion_tipo ");
                    $sentencia->setFetchMode(PDO::FETCH_ASSOC); 
          
                    while ($row = $sentencia->fetch()) {
                      echo '<option value="'.$row['id'].'">'.$row['aplicacion'].'</option>';
                    } ?>
                  </select>
                  </div>
                     <div style="display: none;" id="contenido">
                      <div  class="about_tex_img" id="select2lista" ></div></div>

                      <div class="about_aplicacion_sel_br">
                        
                        <div style="display: none;" id="contenido2">
                        </div>
                       
                       </div>

                      
                       </section> 
            </div>
        
           
                
            </div>
            </div>
            
        </section>
            </div>
        
           
                
            </div>
            </div>
            
        </section>
        

    </section>

 

   
    
<script type="text/javascript">
 //Esta funcion aplicacion.
	$(document).ready(function(){
		$('#lista1').val(1);
		recargarLista();

		$('#lista1').change(function(){
			recargarLista();
		});
	})
</script>
<script type="text/javascript">
	function recargarLista(){
		$.ajax({
			type:"POST",
			url:"./index/ajaxDate.php",
			data:"marca=" + $('#lista1').val(),
			success:function(r){
				$('#select2lista').html(r);
			}
		});
	}
</script>
 <script src="./js/mostrar.js"></script>
    <script
      src="https://code.jquery.com/jquery-3.2.0.min.js"
      integrity="sha256-JAW99MJVpJBGcbzEuXk4Az05s/XyDdBomFqNlM3ic+I="
      crossorigin="anonymous">
    </script>
    <script>
    //Esta es la función que una vez se cargue el documento será gatillada.
    $(function(){
        $("#lista1").val('0')
    });
</script>
