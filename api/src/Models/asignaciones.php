<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Asignaciones
{
    public static function all()
    {
        $sql = "SELECT * FROM asignaciones";
        return ConexionPDO::query($sql); //self::$users;
    }
}
