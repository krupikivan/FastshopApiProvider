<?php
class Database{

    //Especificamos nuestra Base de Datos y conexion LOCAL
    private $host = "localhost";
    private $db_name = "fastshop";
    private $username = "root";
    private $password = "";
/*
    //Especificamos nuestra Base de Datos y conexion WEB
    private $host = "localhost";
    private $db_name = "id7302635_fastshop";
    private $username = "id7302635_fastroot";
    private $password = "Sweb123";
*/

    public $conn;

    //Obtenemos la conexion a la DB
    public function getConnection(){

        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name,
            $this->username, $this->password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Error de conexion: " . $exception->getMessage();
        }
        return $this->conn;
    } 
}
?>