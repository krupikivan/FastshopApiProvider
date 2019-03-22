<?php

class Listado{
 
    // database connection and table name
    private $conn;
    private $table_name = "listados";

 
    // object properties
    public $idListado;
    public $idCat;
    public $fechaCreacion;
    public $fechaCobro;
    public $fechaCompra;
    public $cliente;
    public $nombre;
    public $producto;
    public $cantidad;
    public $filas;
    public $username;

    public $queryParam;
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

// read data
function readData(){

    // select all query
    $query = "SELECT li.idListado, li.nombre, p.descripcion AS 'producto', lp.cant AS 'cantidad', c.username as 'cliente' 
    FROM " . $this->table_name . " li 
    JOIN listadoxproductos lp ON lp.idListado = li.idListado
    JOIN listadoxconsumidores lc ON lc.idListado = li.idListado
    JOIN productos p ON p.idProducto = lp.idProducto
    JOIN clientes c ON c.idCliente = lc.idCliente
    WHERE c.username like 'admin'
    ORDER BY li.nombre DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

    // read list name, client filtering
    function readName($username){

        // select all query
        $query = "SELECT li.idListado, li.nombre FROM " . $this->table_name . " li 
        JOIN listadoxconsumidores lc ON lc.idListado = li.idListado 
        JOIN clientes c ON c.idCliente = lc.idCliente 
        WHERE c.username = '".$username."'";
    
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

// read list products name
function readProductsList($id){

            // select all query
            $query = "SELECT p.descripcion FROM " . $this->table_name . " li 
            JOIN listadoxproductos lp ON lp.idListado = li.idListado 
            JOIN productos p ON p.idProducto = lp.idProducto 
            WHERE li.idListado = '".$id."'";
        
            // prepare query statement
            $stmt = $this->conn->prepare($query);
         
            // execute query
            $stmt->execute();
         
            return $stmt;
        }



//Creamos el listado de compras en la tabla listado
function createName(){

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idListado`, `fechaCreacion`, `fechaCobro`, `fechaCompra`, `nombre`) VALUES
    (NULL, curdate(), '".$this->fechaCobro."', '".$this->fechaCompra."', '".$this->nombre."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->fechaCreacion=htmlspecialchars(strip_tags($this->fechaCreacion));
    $this->fechaCobro=htmlspecialchars(strip_tags($this->fechaCobro));
    $this->fechaCompra=htmlspecialchars(strip_tags($this->fechaCompra));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':fechaCreacion', $this->fechaCreacion);
    $stmt->bindParam(':fechaCobro', $this->$fechaCobro);
    $stmt->bindParam(':fechaCompra', $this->fechaCompra);
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}


function getId(){
    // select all query
    $query = "SELECT idListado FROM " . $this->table_name . " 
    WHERE nombre like '".$this->nombre."'";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();

    //Creamos vector
    $list_arr=array();
    //Extraemos el resultado
    extract($stmt->fetch(PDO::FETCH_ASSOC));
    //Lo metemos en un vector nuevo
    $list_item=array(
        //Tomamos el dato y lo guardamos en la variable
        "idListado" => $idListado
    );
    array_push($list_arr, $list_item);
 
    return $idListado;
}

    
//Creamos el listado de compras en la tabla listadoxsubcategorias
function createCategory($array_cat){
    
    //Insertamos query
	$query = "INSERT INTO listadoxsubcategoria
    (`idListado`, `idCat`) VALUES ";

    for($i = 0; $i < $this->filas; $i++){

        $query .= "('".$this->idListado."','".$array_cat[$i]."')";
        
        if($i != $this->filas-1){
            //Con esto manejamos el uso de las comas entre cada VALUE
            $query .= ",";
        }

    }
    
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){
    return true;
    }

    return false;
    }

}

?>