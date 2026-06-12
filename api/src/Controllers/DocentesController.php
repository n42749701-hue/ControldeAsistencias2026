<?php
require_once "../src/Models/docentes.php";
class DocentesController{
    public function getAll()
    {
        $docentes=Docentes::all();
        echo json_encode($docentes);
         
    }
}