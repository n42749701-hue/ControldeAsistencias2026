<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Cursos
{
    public static function all()
    {
        $sql = "SELECT * FROM CURSOS";
        return ConexionPDO::query($sql); //self::$users;
    }
}
