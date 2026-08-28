<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Docentes
{
    public static function all()
    {
        $sql = "SELECT * FROM DOCENTES";
        return ConexionPDO::query($sql); //self::$users;
    }
}
