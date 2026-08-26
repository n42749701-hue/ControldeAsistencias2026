<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Inscripciones
{
    public static function all()
    {
        $sql = "SELECT * FROM INSCRIPCIONES";
        return ConexionPDO::query($sql); //self::$users;
    }
}
