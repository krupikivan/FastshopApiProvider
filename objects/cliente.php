<?php

class Cliente{

    //Conexion a DB y tabla
    private $conn;
    //Insertamos el nombre de la tabla
    private $table_name = "clientes";

    public $idCliente;
    public $username;
    public $apellido;
    public $email;
    public $nombre;
    public $password;
    public $nroDoc;
    public $idTipoDocFK;

    //Constructor con $db como la conexion de DB
    public function __construct($db){
        $this->conn = $db;
        
    }

    //Creamos el cliente que va a utilizar la APP
function create(){
    
    //Existe el cliente?
    if($this->clientExist()){
        return false;
    }

    //Hasheamos la password
    $password_hash = password_hash($this->password, PASSWORD_BCRYPT);


    //Insertamos query
	$query = "INSERT INTO
    " . $this->table_name . "
    (`idCliente`, `username`, `email`, `password`)
VALUES
    (NULL, '".$this->username."', '".$this->email."', '".$password_hash."')";
//Preparamos la query
$stmt = $this->conn->prepare($query);

// sanitize
$this->username=htmlspecialchars(strip_tags($this->username));
$this->email=htmlspecialchars(strip_tags($this->email));
$this->password=htmlspecialchars(strip_tags($this->password));

// bind the values
$stmt->bindParam(':username', $this->username);
$stmt->bindParam(':email', $this->email);
$stmt->bindParam(':password', $password_hash);


//Ejecutamos el script y corroboramos si la query esta OK
if($stmt->execute()){
return true;
}

return false;
}

//Existe el usuario??
function clientExist(){
 
    //Chequear si existe el usuario
    $query = "SELECT *
            FROM " . $this->table_name . "
            WHERE username = '".$this->username."'";
 
    //Preparamos query
    $stmt = $this->conn->prepare( $query );
 
    //Sanitizar
    $this->username=htmlspecialchars(strip_tags($this->username));
 
    //Enlazamos valores
    $stmt->bindParam(1, $this->username);
 
    //Ejecutamos query
    $stmt->execute();
 
    //Numero de filas
    $num = $stmt->rowCount();

    // Si existe asignamos valores al objeto (lo podemos usar para manejo de sesiones)
    if($num>0){

        // Traemos valores
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        //Asignamos valores
        $this->idCliente = $row['idCliente'];
        $this->username = $row['username'];
        $this->email = $row['email'];
        $this->password = $row['password'];


        // True porque existe en la DB
        return true;
    }
 
    // False porque no existe en la DB
    return false;
}

//Devolvemos el id mandando el usuario
function getIdForUsername($username){
 
    //Chequear si existe el usuario
    $query = "SELECT idCliente
            FROM " . $this->table_name . "
            WHERE username like '".$username."'";
 
    //Preparamos query
    $stmt = $this->conn->prepare( $query );

    //Ejecutamos query
    $stmt->execute();
 
    //Numero de filas
    $num = $stmt->rowCount();

    // Si existe asignamos valores al objeto (lo podemos usar para manejo de sesiones)
    if($num>0){

        // Traemos valores
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
 
        //Asignamos valores
        $this->idCliente = $row['idCliente'];

        // True porque existe en la DB
        return $this->idCliente;
    }
 
    // False porque no existe en la DB
    return '';
}

function getUsername(){
    return $this->username;
}

function getId(){
    return $this->idCliente;
}

//Login Cliente
function login(){
    //Seleccionar todas las query
    $query = "SELECT
                * 
            FROM
                " . $this->table_name . "
            WHERE
            username='".$this->username."' AND password='".$this->password."'";
    //Preparar query statement
    $stmt = $this->conn->prepare($query);
    //Ejecutar query
    $stmt->execute();
    return $stmt;
}

}


?>