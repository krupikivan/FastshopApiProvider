<?php

class Categoria{
 
    // database connection and table name
    private $conn;
    private $table_name = "Categorias";
 
    // object properties
    public $idCategoria;
    public $descripcion;
    public $superior;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

    // read categories
    function read(){

    // select all query
    $query = "SELECT idCategoria, Descripcion, IdCategoriaSuperiorFK as 'superior'
                FROM " . $this->table_name . " 
                WHERE IdCategoriaSuperiorFK is not null
                ORDER BY Descripcion asc";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

// read list categories name
function readCategoriesList($id){

    // select all query
    $query = "SELECT c.Descripcion, c.idCategoria FROM " . $this->table_name . " c 
    JOIN listadoxproductos ls ON ls.idCategoriaFK = c.idCategoria 
    WHERE ls.idListado = '".$id."'";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

}
?>