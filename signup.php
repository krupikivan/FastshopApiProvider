<?php

// required headers
// header("Access-Control-Allow-Origin: http://localhost/FastshopApiProvider/");
header("Access-Control-Allow-Origin: https://aqueous-fjord-12024.herokuapp.com/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Obtener archivos para la conexion
include_once 'config/database.php';
include_once 'objects/cliente.php';

//Obtener datos de POST desde un JSON
$data = json_decode(file_get_contents("php://input"));
//Corroboramos primero que se haya especificado cliente and pass
if($data->email && $data->password){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar cliente
    $cliente = new Cliente($db);

    // Setear valored de cliente
    $cliente->nombre = $data->nombre;
    $cliente->apellido = $data->apellido;
    $cliente->email = $data->email;
    $cliente->password = $data->password;


    //Creamos el cliente
    if($cliente->create()){
        // set response code
        http_response_code(200);

        //Mostramos mensaje
        echo json_encode(array("message" => "Cliente creado!"));
    }

    //Mensaje sino se pudo crear
    else{

        //Seteamos estado
        http_response_code(400);

        echo json_encode(array("message" => "El cliente ya existe"));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    echo json_encode(array("message" => "Se requieren datos"));
}
?>