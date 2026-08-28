<?php
require_once __DIR__ . "/../Models/docentes.php";
class DocentesController{
    public function getAll()
    {
        $docentes=Docentes::all();
        echo json_encode($docentes);
         
    }
}
