<?php

class Producto{
 
    // database connection and table name
    private $conn;
    private $table_name = "productos";
 
    // object properties
    public $idProducto;
    public $codigo;
    public $descripcion;
    public $categoria;
    public $marca;
    public $precio;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

        // read list products name
        function readProductsCateg($id){

            // select all query
            $query = "SELECT p.idProducto, p.codigo, p.descripcion, c.descripcion as 'categoria', 
            m.nombre as 'marca', p.precio 
            FROM " . $this->table_name . " p 
            JOIN categorias c ON p.idCategoriaFK = c.idCategoria  
            JOIN marcas m on m.idMarca = p.idMarcaFK WHERE c.idCategoria = '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }

}
?>