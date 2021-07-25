<?php

class Notificacion{
 
    // database connection and table name
    private $conn;
    private $table_name = "notificaciones";
 
    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read categories
    function read(){

    // select all query
    $query = "SELECT NotificacionesId, Cuerpo, Titulo
                FROM " . $this->table_name . " 
                ORDER BY NotificacionesId desc";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>