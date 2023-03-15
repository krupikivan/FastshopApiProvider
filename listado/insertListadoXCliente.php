<?php

// required headers
// header("Access-Control-Allow-Origin: http://localhost/api/");
header("Access-Control-Allow-Origin: https://aqueous-fjord-12024.herokuapp.com/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Obtener archivos para la conexion
include_once '../config/database.php';
include_once '../objects/listado.php';

//Obtener datos de POST desde un JSON
$data = json_decode(file_get_contents("php://input"));

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar listado
    $listado = new Listado($db);

    // Setear valores de listado
    $listado->idListado = $data->idListado;
    $listado->idCliente = $data->idCliente;
    //Creamos el listado
    if($listado->createListXClien()){

            // set response code
            http_response_code(200);

            //Mostramos mensaje
            echo json_encode(array("message" => "Agregado!"));
        
    }

    //Mensaje sino se pudo crear
    else{

        //Seteamos estado
        http_response_code(400);

        echo json_encode(array("message" => "No agregado!"));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    echo json_encode(array("message" => "Se requieren datos"));
}
?>