<?php
require_once __DIR__ . '/config.php';

class ConexionPDO
{
    private static ?PDO $cnn = null;

    public static function connect(): PDO
    {
        if (self::$cnn instanceof PDO) {
            return self::$cnn;
        }

        $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DATABASE . ';charset=' . CHARSET;
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if (SSL_CA !== '' && defined('PDO::MYSQL_ATTR_SSL_CA')) {
            $options[PDO::MYSQL_ATTR_SSL_CA] = SSL_CA;
            $options[PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT] = true;
        }

        try {
            self::$cnn = new PDO($dsn, USERNAME, PASSWORD, $options);
        } catch (PDOException $error) {
            throw new RuntimeException('No se pudo conectar con la base de datos.', 0, $error);
        }

        return self::$cnn;
    }

    public static function query(string $sql, array $param=[]): array
    {
        $stmt = self::connect()->prepare($sql);
        $stmt->execute($param);
        return $stmt->fetchAll();
    }

    public static function execute(string $sql, array $param = [], bool $returnId = false)
    {
        $db = self::connect();
        $stmt = $db->prepare($sql);
        $stmt->execute($param);

        if ($returnId) {
            return $db->lastInsertId();
        }

        return $stmt->rowCount();
    }
}
