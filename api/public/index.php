<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
require_once __DIR__ . "/../src/Router.php";
require_once __DIR__ . "/../src/Controllers/UserController.php";
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
$route->add('GET','/users/{id}','UserController@getById');
$route->add('PUT','/users/{id}','UserController@update');
$route->add('POST','/users','UserController@add');
$route->add('DELETE','/users/{id}','UserController@delete');
//direccion de estudiantes
$route->add('GET','/estudiantes','EstudianteController@getAll');
$route->add('PUT','/estudiantes/{id}','EstudianteController@update');
$route->add('POST','/estudiantes','EstudianteController@add');
$route->add('DELETE','/estudiantes/{id}','EstudianteController@delete');
//direccion de asignaciones
$route->add('GET','/asignaciones','AsignacionesController@getAll');
//direccion de cursos
$route->add('GET','/cursos','CursosController@getAll');
//direccion de docentes
$route->add('GET','/docentes','DocentesController@getAll');
$route->add('GET','/docentes/{id}','DocentesController@getById');
$route->add('PUT','/docentes/{id}','DocentesController@update');
$route->add('POST','/docentes','DocentesController@add');
$route->add('DELETE','/docentes/{id}','DocentesController@delete');
//direccion de asistencias
$route->add('GET','/asistencias','AsistenciasController@getAll');
$route->add('GET','/asistencias/{id}','AsistenciasController@getById');
$route->add('PUT','/asistencias/{id}','AsistenciasController@update');
$route->add('POST','/asistencias','AsistenciasController@add');
$route->add('DELETE','/asistencias/{id}','AsistenciasController@delete');
//direccion de materias
$route->add('GET','/materias','MateriasController@getAll');
//direccion de inscripciones
$route->add('GET','/inscripciones','InscripcionesController@getAll');





$route->run();
