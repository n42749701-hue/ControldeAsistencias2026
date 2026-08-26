<?php
require_once __DIR__ . "/../Models/cursos.php";
class CursosController{
    public function getAll()
    {
        $cursos=Cursos::all();
        echo json_encode($cursos);
         
    }
}
