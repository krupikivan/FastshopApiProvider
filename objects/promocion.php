<?php

class Promocion{
 
    // database connection and table name
    private $conn;
    private $table_name = "promocionxproducto";
 
    // object properties
    public $idPromocion;
    public $fechaFin;
    public $fechaInicio;
    public $producto;
    public $promocion;
    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read products
    function read(){

    // select all query
    $query = "SELECT pp.idPromocion, DATE_FORMAT(pp.fechaFin,'%d-%m-%y') as 'fechaFin', DATE_FORMAT(pp.fechaInicio,'%d-%m-%y') as 'fechaInicio', pr.descripcion AS 'producto', p.descripcion AS 'promocion' 
                FROM " . $this->table_name . " pp 
                JOIN promocion p ON p.idTipoPromocion = pp.idTipoPromocion 
                JOIN productos pr ON pr.idProducto = pp.idProducto 
                WHERE pp.fechaInicio <= NOW() AND pp.fechaFin > NOW()
                ORDER BY
                pp.fechaFin DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

function readStream($queryParam){

    // select all query
    $query = "SELECT pp.idPromocion, pp.fechaFin, pp.fechaInicio, pr.descripcion AS 'producto', p.descripcion AS 'promocion' 
                FROM " . $this->table_name . " pp 
                JOIN promocion p ON p.idTipoPromocion = pp.idTipoPromocion 
                JOIN productos pr ON pr.idProducto = pp.idProducto 
                WHERE pp.fechaInicio <= NOW() AND pp.fechaFin > NOW() AND p.descripcion LIKE '%".$queryParam."%'
                ORDER BY
                pp.fechaFin DESC";
    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>