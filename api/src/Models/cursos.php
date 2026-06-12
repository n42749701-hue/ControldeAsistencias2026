<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Cursos
{
    public static function all()
    {
        $sql = "SELECT * FROM cursos";
        return ConexionPDO::query($sql); //self::$users;
    }
}
