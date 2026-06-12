<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Materias
{
    public static function all()
    {
        $sql = "SELECT * FROM materias";
        return ConexionPDO::query($sql); //self::$users;
    }
}
