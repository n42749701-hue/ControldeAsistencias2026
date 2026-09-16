<?php
require_once __DIR__ . "/../Models/estudiante.php";
class EstudianteController
{
 public function getAll()
    {
        $estudiante = Estudiantes::all();
        echo json_encode($estudiante); 
    }

    public function getById($id)
    {
        $estudiante = Estudiantes::find($id);
        if ($estudiante) {
            echo json_encode($estudiante);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Estudiante no encontrado",
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

        $estudiante = Estudiantes::update($id, $data);
        if ($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del estudiante actualizados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar los datos del estudiante",
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

        $estudiante = Estudiantes::add($data);
        if ($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del estudiante adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo adicionar los datos del estudiante",
        ]);
    }

    public function delete($id)
    {
        $estudiante = Estudiantes::delete($id);
        if ($estudiante) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del estudiante eliminados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar los datos del estudiante",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['nombre']) || trim((string) $data['nombre']) === "") {
            $errores[] = "El campo nombre es obligatorio";
        } elseif (strlen((string) $data['nombre']) > 50) {
            $errores[] = "El campo nombre no debe superar los 50 caracteres";
        }

        if ($esNuevo && (!isset($data['apellido']) || trim((string) $data['apellido']) === "")) {
            $errores[] = "El campo apellido es obligatorio";
        } elseif (strlen((string) $data['apellido']) > 50) {
            $errores[] = "El campo apellido no debe superar los 50 caracteres";
        }

        if ($esNuevo && (!isset($data['direccion']) || trim((string) $data['direccion']) === "")) {
            $errores[] = "El campo direccion es obligatorio";
        } elseif (strlen((string) $data['direccion']) > 250) {
            $errores[] = "El campo direccion no debe superar los 250 caracteres";
        }

        if ($esNuevo && (!isset($data['telefono']) || trim((string) $data['telefono']) === "")) {
            $errores[] = "El campo telefono es obligatorio";
        } elseif (strlen((string) $data['telefono']) > 15) {
            $errores[] = "El campo telefono no debe superar los 15 caracteres";
        }

        return $errores;
    }
}
