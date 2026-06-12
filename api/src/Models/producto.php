<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Productos
{
    public static function all()
    {
        $sql = "SELECT * FROM productos";
        return ConexionPDO::query($sql); //self::$users;
    }
}
