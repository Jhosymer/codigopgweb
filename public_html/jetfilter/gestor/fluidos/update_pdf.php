<?php
/* -----ARCHIVO PARA ACTUALIZAR LAS IMAGENES UN REGISTRO EN AIRE AUTOMOTRIZ-------- */

date_default_timezone_set('America/Caracas');
session_start();

//Si no existe id te redirigirá a otra ventana
if (!isset($_POST['id_codigo'])) {
    header("location: ./espec_fluidos.php");
} else {
    $idcodigo = $_POST['id_codigo'];
}

include_once('../../../config/conexion.php');

if (isset($_POST['btnpdf'])) {

    $sincronizado = date("Ymd");
    $fecha_updated = date("Y-m-d H:i:s");
    $fecha_actualiza = date("d-m-y");

    //Se buscan los pdf del filtro
    $seleccionado = $base_de_datos->prepare("SELECT id, codigo, boletin, instalacion FROM filtro_codificacion
                                                WHERE id = :idcodigo ") or die('Error al eliminar');
    $seleccionado->bindParam(':idcodigo', $idcodigo, PDO::PARAM_INT);
    $seleccionado->execute();
    $seleccionado_pdf = $seleccionado->fetch();

    $codigo = $seleccionado_pdf['codigo'];
    $id_codigo = $seleccionado_pdf['id'];

    $boletin = $_FILES['boletin']['name'];
    $instalacion = $_FILES['instalacion']['name'];

    if ($boletin != "") {
        $boletin_destino = "../../../informacion_adicional/boletin/" . $codigo . ".pdf";
        move_uploaded_file($_FILES['boletin']['tmp_name'], $boletin_destino);

        $archivoboletin = $codigo;

        $argumentos = [$fecha_actualiza, $sincronizado, $fecha_updated, $archivoboletin, $id_codigo];

        try {
            //Se crearan los registros en la tabla de aire automotriz y de filtro codificación.
            //En caso de que falle alguna subida, se cancelara todo
            $base_de_datos->beginTransaction();

            $actualizando = $base_de_datos->prepare("UPDATE filtro_codificacion SET fecha_actualiza = ?, sincronizado = ?, updated_at = ?, boletin = ? WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos);

            $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $aplicacion = $base_de_datos->prepare($sql);
            $aplicacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $aplicacion->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $aplicacion->execute();

            $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $equivalencia_update = $base_de_datos->prepare($sql);
            $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $equivalencia_update->execute();

            //Si todo salio bien
            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
            // header( "location: espec_fluidos.php");
        } catch (PDOException $exception) {
            //Si todo salio bien
            $base_de_datos->rollBack();
            $_SESSION['error'] = true;
            header("location: ./espec_fluidos.php");
        }

        header( "location: espec_fluidos.php");
    }

    if ($instalacion != "") {
        $instalacion_destino = "../../../informacion_adicional/instalacion/" . $codigo . ".pdf";
        move_uploaded_file($_FILES['instalacion']['tmp_name'], $instalacion_destino);
        $archivoinstalacion = $codigo;

        $argumentos = [$fecha_actualiza, $sincronizado, $fecha_updated, $archivoinstalacion, $id_codigo];

        try {
            //Se crearan los registros en la tabla de aire automotriz y de filtro codificación.
            //En caso de que falle alguna subida, se cancelara todo
            $base_de_datos->beginTransaction();

            $actualizando = $base_de_datos->prepare("UPDATE filtro_codificacion SET fecha_actualiza = ?, sincronizado = ?, updated_at = ?, instalacion =?  WHERE id = ?") or die("Error al actualizar");
            $actualizando->execute($argumentos);

            $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $aplicacion = $base_de_datos->prepare($sql);
            $aplicacion->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $aplicacion->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $aplicacion->execute();

            $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( codigo = :codigo )";
            $equivalencia_update = $base_de_datos->prepare($sql);
            $equivalencia_update->bindParam(':codigo', $codigo, PDO::PARAM_STR);
            $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $equivalencia_update->execute();

            //Si todo salio bien
            $base_de_datos->commit();

            $_SESSION['actualizado'] = true;
        } catch (PDOException $exception) {
            //Si todo salio bien
            $base_de_datos->rollBack();
            $_SESSION['error'] = true;
           
        }

        header( "location: espec_fluidos.php");
    }
       if ($instalacion == ""  &&  $boletin == "" ) {
    
        header( "location: espec_fluidos.php");
        $_SESSION['error'] = true;

    }
    
}
 //Si se pulson un boton para eliminar imagenes
    else if( isset( $_POST['pdf_eliminada'] ) ){
       
        $sincronizado = date("Ymd");
        $id_codigo = $_POST['id_codigo'];
        $id = $_POST['id'];

        try {
            $base_de_datos->beginTransaction();
            
            if( $_POST['pdf_eliminada'] == 'button-pdf1' ){
                 $sql = "UPDATE filtro_codificacion SET sincronizado = :sincronizado, boletin = NULL  WHERE id = :id_codigo";
            }
            else if( $_POST['pdf_eliminada'] == 'button-pdf2' ){
               
                $sql = "UPDATE filtro_codificacion SET sincronizado = :sincronizado, instalacion = NULL WHERE id = :id_codigo";
            }
           

            $borrar_imagen = $base_de_datos->prepare($sql);
            $borrar_imagen->bindParam(':sincronizado', $sincronizado, PDO::PARAM_INT );
            $borrar_imagen->bindParam(':id_codigo', $id_codigo, PDO::PARAM_INT );
            $borrar_imagen->execute();

            $sql = "UPDATE aplicacion SET sincronizado = :sincronizado WHERE ( id_codigo = :id_codigo )";
            $aplicacion = $base_de_datos->prepare($sql);
            $aplicacion->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR);
            $aplicacion->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $aplicacion->execute();

            $sql = "UPDATE filtro_equivalencia SET sincronizado = :sincronizado WHERE ( id_codigo = :id_codigo )";
            $equivalencia_update = $base_de_datos->prepare($sql);
            $equivalencia_update->bindParam(':id_codigo', $id_codigo, PDO::PARAM_STR);
            $equivalencia_update->bindParam(':sincronizado', $sincronizado, PDO::PARAM_STR);
            $equivalencia_update->execute();
            $base_de_datos->commit();
            
            $_SESSION['pdf_eliminada'] = true;
            header( "location: ./editar_pdf.php?id=$id&idcodigo=$id_codigo");
        }
        catch( PDOException $exception ){
            $base_de_datos->rollback();
            $_SESSION['error'] = true;
        }
    }
?>
