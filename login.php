<?php

header("Access-Control-Allow-Origin: http://localhost/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//Obtener archivos para la conexion
include_once 'config/database.php';
include_once 'objects/cliente.php';

//Generar JWT (JSON web token)
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


//Obtener datos de POST desde un JSON
$datas = file_get_contents("php://input");
$data = json_decode($datas);
//Corroboramos primero que se haya especificado cliente and pass
if($data->username && $data->password){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar cliente
    $cliente = new cliente($db);

    //Setear valores
    $cliente->username = $data->username;
    $cliente_exist = $cliente->clientExist();
    
    
    //Chequeamos si existe cliente y la contra es valida
    if($cliente_exist && password_verify($data->password, $cliente->password)){
    
        $cliente_name = $cliente->getUsername();
        $cliente_id = $cliente->getId();
        $token = array(
        "iss" => $iss,
        "aud" => $aud,
        "iat" => $iat,
        "nbf" => $nbf,
        "data" => array(
            "username" => $cliente->username,
            "password" => $cliente->password,
            "idCliente" => $cliente->idCliente,
        )
        );
    
        http_response_code(200);
        //Generar JWT
        $jwt = JWT::encode($token, $key);
        echo json_encode(
                array(
                    "username" => $cliente_name,
                    //"message" => "Login exitoso.",
                    "jwt" => $jwt,
                    "idCliente" => $cliente_id
                )
            );
    }
    //Error en login
    else{
    
        http_response_code(401);
    
        echo json_encode(array("message" => "Login error."));
    }
}




/*//Chequeamos si existe cliente y la contra es valida
if($cliente_exist && password_verify($data->password, $cliente->password)){
    $cliente_name = $cliente->getName();
    http_response_code(200);

    echo json_encode(array("message" => "Login exitoso.", "name" => $cliente_name));

}*/
?>