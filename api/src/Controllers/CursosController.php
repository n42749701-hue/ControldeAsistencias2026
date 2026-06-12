<?php
require_once "../src/Models/cursos.php";
class CursosController{
    public function getAll()
    {
        $cursos=Cursos::all();
        echo json_encode($cursos);
         
    }
}