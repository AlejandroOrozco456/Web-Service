<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Allow, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
header("Allow: GET, POST, PUT, DELETE, OPTIONS, HEAD");
require_once "conexion.php";
require_once "jwt.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){
    if(isset($_GET['user']) && isset($_GET['pass'])){
        $c = conexion();
        $s = $c->prepare("SELECT * FROM users WHERE user=:u AND password=MD5(:p)");
        $s->bindValue(":u", $_GET['user']);
        $s->bindValue(":p", $_GET['pass']);
        $s->execute();
        $s->setFetchMode(PDO::FETCH_ASSOC);
        $r = $s->fetch();
        if($r){
            $jwt = JWT::create(array("user"=>$_GET['user']),Config::SECRET);
            $r = array("login"=>"y", "token"=>$jwt);
        }else{
            $r = array("login"=>"n", "token"=>"Error de usuario/contraseña");
        }
        header("HTTP/1.1 200 OK");
        echo json_encode($r);
    }else{
        header("HTT/1.1 400 Bad Request");
    }
}else{
    header("HTT/1.1 400 Bad Request");
}