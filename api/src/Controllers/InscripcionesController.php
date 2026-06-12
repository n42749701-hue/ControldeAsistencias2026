<?php
require_once "../src/Models/inscripciones.php";
class InscripcionesController{
    public function getAll()
    {
        $inscripciones=Inscripciones::all();
        echo json_encode($inscripciones);
         
    }
}