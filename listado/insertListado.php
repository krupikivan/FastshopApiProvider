<?php

// required headers
header("Access-Control-Allow-Origin: http://localhost/api/");
//header("Access-Control-Allow-Origin: http://app-1538168783.000webhostapp.com/api/");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//Obtener archivos para la conexion
include_once '../config/database.php';
include_once '../objects/listado.php';

//Obtener datos de POST desde un JSON
$getContent = file_get_contents("php://input");
$data = json_decode($getContent);
$json = json_encode($data->listadoCategorias);
$array = json_decode($json, true);

//Corroboramos primero que se hayan ingresados datos
if($data != NULL){
    //Obtener conexion con DB
    $database = new Database();
    $db = $database->getConnection();
    //Instanciar listado
    $listado = new Listado($db);

    // Setear valored de listado
    $listado->idCliente = $data->idCliente;
    $listado->nombre = $data->nombre;
    $listado->listadoCategorias = $array;


    //Listado de subcategorias
        // $array_cat;
        // for($i=0;$i<count($array);$i++){
        //     $array_cat[$i] = $array[$i]['idCategoria'];
        // }
    
    // Creamos el listado
    if($listado->createListado()){
        $id = $listado->getId();
        $listado->idListado = $id;

        if($listado->createListXConsumidores()){

            for($i=0;$i<count($array);$i++){
                $listado->idCategoria = $array[$i]['idCategoria'];

                if($listado->createListXCategorias()){
                
                    $list_arr=array();
                    $list_item=array(
                        "idListado" => $id,
                        // "nombre" => $listado->nombre,
                    );
                    array_push($list_arr, $list_item);
                    //Le tuve q sacar el api key porque no me traia las promociones
                    //array_push($promo_arr["PromocionesVigentes"], $promo_item);
        
                    // set response code
                    http_response_code(200);
        
                    //Mostramos mensaje
                    echo json_encode($list_arr);
                }else{
        
                    //Seteamos estado
                    http_response_code(400);
            
                    //El listado ya existe
                    echo json_encode(array("message" => "No se pudo cargar"));
                }
            }

        }     
        //Mensaje sino se pudo crear
        else{
    
            //Seteamos estado
            http_response_code(400);
    
            //El listado ya existe
            echo json_encode(array("message" => "No se pudo cargar"));
        }
    }

    //Mensaje sino se pudo crear
    else{

        //Seteamos estado
        http_response_code(400);

        //El listado ya existe
        echo json_encode(array("message" => "No se pudo cargar"));
    }
}
else{
    //Seteamos estado
    http_response_code(400);
    //Se requieren datos
    echo json_encode(array("message" => "Error Data"));
}
?>