<?php
require_once "../src/Models/asistencias.php";
class AsistenciasController{
    public function getAll()
    {
        $asistencias=Asistencias::all();
        echo json_encode($asistencias);
         
    }
}