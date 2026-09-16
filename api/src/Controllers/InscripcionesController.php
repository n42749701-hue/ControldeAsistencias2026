<?php
require_once __DIR__ . "/../Models/inscripciones.php";
class InscripcionesController{
    public function getAll()
    {
        $inscripciones = Inscripciones::all();
        echo json_encode($inscripciones);
    }

    public function getById($id)
    {
        $inscripcion = Inscripciones::find($id);
        if ($inscripcion) {
            echo json_encode($inscripcion);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Inscripcion no encontrada",
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

        $errores = $this->validarDatos($data);
        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode([
                "status" => "error",
                "message" => "Existen errores de validacion",
                "errores" => $errores,
            ]);
            return;
        }

        $inscripcion = Inscripciones::update($id, $data);
        if ($inscripcion) {
            echo json_encode(["estado" => true, "message" => "Inscripcion actualizada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo actualizar la inscripcion"]);
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

        $errores = $this->validarDatos($data);
        if (count($errores) > 0) {
            http_response_code(422);
            echo json_encode(["status" => "error", "message" => "Existen errores de validacion", "errores" => $errores]);
            return;
        }

        $inscripcion = Inscripciones::add($data);
        if ($inscripcion) {
            echo json_encode(["estado" => true, "message" => "Inscripcion adicionada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo adicionar la inscripcion"]);
    }

    public function delete($id)
    {
        $inscripcion = Inscripciones::delete($id);
        if ($inscripcion) {
            echo json_encode(["estado" => true, "message" => "Inscripcion eliminada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo eliminar la inscripcion"]);
    }

    private function validarDatos($data)
    {
        $errores = [];
        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        foreach (['cod_estudiante', 'cod_curso', 'gestion'] as $campo) {
            if (!isset($data[$campo]) || trim((string) $data[$campo]) === '') {
                $errores[] = "El campo $campo es obligatorio";
            }
        }
        return $errores;
    }
}
