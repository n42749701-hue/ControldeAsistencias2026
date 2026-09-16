<?php
require_once __DIR__ . "/../Models/asignaciones.php";
class AsignacionesController
{
 public function getAll()
    {
        $asignaciones = Asignaciones::all();
        echo json_encode($asignaciones); 
    }

    public function getById($id)
    {
        $asignacion = Asignaciones::find($id);
        if ($asignacion) {
            echo json_encode($asignacion);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Datos de asignacion no encontrada",
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

        $asignacion = Asignaciones::update($id, $data);
        if ($asignacion) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asignacion actualizados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar los datos de la asignacion",
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

        $asignacion = Asignaciones::add($data);
        if ($asignacion) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asignacion adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo adicionar los datos de la asignacion",
        ]);
    }

    public function delete($id)
    {
        $asignacion = Asignaciones::delete($id);
        if ($asignacion) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asignacion eliminados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar los datos de la asignacion",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['cod_docente']) || trim((string) $data['cod_docente']) === "") {
            $errores[] = "El campo cod_docente es obligatorio";
        }

        if (!isset($data['cod_materia']) || trim((string) $data['cod_materia']) === "") {
            $errores[] = "El campo cod_materia es obligatorio";
        }

        if (!isset($data['cod_curso']) || trim((string) $data['cod_curso']) === "") {
            $errores[] = "El campo cod_curso es obligatorio";
        }

        return $errores;
    }
}
