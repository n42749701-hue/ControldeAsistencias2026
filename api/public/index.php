<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
require_once __DIR__ . "/../src/Router.php";
require_once __DIR__ . "/../src/Controllers/UserController.php";
require_once __DIR__ . "/../src/Controllers/ProductoController.php";
require_once __DIR__ . "/../src/Controllers/EstudianteController.php";
require_once __DIR__ . "/../src/Controllers/AsignacionesController.php";
require_once __DIR__ . "/../src/Controllers/CursosController.php";
require_once __DIR__ . "/../src/Controllers/DocentesController.php";
require_once __DIR__ . "/../src/Controllers/AsistenciasController.php";
require_once __DIR__ . "/../src/Controllers/MateriasController.php";
require_once __DIR__ . "/../src/Controllers/InscripcionesController.php";
use App\Router;

$route=new Router();
//direccion para usuarios
$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll'); 
//direccion de productos
$route->add('GET','/productos','ProductoController@getAll'); 
$route->add('PUT','/productos/{id}','ProductoController@update');
$route->add('POST','/productos','ProductoController@add'); 
$route->add('DELETE','/productos/{id}','ProductoController@delete');
//direccion de estudiantes
$route->add('GET','/estudiantes','EstudianteController@getAll');
//$route->add('PUT','/estudiantes/{id}','EstudianteController@update');
//$route->add('POST','/estudiantes','EstudianteController@add');
//$route->add('DELETE','/estudiantes/{id}','EstudianteController@delete');
//direccion de asignaciones
$route->add('GET','/asignaciones','AsignacionesController@getAll');
//direccion de cursos
$route->add('GET','/cursos','CursosController@getAll');
//direccion de docentes
$route->add('GET','/docentes','DocentesController@getAll');
//direccion de asistencias
$route->add('GET','/asistencias','AsistenciasController@getAll');
//$route->add('PUT','/asistencias/{id}','AsistenciasController@update');
//$route->add('POST','/asistencias','AsistenciasController@add');
//$route->add('DELETE','/asistencias/{id}','AsistenciasController@delete');
//direccion de materias
$route->add('GET','/materias','MateriasController@getAll');
//direccion de inscripciones
$route->add('GET','/inscripciones','InscripcionesController@getAll');





$route->run();
