<?php
if (!defined('HOST')) {
    $envPath = __DIR__ . '/../../.env';

    if (!file_exists($envPath)) {
        die("ERROR No se encontro el archivo .env");
    }

    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);

        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        $value = trim($value, "\"'");

        putenv($key . '=' . $value);
        $_ENV[$key] = $value;
    }

    define('HOST', getenv('DB_HOST'));
    define('PORT', getenv('DB_PORT'));
    define('DATABASE', getenv('DB_NAME'));
    define('USERNAME', getenv('DB_USER'));
    define('PASSWORD', getenv('DB_PASSWORD'));
    define('CHARSET', getenv('DB_CHARSET') ?: 'charset=utf8');
}
class ConexionPDO
{
    private static ?PDO  $cnn = null;
    public static function connect(): PDO
    {
        $pdo = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DATABASE . ';' . CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ];
        try {
            self::$cnn = new PDO($pdo, USERNAME, PASSWORD, $options);
        } catch (PDOException $error) {
            die("ERROR " . $error->getMessage());
        }
        return self::$cnn;
    }
    //funcion que ejecute una consulta
    public static function query(string $sql, array $param=[]): array
    {
        try {
            $stmt=self::connect()->prepare($sql);
            $stmt->execute($param);
            return $stmt->fetchAll(); //["ok" => $sql];
        } catch (Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }
    // funcion para ejecutar transacion update o add
    public static function execute($sql,array $param , $id) 
    {
     try{
        $db = self::connect();
         $stmt= $db->prepare($sql);
         $res=$stmt->execute($param);
         if ($id == true) {
            return $db->lastInsertId();
         }
         return $res;
     } catch(Exception $e) {
        die("Existe error al procesar datos" . $e->getMessage());
     }
    }
}
