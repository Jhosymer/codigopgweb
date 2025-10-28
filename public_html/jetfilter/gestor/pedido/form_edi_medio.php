

<table class="table table-striped table-hover color_blanco table-responsive table-bordered dataTable" id="invoiceItem">	
<thead>  
<tr>
<th width="2%"></th>
            <th width="12%">Codigo Prod.</th>
            <th width="30%">Nombre Producto</th>
            <th width="10%">Stock</th>
            <th width="10%">Cantidad</th>
            <th width="12%">precio Und</th>
            <th width="12%">total</th>
            <th width="15%">Operacion</th>
        </tr>
     </thead>  
   

     <form action="crud.php" method="post" id="myform">

     <input type="hidden" name="modo" value="<?php echo $modo ?>">
<input type="hidden" name="id_pedido" id ="id_pedido"  value="<?php echo $id_pedido ?>">
        
<tr>
            <td><input type="hidden" name="id_pro" id ="id_pro"  value="<?php echo $id_pro ?>">
            <input type="hidden" name="id_lista_pedido" id ="id_lista_pedido"  value="<?php echo $id_lista_pedido ?>">
        </td>
            <td><input type="text" name="codigo_p" id="codigo_p" class="form-control" autocomplete="off" value="<?php echo $codpro?>">
            <ul id="lista_cp" class="list-group  li_busqueda"></ul></td>
    
            <td><input type="text" name="nombre_p" id="nombre_p" class="form-control" autocomplete="off" value="<?php echo $nombre  ?>">	
            <ul id="lista_np" class="list-group  li_busqueda"></ul></td>
            <td><input type="number" name="stock"  id="stock" class="inavi" autocomplete="off" value="<?php echo $stock ?>" ></td>
           	
            <td><input type="text" name="cantidad"  id="cantidad" class="form-control quantity" autocomplete="off" value="<?php echo $cantidad ?>"></td>
            <td><input type="text"   name="precio_u" id="precio_u" class="inavi " autocomplete="off" value="<?php echo $precio_u ?>" ></td>
            
            <td><input type="text"   name="total_pp" id="total_pp" class="inavi" autocomplete="off" value="<?php echo $total_pp ?>" ></td>
        

            <td>  <button type="submit"  class="<?php echo $colorbn ?>"><?php echo $nb ?></button></td>
        </tr>
        </form>
        </tbody>
        </table>

     
   
      
  </div>
   