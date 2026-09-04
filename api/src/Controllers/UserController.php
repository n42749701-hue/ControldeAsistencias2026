<?php
require_once __DIR__ . "/../Models/Users.php";
class UserController{
    public function getAll()
    {
        $user=Users::all();
        echo json_encode($user);
         
    }

    public function getById($id)
    {
        $user = Users::find($id);
        if($user) {
            echo json_encode($user);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Usuario no encontrado",
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

        $user=Users::update($id,$data);
        if($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario actualizado correctamente",
            ]);
            return;
        }
        echo json_encode($user);
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

        $user = Users::add($data);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario adicionado correctamente",
            ]);
            return;
        }
        echo json_encode($user);
    }

    public function delete($id)
    {
        $user = Users::delete($id);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario eliminado correctamente",
            ]);
            return;
        }
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el usuario",
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];
        $rolesPermitidos = ["Administrador", "Docente", "Estudiante"];

        if(!is_array($data))
        {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if(!isset($data['username']) || trim($data['username'])=="")
        {
            $errores[] = "El campo username es obligatorio";
        } elseif(strlen($data['username'])>50) {
            $errores[] = "El campo username no debe superar los 50 caracteres";
        }

        if(!isset($data['password_hash']) || trim($data['password_hash'])=="")
        {
            $errores[] = "El campo password_hash es obligatorio";
        } elseif(strlen($data['password_hash'])>255) {
            $errores[] = "El campo password_hash no debe superar los 255 caracteres";
        }

        if(!isset($data['rol']) || trim($data['rol'])=="")
        {
            $errores[] = "El campo rol es obligatorio";
        } elseif(!in_array($data['rol'], $rolesPermitidos)) {
            $errores[] = "El campo rol debe ser Administrador, Docente o Estudiante";
        }

        if(isset($data['cod_docente']) && trim($data['cod_docente'])!="" && !is_numeric($data['cod_docente']))
        {
            $errores[] = "El campo cod_docente debe ser numerico";
        }

        if(isset($data['cod_estudiante']) && trim($data['cod_estudiante'])!="" && !is_numeric($data['cod_estudiante']))
        {
            $errores[] = "El campo cod_estudiante debe ser numerico";
        }

        return $errores;
    }
}
