<?php

class Listado{
 
    // database connection and table name
    private $conn;
    private $table_listado = "listados";
    private $table_listado_consumidores = "listadoxconsumidor";
    private $table_listado_productos = "listadoxproductos";
    
 
    // object properties
    public $idListado;
    public $idCategoria;
    public $fechaCreacion;
    public $fechaCobro;
    public $fechaCompra;
    public $idCliente;
    public $nombre;
    public $filas;
    public $creado;
    public $listadoCategorias;

    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
        
    }

// read data
function readData(){

    // select all query
    $query = "SELECT li.idListado, li.nombre, p.descripcion AS 'producto', lp.cant AS 'cantidad', c.email as 'cliente' 
    FROM " . $this->table_listado . " li 
    JOIN listadoxproductos lp ON lp.idListado = li.idListado
    JOIN listadoxcliente lc ON lc.idListado = li.idListado
    JOIN productos p ON p.idProducto = lp.idProducto
    JOIN clientes c ON c.idCliente = lc.idCliente
    WHERE c.email like 'admin'
    ORDER BY li.nombre DESC";

    // prepare query statement
    $stmt = $this->conn->prepare($query);
 
    // execute query
    $stmt->execute();
 
    return $stmt;
}

    // read list name, client filtering
    function readName($id){

        // select all query
        $query = "SELECT li.idListado, lc.nombre FROM " . $this->table_listado . " li 
        JOIN " . $this->table_listado_consumidores . " lc ON lc.idListado = li.idListado 
        JOIN clientes c ON c.idCliente = lc.idCliente 
        WHERE c.idCliente = '".$id."'";

        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

//Creamos el listado de compras en la tabla listado
function createListado(){

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_listado . "
    (`idListado`, `fechaCreacion`, `fechaCobro`, `fechaCompra`, `idCliente`) VALUES
    (NULL, curdate(), curdate(), curdate(), '".$this->idCliente."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->fechaCreacion=htmlspecialchars(strip_tags($this->fechaCreacion));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':fechaCreacion', $this->fechaCreacion);
    $stmt->bindParam(':fechaCobro', $this->fechaCobro);
    $stmt->bindParam(':fechaCompra', $this->fechaCompra);
    $stmt->bindParam(':idCliente', $this->idCliente);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

function deleteListCompra(){
    //Insertamos query
    $query = "DELETE FROM " . $this->table_listado_productos . " WHERE idListado = ".$this->idListado.";";
    $query2 = "DELETE FROM " . $this->table_listado_consumidores . " WHERE idListado = ".$this->idListado.";";
    $query3 = "DELETE FROM " . $this->table_listado . " WHERE idListado = ".$this->idListado.";";
    // DELETE FROM " . $this->table_listado_consumidores . " WHERE idListado = ".$this->idListado.";
    // DELETE FROM " . $this->table_listado . " WHERE idListado = ".$this->idListado.";";
    //Preparamos la query
    $stmt = $this->conn->prepare($query);
    $stmt2 = $this->conn->prepare($query2);
    $stmt3 = $this->conn->prepare($query3);

    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute() && $stmt2->execute() && $stmt3->execute()){

        return true;
    }

    return false;
}

//Creamos el listadoxproducto/categoria
function createListXCategorias(){

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_listado_productos . "
    (`idListadoxProducto`, `cant`, `escaneado`, `idCategoriaFK`, `idListado`) VALUES
    (NULL, 1,0, '".$this->idCategoria."', '".$this->idListado."')";

    var_dump($query);
    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->idCategoria=htmlspecialchars(strip_tags($this->idCategoria));
    $this->idListado=htmlspecialchars(strip_tags($this->idListado));

    // bind the values
    $stmt->bindParam(':idCategoria', $this->idCategoria);
    $stmt->bindParam(':idListado', $this->idListado);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

//Creamos el listadoXCliente
function createListXConsumidores(){

    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_listado_consumidores . "
    (`idListadoxCliente`, `fechaCobro`, `fechaCompra`, `fechaCreacion`, `idCliente`, `idListado`, `nombre`) VALUES
    (NULL, curdate(), curdate(), curdate(), '".$this->idCliente."', '".$this->idListado."', '".$this->nombre."')";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->idCliente=htmlspecialchars(strip_tags($this->idCliente));
    $this->idListado=htmlspecialchars(strip_tags($this->idListado));
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':idCliente', $this->idCliente);
    $stmt->bindParam(':idListado', $this->idListado);
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    if($stmt->execute()){

        return true;
    }

    return false;
}

function getId(){
    // select all query
    $query = "SELECT LAST_INSERT_ID() as 'idListado' FROM " . $this->table_listado . "";

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

//Le marcamos en 1 la casilla creado de la tabla listado
function getList(){

    //Insertamos query
	$query = "SELECT * FROM
    " . $this->table_listado . " l
    JOIN listadoxcliente lc on lc.idListado = l.idListado
    JOIN listadoxsubcategoria ls on ls.idListado = l.idListado
    WHERE l.idListado like '".$this->idListado."'";

    //Preparamos la query
    $stmt = $this->conn->prepare($query);

    // sanitize
    $this->nombre=htmlspecialchars(strip_tags($this->nombre));

    // bind the values
    $stmt->bindParam(':nombre', $this->nombre);


    //Ejecutamos el script y corroboramos si la query esta OK
    $stmt->execute();

    return $stmt;
}
    
//Creamos el listado de compras en la tabla listadoxsubcategorias
function createCategory($array_cat){
    
    //Insertamos query
	$query = "INSERT INTO listadoxsubcategoria
    (`idListado`, `idCategoria`) VALUES ";

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