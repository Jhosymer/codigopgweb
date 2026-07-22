<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Clear-Site-Data: \"cache\", \"storage\"");

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "./../config/conexion.php"; 

$rutaJson = "./../ftp/cliente_sap.json";

echo "<h2>Sincronización Masiva: SAP -> Base de Datos</h2>";

if (!file_exists($rutaJson)) {
    die("<p style='color:red;'>Error: No se encontró el archivo cliente_sap.json.</p>");
}

$contenido = file_get_contents($rutaJson);
$clientesLista = json_decode($contenido, true);

if (!$clientesLista) {
    die("<p style='color:red;'>Error: El JSON está vacío o mal formado.</p>");
}

if (isset($clientesLista['rif'])) {
    $clientesLista = [$clientesLista];
}

/** * CONSULTAS PREPARADAS 
 */

//Verificar si el RIF ya existe
$sqlCheckRif = "SELECT id FROM users WHERE rif = ? LIMIT 1";
$stmtCheckRif = $base_de_datos->prepare($sqlCheckRif);

//Verificar si el EMAIL ya existe
$sqlCheckEmail = "SELECT id FROM users WHERE email = ? LIMIT 1";
$stmtCheckEmail = $base_de_datos->prepare($sqlCheckEmail);

// Actualizar Saldo
$sqlUpdate = "UPDATE users SET name = ?, saldo = ?, vip = ?, limitecredito = ?, updated_at = NOW() WHERE rif = ?";
$stmtUpdate = $base_de_datos->prepare($sqlUpdate);

//Insertar Nuevo
$sqlInsert = "INSERT INTO users (
                rif, name, email, rol, saldo, username, password, 
                remember_token, vip, limitecredito, created_at, updated_at
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
$stmtInsert = $base_de_datos->prepare($sqlInsert);

/** * VARIABLES POR DEFECTO 
 */
$defaultRol   = 2;
$passwordHash = password_hash('123abc*1', PASSWORD_DEFAULT);

$contInsert = 0;
$contUpdate = 0;
$contError  = 0;

foreach ($clientesLista as $cliente) {
    $rif    = $cliente['rif'] ?? null;
    $nombre = $cliente['nombre'] ?? 'Sin Nombre';
    $email  = $cliente['email'] ?? null;
    $saldo  = $cliente['saldo'] ?? 0;
    $vip           = $cliente['vip'] ?? 'N'; 
    $limitecredito = $cliente['limitecredito'] ?? 0;

    
    // Quitamos los espacios del nombre para el campo username
    $usernameSinEspacios = str_replace(' ', '', $nombre);
    // convertir a minúsculas para que sea más estándar
    $usernameSinEspacios = strtolower($usernameSinEspacios); 

    if (!$rif) continue;

    try {
        // Existe el RIF?
        $stmtCheckRif->execute([$rif]);
        $existeRif = $stmtCheckRif->fetch();

        if ($existeRif) {
            // ACTUALIZAR
          $stmtUpdate->execute([$nombre,$saldo, $vip, $limitecredito, $rif]);
            echo "<p style='color:blue;'>RIF <b>$rif</b>, Nombre <b>$nombre</b>: Saldo, VIP y Límite actualizados.</p>";
            $contUpdate++;
        } else {
            //Verificar duplicidad de EMAIL
            if ($email) {
                $stmtCheckEmail->execute([$email]);
                if ($stmtCheckEmail->fetch()) {
                    echo "<p style='color:orange;'>RIF <b>$rif</b>: Error - El correo <b>$email</b> ya existe.</p>";
                    $contError++;
                    continue;
                }
            }

            //  INSERTAR
            $stmtInsert->execute([
                $rif,                 
                $nombre,              
                $email,              
                $defaultRol,          
                $saldo,              
                $usernameSinEspacios, 
                $passwordHash,        
                null, 
                $vip,          
                $limitecredito           
            ]);
            echo "<p style='color:green;'>RIF <b>$rif</b>: Registrado (VIP: $vip).</p>";
            $contInsert++;
        }
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'><strong>Error Crítico (RIF $rif):</strong> " . $e->getMessage() . "</div>";
        $contError++;
    }

}

echo "<hr><h3>Resumen:</h3>";
echo "Nuevos: <b>$contInsert</b> | Actualizados: <b>$contUpdate</b> | Errores: <b>$contError</b>";
?>