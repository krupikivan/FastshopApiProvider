<?php

class Compra{
 
    // database connection and table name
    private $conn;
    private $table_name = "compra";
    private $table_name_2 = "detalleCompra";
 
    // object properties
    public $idCompra;
    public $fechaCompra;
    public $montoTotal;
    public $idCliente;
    public $idProducto;
    public $precio;
    public $cantidad;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read categories
    function read(){

    // select all query
    $query = "SELECT idCategoria, descripcion, idCategoriaSuperiorFK as 'superior'
                FROM " . $this->table_name . " 
                WHERE idCategoriaSuperiorFK is not null
                ORDER BY descripcion asc";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>