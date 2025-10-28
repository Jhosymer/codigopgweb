<?php
declare(strict_types=1);

// Para depuración durante el desarrollo
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

class Conexion {
    private string $server;
    private string $user;
    private string $password;
    private string $database;
    private int $port;
    private mysqli $conexion;

    public function __construct() {
        // Leer y validar configuración
        $config = $this->leerConfiguracion();
        $this->asignarParametros($config);

        // Forzar que mysqli lance excepciones
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Establecer la conexión
        try {
            $this->conexion = new mysqli(
                $this->server,
                $this->user,
                $this->password,
                $this->database,
                $this->port
            );
            $this->conexion->set_charset('utf8mb4');
        } catch (mysqli_sql_exception $e) {
            error_log("Error de conexión a MySQL: " . $e->getMessage());
            die(json_encode([
                'error' => 'No se pudo conectar a la base de datos'
            ], JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Lee el archivo JSON de configuración y devuelve el array de credenciales.
     */
    private function leerConfiguracion(): array {
        $ruta = __DIR__ . '/config.txt';

        if (!file_exists($ruta) || !is_readable($ruta)) {
            throw new Exception("No se pudo leer el archivo de configuración en $ruta");
        }

        $json = file_get_contents($ruta);
        $datos = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar JSON: " . json_last_error_msg());
        }

        if (!isset($datos['conexion'])) {
            throw new Exception("Falta la sección 'conexion' en el archivo de configuración");
        }

        return $datos['conexion'];
    }

    /**
     * Asigna los valores leídos del JSON a las propiedades de la clase.
     */
    private function asignarParametros(array $conf): void {
        $this->server   = $conf['server']   ?? '';
        $this->user     = $conf['user']     ?? '';
        $this->password = $conf['password'] ?? '';
        $this->database = $conf['database'] ?? '';
        $this->port     = (int)($conf['port'] ?? 3306);
    }

    /**
     * Convierte todos los valores del array a UTF-8.
     */
    private function convertirUTF8(array $array): array {
        array_walk_recursive($array, function (&$item) {
            if ($item !== null && !mb_detect_encoding($item, 'UTF-8', true)) {
                $item = utf8_encode($item);
            }
        });
        return $array;
    }

    /**
     * Ejecuta consultas simples (SELECT) y devuelve un array de resultados.
     */
    public function obtenerDatos(string $sql): array {
        $resultado    = $this->conexion->query($sql);
        $filas        = [];
        while ($row = $resultado->fetch_assoc()) {
            $filas[] = $row;
        }
        return $this->convertirUTF8($filas);
    }

    /**
     * Ejecuta consultas preparadas con parámetros.
     * @param  string     $sqlstr  Consulta con placeholders (?)
     * @param  array      $params  Valores a bindear
     * @param  string     $types   Tipos de datos (i, s, d, b)
     * @return array|false         Array de resultados o false si falló
     */
    public function obtenerDatosPreparada(string $sqlstr, array $params = [], string $types = ''): array|false {
        $stmt = $this->conexion->prepare($sqlstr);
        if ($stmt === false) {
            error_log("Error en prepare(): " . $this->conexion->error);
            return false;
        }

        if ($types !== '' && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        if (!$stmt->execute()) {
            error_log("Error en execute(): " . $stmt->error);
            return false;
        }

        $res        = $stmt->get_result();
        $resultados = [];
        while ($row = $res->fetch_assoc()) {
            $resultados[] = $row;
        }
        $stmt->close();

        return $this->convertirUTF8($resultados);
    }

    /**
     * Ejecuta consultas de modificación (INSERT, UPDATE, DELETE).
     * Devuelve el número de filas afectadas.
     */
    public function nonQuery(string $sqlstr): int {
        $this->conexion->query($sqlstr);
        return $this->conexion->affected_rows;
    }

    /**
     * Ejecuta INSERT y, si tuvo éxito, devuelve el último ID insertado.
     */
    public function nonQueryId(string $sqlstr): int {
        $this->conexion->query($sqlstr);
        return ($this->conexion->affected_rows >= 1)
            ? $this->conexion->insert_id
            : 0;
    }

    /**
     * Encripta un string con MD5.
     */
    protected function encriptar(string $string): string {
        return md5($string);
    }

    /**
     * Permite obtener la instancia raw de mysqli si es necesario.
     */
    public function getConexion(): mysqli {
        return $this->conexion;
    }
}
