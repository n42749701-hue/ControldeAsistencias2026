<?php
include_once __DIR__ . "/../Config/conexionDB.php";
class Asistencias
{
    private static $columnasPermitidas = [
        'cod_estudiante',
        'cod_asignacion',
        'cod_usuario_registro',
        'fecha',
        'estado',
        'observacion',
    ];

    public static function all()
    {
        $sql = "SELECT * FROM ASISTENCIAS";
        return ConexionPDO::query($sql); //self::$users;
    }

    public static function find($id)
    {
        $id = (int) $id;
        $sql = "SELECT * FROM ASISTENCIAS WHERE id=$id";
        $result = ConexionPDO::query($sql);
        return count($result) > 0 ? $result[0] : null;
    }

    //actualizar asistencia
    public static function update($id, $data)
    {
        if (isset($data['id'])){
            unset($data['id']);
        }

        $data = self::prepararDatos($data);

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
        $data = self::prepararDatos($data);

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
