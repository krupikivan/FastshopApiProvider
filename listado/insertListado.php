<?php

// required headers
header("Access-Control-Allow-Origin: http://localhost/api/");
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

    // Setear valored de listado
    $listado->nombre = $data->nombre;

    //Creamos el listado
    if($listado->createName()){
        $id = $listado->getId();

            // set response code
            http_response_code(200);

            //Mostramos mensaje
            echo json_encode(array("idListado" => $id));
        
    }

    //Mensaje sino se pudo crear
    else{

        //Seteamos estado
        http_response_code(400);

        //El listado ya existe
        echo json_encode(array("message" => "Exist"));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    //Se requieren datos
    echo json_encode(array("message" => "Data"));
}
?>