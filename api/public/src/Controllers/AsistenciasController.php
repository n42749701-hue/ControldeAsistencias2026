<?php
require_once __DIR__ . "/../Models/asistencias.php";
class AsistenciasController{
    public function getAll()
    {
        $asistencias=Asistencias::all();
        echo json_encode($asistencias);
         
    }
    //actualizar asistencia
    public function update($id)
    {
        $jsonData=file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if(json_last_error()!=JSON_ERROR_NONE)
        {
            echo json_encode(
            [
                "status"=>"error codificacion",
                "message"=>json_last_error_msg(),
            ]);
            return;
        }

        $errores = $this->validarDatos($data);

        if(count($errores)>0)
        {
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores
            ]);
            return;
        }

        $asistencia=Asistencias::update($id,$data);
        if($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Asistencia actualizada correctamente",
            ]);
            return;
        }
        echo json_encode($asistencia);
    }

    //adicionar asistencia
    public function add()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if(json_last_error()!=JSON_ERROR_NONE)
        {
            echo json_encode(
            [
                "status"=>"error codificacion",
                "message"=>json_last_error_msg(),
            ]);
            return;
        }

        $errores = $this->validarDatos($data);

        if(count($errores)>0)
        {
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores
            ]);
            return;
        }

        $asistencia = Asistencias::add($data);
        if ($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Asistencia adicionada correctamente",
            ]);
            return;
        }
        echo json_encode($asistencia);
    }

    //eliminar asistencia
    public function delete($id)
    {
        $asistencia = Asistencias::delete($id);
        if ($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Asistencia eliminada correctamente",
            ]);
            return;
        }
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar la asistencia",
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];
        $estadosPermitidos = ["Presente", "Ausente", "Licencia", "Retraso"];

        if(!is_array($data))
        {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if(!isset($data['cod_estudiante']) || trim($data['cod_estudiante'])=="")
        {
            $errores[] = "El campo cod_estudiante es obligatorio";
        } elseif(!is_numeric($data['cod_estudiante'])) {
            $errores[] = "El campo cod_estudiante debe ser numerico";
        }

        if(!isset($data['cod_asignacion']) || trim($data['cod_asignacion'])=="")
        {
            $errores[] = "El campo cod_asignacion es obligatorio";
        } elseif(!is_numeric($data['cod_asignacion'])) {
            $errores[] = "El campo cod_asignacion debe ser numerico";
        }

        if(!isset($data['fecha']) || trim($data['fecha'])=="")
        {
            $errores[] = "El campo fecha es obligatorio";
        }

        if(!isset($data['estado']) || trim($data['estado'])=="")
        {
            $errores[] = "El campo estado es obligatorio";
        } elseif(!in_array($data['estado'], $estadosPermitidos)) {
            $errores[] = "El campo estado debe ser Presente, Ausente, Licencia o Retraso";
        }

        return $errores;
    }
}
