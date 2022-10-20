<?php

require_once "connection.php";
require_once "jwt.php";

if(isset($_REQUEST['user']) && isset($_REQUEST['pass'])){
    $user = $_REQUEST['user'];
    $pass = $_REQUEST['pass'];
    $conexion = connection();
    $conexion -> exec("use iot");
    $comando = $conexion ->prepare("SELECT user, role FROM users WHERE user= :u AND pass= :p"); 
    $comando -> bindValue(":u", $user);
    $comando -> bindValue(":p", sha1($pass));
    $comando -> execute();
    $comando -> setFetchMode(PDO :: FETCH_ASSOC);
    $resultado = $comando  -> fetch();
    if ($resultado){
        $resultado = [
            "status" => "ok", 
            "jwt" => JWT :: create($resultado, "123")
    ];
    }else{
        $resultado = ["status" => "error"];
    }
    echo json_encode($resultado);
}else{
    header(("HTTP/1.1 400 Bad Request"));
}