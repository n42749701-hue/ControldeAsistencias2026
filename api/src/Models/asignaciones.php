<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Asignaciones
{
    public static function all()
    {
        $sql = "SELECT * FROM ASIGNACIONES";
        return ConexionPDO::query($sql); //self::$users;
    }
}
