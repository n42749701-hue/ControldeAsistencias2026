<?php
require_once "../src/Models/asignaciones.php";
class AsignacionesController{
    public function getAll()
    {
        $asignaciones=Asignaciones::all();
        echo json_encode($asignaciones);
         
    }
}