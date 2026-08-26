<?php
require_once __DIR__ . "/../Models/inscripciones.php";
class InscripcionesController{
    public function getAll()
    {
        $inscripciones=Inscripciones::all();
        echo json_encode($inscripciones);
         
    }
}
