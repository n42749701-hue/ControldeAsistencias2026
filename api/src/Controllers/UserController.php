<?php
require_once __DIR__ . "/../Models/Users.php";

class UserController
{
    public function getAll()
    {
        $user = Users::all();
        echo json_encode($user); 
    }

    public function getById($id)
    {
        $user = Users::find($id);
        if ($user) {
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
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error codificacion",
                "message" => json_last_error_msg() ?: "JSON inválido",
            ]);
            return;
        }

        $errores = $this->validarDatos($data, false);

        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores,
            ]);
            return;
        }

        if (isset($data['password']) && $data['password'] !== '') {
            $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        unset($data['password']);

        $user = Users::update($id, $data);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario actualizado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar el usuario",
        ]);
    }

    public function add()
    {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
            http_response_code(400);
            echo json_encode([
                "status" => "error codificacion",
                "message" => json_last_error_msg() ?: "JSON inválido",
            ]);
            return;
        }

        $errores = $this->validarDatos($data, true);

        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores,
            ]);
            return;
        }

        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        $user = Users::add($data);
        if ($user) {
            echo json_encode([
                "estado" => true,
                "message" => "Usuario adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo crear el usuario",
        ]);
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

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar el usuario",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['username']) || trim((string) $data['username']) === "") {
            $errores[] = "El campo username es obligatorio";
        } elseif (strlen((string) $data['username']) > 50) {
            $errores[] = "El campo username no debe superar los 50 caracteres";
        }

        if ($esNuevo && (!isset($data['password']) || !is_string($data['password']) || trim($data['password']) === "")) {
            $errores[] = "El campo password es obligatorio";
        } elseif (isset($data['password']) && !is_string($data['password'])) {
            $errores[] = "El campo password debe ser texto";
        } elseif (isset($data['password']) && $data['password'] !== '' && trim($data['password']) === '') {
            $errores[] = "La contraseña no puede contener solo espacios";
        } elseif (isset($data['password']) && strlen($data['password']) > 72) {
            $errores[] = "La contraseña no debe superar los 72 bytes";
        }

        foreach (['id_docente', 'id_estudiante'] as $campo) {
            if (isset($data[$campo]) && $data[$campo] !== '' && (!is_numeric($data[$campo]) || (int) $data[$campo] < 1)) {
                $errores[] = "El campo $campo debe ser un entero positivo";
            }
        }

        return $errores;
    }
}
