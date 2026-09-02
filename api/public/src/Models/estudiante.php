<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Estudiantes
{
    public static function all()
    {
        $sql = "SELECT * FROM ESTUDIANTES";
        return ConexionPDO::query($sql); //self::$users;
    }
    //actualizar estudiante
    public static function update($id, $data)
    {
        if (isset($data['id'])){
            unset($data['id']);
        }
        $campos=[];
        $valores=[];
        //construir datos
        foreach($data as $columna=>$valor)
            {
                $campos[]="$columna=:$columna";
                $valores[":$columna"]=$valor;
            }
            $stringCampos=implode(",",$campos);
            //preparamos la consulta
            $sql="UPDATE estudiantes SET $stringCampos WHERE id=:id";
            $valores[':id']=$id;
            $result = ConexionPDO::execute($sql, $valores,false);
        return $result;
    }
    //Adicionar Estudiante
    public static function add($data)
    {
        $campos = [];
        $placeholders = [];
        $valores = [];
        //construir datos
        foreach($data as $columna => $valor) {
                $campos[] = $columna;
                $placeholders[] = ":$columna";
                $valores[":$columna"] = $valor;
            }

            $stringCampos = implode(",",$campos);
            $stringPlaceholders = implode(",",$placeholders);
            //preparamos la consulta
            $sql = "INSERT INTO estudiantes ($stringCampos) VALUES ($stringPlaceholders)";
            $result = ConexionPDO::execute($sql, $valores,true);
            return $result;
    }
    //Eliminar Estudiante
    public static function delete($id)
    {
        $sql = "DELETE FROM estudiantes WHERE id=:id";
        $valores = [":id" => $id];
        $result = ConexionPDO::execute($sql, $valores, false);
        return $result;
    }
}
