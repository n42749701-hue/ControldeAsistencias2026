<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Asistencias
{
    public static function all()
    {
        $sql = "SELECT * FROM asistencias";
        return ConexionPDO::query($sql); //self::$users;
    }
    //actualizar asistencia
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
            $sql="UPDATE asistencias SET $stringCampos WHERE id=:id";
            $valores[':id']=$id;
            $result = ConexionPDO::execute($sql, $valores,false);
        return $result;
    }
    //Adicionar Asistencia
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
            $sql = "INSERT INTO asistencias ($stringCampos) VALUES ($stringPlaceholders)";
            $result = ConexionPDO::execute($sql, $valores,true);
            return $result;
    }
    //Eliminar Asistencia
    public static function delete($id)
    {
        $sql = "DELETE FROM asistencias WHERE id=:id";
        $valores = [":id" => $id];
        $result = ConexionPDO::execute($sql, $valores, false);
        return $result;
    }
}
