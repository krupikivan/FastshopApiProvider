<?php

header("Access-Control-Allow-Origin: http://localhost/api/");
//header("Access-Control-Allow-Origin: http://app-1538168783.000webhostapp.com/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//Connection files
include_once 'config/database.php';
include_once 'objects/cliente.php';

//Generate JWT (JSON web token)
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


//POST data from JSON
$datas = file_get_contents("php://input");
$data = json_decode($datas);

//If username and password posted
if($data->username && $data->password){
    //Database connection
    $database = new Database();
    $db = $database->getConnection();

    //Instance client
    $cliente = new cliente($db);

    //Set values
    $cliente->username = $data->username;
    $cliente_exist = $cliente->clientExist();
    
    
    //Check if username and password is correct
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
        //Generate JWT
        $jwt = JWT::encode($token, $key);
        echo json_encode(
            array(
                "username" => $cliente_name,
                "idCliente" => (INT)$cliente_id,
                "token" => $jwt
            )
        );
    }
    //Error
    else{
    
        http_response_code(401);
    
        echo json_encode(array("message" => "Login error."));
    }
}
?>