<?php

header("Access-Control-Allow-Origin: http://localhost/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

//Archivos para decodificar el JWT
include_once 'config/core.php';
include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;

//Obtenemos POST DATA
$data = json_decode(file_get_contents("php://input"));
 
//Obtenemos JWT
$jwt=isset($data->jwt) ? $data->jwt : "";
 
//Si JWT no es NULL
if($jwt){
 
    try {
        //Decodificamos JWT
        $decoded = JWT::decode($jwt, $key, array('HS256'));
 
        //Seteamos estado
        http_response_code(200);

        //Mostramos datos de usuario
        echo json_encode(array(
            // "message" => "Acceso garantizado.",
            // "data" => $decoded->data,
            "email" => $decoded->data->email,
            "nombre" => $decoded->data->nombre,
            "token" => $decoded->data->password,
            "idCliente" => (INT)$decoded->data->idCliente,
        ));
 
    }
 
    //Significa que el JWT es invalido
    catch (Exception $e){
 
    http_response_code(401);
 
    echo json_encode(array(
        "message" => "Acceso denegado.",
        "error" => $e->getMessage()
    ));
}
}
//El JWT esta vacio
else{
 
    http_response_code(401);
 
    echo json_encode(array("message" => "Token vacio."));
}
?>