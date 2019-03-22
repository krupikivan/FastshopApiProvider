<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/listado.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$list = new Listado($db);

//Tomamos el usuario activo dentro de la app
$nombre = $_GET['nombre'];

$list->nombre = $nombre;
// query products

$stmt = $list->listExist();

//$num = $stmt->rowCount();

// check if more than 0 record found
if($stmt == false){
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode(
        array("nombre" => null)
    );
}
 
else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("nombre" => $nombre)
    );
}
?>