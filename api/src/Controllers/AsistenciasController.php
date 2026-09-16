<?php
require_once __DIR__ . "/../Models/asistencias.php";
class AsistenciasController{
 public function getAll()
    {
        $asistencia = Asistencias::all();
        echo json_encode($asistencia); 
    }

    public function getById($id)
    {
        $asistencia = Asistencias::find($id);
        if ($asistencia) {
            echo json_encode($asistencia);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Datos de asistencia no encontrada",
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

        $asistencia = Asistencias::update($id, $data);
        if ($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asistencia actualizados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar los datos de la asistencia",
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

        $asistencia = Asistencias::add($data);
        if ($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asistencia adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo adicionar los datos de la asistencia",
        ]);
    }

    public function delete($id)
    {
        $asistencia = Asistencias::delete($id);
        if ($asistencia) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos de la asistencia eliminados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar los datos de la asistencia",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['cod_estudiante']) || trim((string) $data['cod_estudiante']) === "") {
            $errores[] = "El campo cod_estudiante es obligatorio";
        }

        if (!isset($data['cod_asignacion']) || trim((string) $data['cod_asignacion']) === "") {
            $errores[] = "El campo cod_asignacion es obligatorio";
        }

        if (!isset($data['cod_usuario_registro']) || trim((string) $data['cod_usuario_registro']) === "") {
            $errores[] = "El campo cod_usuario_registro es obligatorio";
        }

        if (!isset($data['fecha']) || trim((string) $data['fecha']) === "") {
            $errores[] = "El campo fecha es obligatorio";
        }

        if (!isset($data['estado']) || trim((string) $data['estado']) === "") {
            $errores[] = "El campo estado es obligatorio";
        } elseif (!in_array($data['estado'], ['Presente', 'Ausente', 'Licencia', 'Retraso', 'Retrasado'], true)) {
            $errores[] = "El campo estado debe ser Presente, Ausente, Licencia o Retraso";
        }

        if (!isset($data['observacion']) || trim((string) $data['observacion']) === "") {
            $errores[] = "El campo observacion es obligatorio";
        }

        return $errores;
    }
}
