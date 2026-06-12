<?php
require_once "../src/Models/estudiante.php";
class EstudianteController{
    public function getAll()
    {
        $estudiante=Estudiantes::all();
        echo json_encode($estudiante);
         
    }
}