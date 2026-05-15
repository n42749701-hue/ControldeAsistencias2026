<?php
require_once "../src/Models/Users.php";
class UserController{
    public function getAll()
    {
        $user=Users::all();
        echo json_encode($user);
         
    }
}