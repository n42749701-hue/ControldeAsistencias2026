<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
require_once "../src/Router.php";
require_once "../src/Controllers/UserController.php";

use App\Router;

$route=new Router();

$route->add('GET','/','UserController@getAll');
$route->add('GET','/users','UserController@getAll'); 

$route->run();