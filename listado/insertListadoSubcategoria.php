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
$data = json_decode(file_get_contents("php://input"), true);

//Creamos el vector solamente con los idCat total el idListado es igual para todos
$array_cat;
for($i=0;$i<count($data);$i++){
    $array_cat[$i] = $data[$i]['idCategoria'];
}

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();

    //Instanciar listado
    $listado = new Listado($db);

    // Setear valores de listado
    $listado->idListado = $data[0]['idListado'];
    $listado->filas = count($data);
    //Creamos el listado
    if($listado->createCategory($array_cat)){

            // set response code
            http_response_code(200);

            //Mostramos mensaje
            echo json_encode($listado->idListado);
        
    }

    //Mensaje sino se pudo crear
    else{

        //Seteamos estado
        http_response_code(400);

        echo json_encode(array("message" => "Categorias repetidas!"));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    echo json_encode(array("message" => "Se requieren datos"));
}
?>