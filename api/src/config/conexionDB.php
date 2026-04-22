<?php
class ConexionPDO
{
    static private $cnn;
    public function_contruct() {
        $pdo='mysql:host='.HOST.'port='.PORT.';dbname='.DATABASE.';charset='.CHARSET;
        $options=[PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,PDO::ATTR_EMULATE_PREPARES=>false];
        try{
            self::$cnn=new PDO($pdo, USER,PASSWORD,$options)
        }
    }catch(/PDOException $error)
    {
        die("ERROR ".$error->getmessage();)
    }
}