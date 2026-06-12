<?php
require_once "../src/Models/materias.php";
class MateriasController{
    public function getAll()
    {
        $materias=Materias::all();
        echo json_encode($materias);
         
    }
}