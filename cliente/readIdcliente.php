<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/cliente.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
// initialize object
$Cli = new Cliente($db);

//Tomamos el usuario activo dentro de la app
$email = $_GET['email'];

// query products

$id = $Cli->getIdForEmail($email);

// check if more than 0 record found
if($id != ''){
     
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($id);
}

else{
 
    // set response code - 404 Not found
    http_response_code(404);
 
    // tell the user no products found
    echo json_encode(
        array("message" => "El cliente no existe")
    );
}
?>