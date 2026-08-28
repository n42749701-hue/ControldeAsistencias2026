<?php

require_once __DIR__ . "/config.php";

class ConexionPDO
{
    private static $cnn = null;

    private static function connect()
    {
        if (self::$cnn === null) {
            $dsn = 'mysql:host=' . HOST . ';port=' . PORT . ';dbname=' . DATABASE . ';charset=' . CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ];

            try {
                self::$cnn = new PDO($dsn, USERNAME, PASSWORD, $options);
            } catch (PDOException $error) {
                die("ERROR " . $error->getMessage());
            }
        }

        return self::$cnn;
    }

    public static function query($sql)
    {
        $stmt = self::connect()->query($sql);
        return $stmt->fetchAll();
    }

    public static function execute($sql, $valores = [], $returnId = false)
    {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($valores);

        return $returnId ? $pdo->lastInsertId() : $stmt->rowCount();
    }
}
