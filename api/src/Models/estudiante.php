<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Estudiantes
{
    public static function all()
    {
        $sql = "SELECT * FROM estudiantes";
        return ConexionPDO::query($sql); //self::$users;
    }
}
