<?php
require_once "../src/Models/Users.php";
class UserController{
public function getAll()
{
    $user=User::all();
    echo json_encode($user);
}
}