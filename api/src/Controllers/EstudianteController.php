<?php
require_once "../src/Models/estudiante.php";
class EstudianteController{
    public function getAll()
    {
        $estudiante=Estudiantes::all();
        echo json_encode($estudiante);
         
    }
    //actualizar estudiante
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

        $estudiante=Estudiantes::update($id,$data);
        if($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Estudiante actualizado correctamente",
            ]);
            return;
        }
        echo json_encode($estudiante);
    }

    //adicionar estudiante
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

        $estudiante = Estudiantes::add($data);
        if ($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Estudiante adicionado correctamente",
            ]);
            return;
        }
        echo json_encode($estudiante);
    }

    //eliminar estudiante
    public function delete($id)
    {
        $estudiante = Estudiantes::delete($id);
        if ($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Estudiante eliminado correctamente",
            ]);
            return;
        }
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el estudiante",
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
        }

        if(!isset($data['nombre']) || trim($data['nombre'])=="")
        {
            $errores[] = "El campo nombre es obligatorio";
        }

        if(!isset($data['apellido']) || trim($data['apellido'])=="")
        {
            $errores[] = "El campo apellido es obligatorio";
        }

        return $errores;
    }
}
