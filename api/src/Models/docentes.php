<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Docentes
{
    private static $columnasPermitidas = [
        'CI',
        'nombre',
        'apellido',
        'telefono',
    ];

    public static function all()
    {
        $sql = "SELECT * FROM DOCENTES";
        return ConexionPDO::query($sql); //self::$users;
    }

    public static function find($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM DOCENTES WHERE id=$id";
        $result = ConexionPDO::query($sql);
        return count($result) > 0 ? $result[0] : null;
    }

    public static function update($id, $data)
    {
        if (isset($data['id'])){
            unset($data['id']);
        }

        $data = self::prepararDatos($data);

        $campos=[];
        $valores=[];
        foreach($data as $columna=>$valor)
            {
                $campos[]="$columna=:$columna";
                $valores[":$columna"]=$valor;
            }
            $stringCampos=implode(",",$campos);
            $sql="UPDATE docentes SET $stringCampos WHERE id=:id";
            $valores[':id']=$id;
            $result = ConexionPDO::execute($sql, $valores,false);
        return $result;
    }

    public static function add($data)
    {
        $data = self::prepararDatos($data);

        $campos = [];
        $placeholders = [];
        $valores = [];
        foreach($data as $columna => $valor) {
                $campos[] = $columna;
                $placeholders[] = ":$columna";
                $valores[":$columna"] = $valor;
            }

            $stringCampos = implode(",",$campos);
            $stringPlaceholders = implode(",",$placeholders);
            $sql = "INSERT INTO docentes ($stringCampos) VALUES ($stringPlaceholders)";
            $result = ConexionPDO::execute($sql, $valores,true);
            return $result;
    }

    public static function delete($id)
    {
        $sql = "DELETE FROM docentes WHERE id=:id";
        $valores = [":id" => $id];
        $result = ConexionPDO::execute($sql, $valores, false);
        return $result;
    }

    private static function prepararDatos($data)
    {
        $datos = [];
        foreach($data as $columna => $valor) {
            if(in_array($columna, self::$columnasPermitidas)) {
                $datos[$columna] = $valor === "" ? null : $valor;
            }
        }
        return $datos;
    }
}
