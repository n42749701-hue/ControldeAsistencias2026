<?php
require_once __DIR__ . "/../Models/asignaciones.php";
class AsignacionesController{
    public function getAll()
    {
        $asignaciones=Asignaciones::all();
        echo json_encode($asignaciones);
         
    }
}
