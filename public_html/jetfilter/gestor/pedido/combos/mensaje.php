<div class="py-3">
        <?php 
                     if(isset($_SESSION['mensajeLista'])){ 
                      echo "<div class='text-center alert " .$_SESSION['tm']."' role='alert'>";
                      echo $_SESSION['mensajeLista'];
                      echo "</div>";
                      unset($_SESSION['mensajeLista']);
                  }?>
        </div>

     