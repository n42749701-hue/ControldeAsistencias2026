<?php
require_once "config.php";
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
}
