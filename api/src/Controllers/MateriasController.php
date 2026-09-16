<?php
require_once __DIR__ . "/../Models/materias.php";
class MateriasController{
    public function getAll()
    {
        $materias = Materias::all();
        echo json_encode($materias);
    }

    public function getById($id)
    {
        $materia = Materias::find($id);
        if ($materia) {
            echo json_encode($materia);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Materia no encontrada",
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
            echo json_encode(["status" => "error", "message" => "Existen errores de validacion", "errores" => $errores]);
            return;
        }

        $materia = Materias::update($id, $data);
        if ($materia) {
            echo json_encode(["estado" => true, "message" => "Materia actualizada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo actualizar la materia"]);
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

        $materia = Materias::add($data);
        if ($materia) {
            echo json_encode(["estado" => true, "message" => "Materia adicionada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo adicionar la materia"]);
    }

    public function delete($id)
    {
        $materia = Materias::delete($id);
        if ($materia) {
            echo json_encode(["estado" => true, "message" => "Materia eliminada correctamente"]);
            return;
        }

        http_response_code(400);
        echo json_encode(["estado" => false, "message" => "No se pudo eliminar la materia"]);
    }

    private function validarDatos($data)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['nombre_materia']) || trim((string) $data['nombre_materia']) === '') {
            $errores[] = "El campo nombre_materia es obligatorio";
        }

        return $errores;
    }
}
