<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Authorization, Access-Control-Allow-Methods, Access-Control-Allow-Headers, Allow, Access-Control-Allow-Origin");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS, HEAD");
header("Allow: GET, POST, PUT, DELETE, OPTIONS, HEAD");
require_once "conexion.php";
require_once "jwt.php";
if($_SERVER["REQUEST_METHOD"]=="OPTIONS"){
    exit();
}
$header = apache_request_headers();
$jwt = $header['Authorization'];
if(JWT::verify($jwt,"qwertyuiop")!=0){
    header("HTT/1.1 401 Unauthorized");
    exit();
}
$data = JWT::get_data($jwt, Config::SECRET);

$metodo = $_SERVER["REQUEST_METHOD"];

switch($metodo){
    case "GET":
        if(isset($_GET['id'])){
            $c = conexion();
            $s = $c->prepare("SELECT * FROM sensores WHERE id=:id");
            $s->bindValue(":id", $_GET['id']);
            $s->execute();
            $s->setFetchMode(PDO::FETCH_ASSOC);
            $r = $s->fetch();
        }else{
            $c = conexion();
            $s = $c->prepare("SELECT * FROM sensores");
            $s->execute();
            $s->setFetchMode(PDO::FETCH_ASSOC);
            $r = $s->fetchAll();
        }
        echo json_encode($r);
        break;
    case "POST":
        if(isset($_POST['tipo']) && isset($_POST['valor'])){
            $c = conexion();
            $s = $c->prepare("INSERT INTO sensores(user,tipo,valor,fecha) VALUES(:u,:t,:v,:f)");
            $s->bindValue(":u", $data['user']);
            $s->bindValue(":t", $_POST['tipo']);
            $s->bindValue(":v", $_POST['valor']);
            $s->bindValue(":f", date("Y-m-d H:i:s"));
            $s->execute();
            if($s->rowCount()){
                $id = $c->lastInsertId();
                $r = array("add"=>"y", "id"=>$id);
            }else{
                $r = array("add"=>"n");
            }
            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        }else{
            header("HTT/1.1 400 Bad Request");
        }
        break;
    case "PUT":
        if(isset($_GET['id']) && isset($_GET['tipo']) && isset($_GET['valor'])){
            $c = conexion();
            $s = $c->prepare("UPDATE sensores SET tipo = :t, valor = :v WHERE user = :u AND id = :id");
            $s->bindValue(":id", $_GET['id']);
            $s->bindValue(":u", $data['user']);
            $s->bindValue(":t", $_GET['tipo']);
            $s->bindValue(":v", $_GET['valor']);
            $s->execute();
            if($s->rowCount()){
                $r = array("edit"=>"y");
            }else{
                $r = array("edit"=>"n");
            }
            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        }else{
            header("HTT/1.1 400 Bad Request");
        }
        break;
    case "DELETE":
        if(isset($_GET['id'])){
            $c = conexion();
            $s = $c->prepare("DELETE FROM sensores WHERE user = :u AND id = :id");
            $s->bindValue(":id", $_GET['id']);
            $s->bindValue(":u", $data['user']);

            $s->execute();
            if($s->rowCount()){
                $r = array("del"=>"y");
            }else{
                $r = array("del"=>"n");
            }
            header("HTTP/1.1 200 OK");
            echo json_encode($r);
        }else{
            header("HTT/1.1 400 Bad Request");
        }
        break;
    default:
        header("HTT/1.1 400 Bad Request");
}