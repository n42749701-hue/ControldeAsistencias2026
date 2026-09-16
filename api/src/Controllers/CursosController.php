<?php
require_once __DIR__ . "/../Models/cursos.php";
class CursosController{
 public function getAll()
    {
        $cursos = Cursos::all();
        echo json_encode($cursos); 
    }

    public function getById($id)
    {
        $curso = Cursos::find($id);
        if ($curso) {
            echo json_encode($curso);
            return;
        }

        http_response_code(404);
        echo json_encode([
            "estado" => false,
            "message" => "Datos del curso no encontrados",
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

        $curso = Cursos::update($id, $data);
        if ($curso) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del curso actualizados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo actualizar los datos del curso",
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

        $curso = Cursos::add($data);
        if ($curso) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del curso adicionado correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo adicionar los datos del curso",
        ]);
    }

    public function delete($id)
    {
        $curso = Cursos::delete($id);
        if ($curso) {
            echo json_encode([
                "estado" => true,
                "message" => "Datos del curso eliminados correctamente",
            ]);
            return;
        }

        http_response_code(400);
        echo json_encode([
            "estado" => false,
            "message" => "No se pudo eliminar los datos del curso",
        ]);
    }

    private function validarDatos($data, $esNuevo = false)
    {
        $errores = [];

        if (!is_array($data)) {
            $errores[] = "Los datos enviados no son validos";
            return $errores;
        }

        if (!isset($data['nombre_curso']) || trim((string) $data['nombre_curso']) === "") {
            $errores[] = "El campo nombre_curso es obligatorio";
        }

        if (!isset($data['nivel']) || trim((string) $data['nivel']) === "") {
            $errores[] = "El campo nivel es obligatorio";
        }

        if (!isset($data['paralelo']) || trim((string) $data['paralelo']) === "") {
            $errores[] = "El campo paralelo es obligatorio";
        }

        return $errores;
    }
}
