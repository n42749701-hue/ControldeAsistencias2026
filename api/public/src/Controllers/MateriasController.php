<?php
require_once __DIR__ . "/../Models/materias.php";
class MateriasController{
    public function getAll()
    {
        $materias=Materias::all();
        echo json_encode($materias);
         
    }
}
