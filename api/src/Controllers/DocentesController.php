<?php
require_once __DIR__ . "/../Models/docentes.php";
class DocentesController{ public function getAll()
    {
        $docente = Docentes::all();
        echo json_encode($docente); 
    }

    public function getById($id)
    {
        $docente = Docentes::find($id);
        if ($docente) {
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

        $docente = Docentes::update($id, $data);
        if ($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del docente actualizados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar los datos del docente",
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

        $docente = Docentes::add($data);
        if ($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del docente adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo adicionar los datos del docente",
        ]);
    }

    public function delete($id)
    {
        $docente = Docentes::delete($id);
        if ($docente) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del docente eliminados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar los datos del docente",
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

        if ($esNuevo && (!isset($data['telefono']) || trim((string) $data['telefono']) === "")) {
            $errores[] = "El campo telefono es obligatorio";
        } elseif (strlen((string) $data['telefono']) > 15) {
            $errores[] = "El campo telefono no debe superar los 15 caracteres";
        }

        return $errores;
    }}
