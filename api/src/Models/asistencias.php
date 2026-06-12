<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Asistencias
{
    public static function all()
    {
        $sql = "SELECT * FROM asistencias";
        return ConexionPDO::query($sql); //self::$users;
    }
}
