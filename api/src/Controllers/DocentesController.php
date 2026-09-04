<?php
require_once __DIR__ . "/../Models/docentes.php";
class DocentesController{
    public function getAll()
    {
        $docentes=Docentes::all();
        echo json_encode($docentes);
         
    }

    public function getById($id)
    {
        $docente = Docentes::find($id);
        if($docente) {
            echo json_encode($docente);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Docente no encontrado",
        ]);
    }

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

        $docente=Docentes::update($id,$data);
        if($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Docente actualizado correctamente",
            ]);
            return;
        }
        echo json_encode($docente);
    }

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

        $docente = Docentes::add($data);
        if ($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Docente adicionado correctamente",
            ]);
            return;
        }
        echo json_encode($docente);
    }

    public function delete($id)
    {
        $docente = Docentes::delete($id);
        if ($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Docente eliminado correctamente",
            ]);
            return;
        }
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el docente",
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];

        if(!is_array($data))
        {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if(!isset($data['CI']) || trim($data['CI'])=="")
        {
            $errores[] = "El campo CI es obligatorio";
        } elseif(strlen($data['CI'])>20) {
            $errores[] = "El campo CI no debe superar los 20 caracteres";
        }

        if(!isset($data['nombre']) || trim($data['nombre'])=="")
        {
            $errores[] = "El campo nombre es obligatorio";
        } elseif(strlen($data['nombre'])>50) {
            $errores[] = "El campo nombre no debe superar los 50 caracteres";
        }

        if(!isset($data['apellido']) || trim($data['apellido'])=="")
        {
            $errores[] = "El campo apellido es obligatorio";
        } elseif(strlen($data['apellido'])>50) {
            $errores[] = "El campo apellido no debe superar los 50 caracteres";
        }

        if(isset($data['telefono']) && strlen($data['telefono'])>15)
        {
            $errores[] = "El campo telefono no debe superar los 15 caracteres";
        }

        return $errores;
    }
}
