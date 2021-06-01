<?php
require_once "config.php";
function conexion(){
    try{
        $c=new PDO("mysql:host=".Config::HOST.";dbname=".Config::BD, Config::USER, Config::PASS);
        $c->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $c;
    }catch(PDOException $e){
        exit($e->getMessage());
    }
}