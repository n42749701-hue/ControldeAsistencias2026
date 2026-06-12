<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
require_once "../src/Router.php";
require_once "../src/Controllers/UserController.php";
require_once "../src/Controllers/ProductoController.php";
require_once "../src/Controllers/EstudianteController.php";
require_once "../src/Controllers/AsignacionesController.php";
require_once "../src/Controllers/CursosController.php";
require_once "../src/Controllers/DocentesController.php";
require_once "../src/Controllers/AsistenciasController.php";
require_once "../src/Controllers/MateriasController.php";
require_once "../src/Controllers/InscripcionesController.php";
use App\Router;

$route=new Router();
//direccion para usuarios
$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll'); 
//direccion de productos
$route->add('GET','/productos','ProductoController@getAll'); 
//direccion de estudiantes
$route->add('GET','/estudiantes','EstudianteController@getAll');
//direccion de asignaciones
$route->add('GET','/asignaciones','AsignacionesController@getAll');
//direccion de cursos
$route->add('GET','/cursos','CursosController@getAll');
//direccion de docentes
$route->add('GET','/docentes','DocentesController@getAll');
//direccion de asistencias
$route->add('GET','/asistencias','AsistenciasController@getAll');
//direccion de materias
$route->add('GET','/materias','MateriasController@getAll');
//direccion de inscripciones
$route->add('GET','/inscripciones','InscripcionesController@getAll');
$route->run();