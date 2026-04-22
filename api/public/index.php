<?php
if($_SERVER['REQUEST_METHOD']=='OPTIONS')
    {
        exit;
    }
    require_once "../src/router.php";
    require_once "../src/Controllers/UserController.php";
    
    use App\Router;

    $route=new Router();

    $route->add('GET','/user','UserController@getall');

    $route->run();