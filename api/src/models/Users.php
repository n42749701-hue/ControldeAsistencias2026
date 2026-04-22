<?php
class Users{
    private static $users=[
        ["id"=>1, "name"=>'Nelson Tusco','email'=>"nelson@gmail.com"],
        ["id"=>2, "name"=>'Rafa Gorgory','email'=>"Rafa@gmail.com"],
        ["id"=>3, "name"=>'Eder Chavez','email'=>"eder@gmail.com"],
        ["id"=>4, "name"=>'Maria Jose','email'=>"maria@gmail.com"],
    ];
    public static function all() {
        return self::users;
    }
}