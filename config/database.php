<?php
class Database{


    private $host;
    private $db_name;
    private $username;
    private $password;

    public function __construct()
    {
        if(getenv('ENV') == false){
            $this->host = '192.168.1.153:3306';
            $this->db_name = 'fastshop_db';
            $this->username = 'root';
            $this->password = 'root';
        }else{
            $this->host = getenv('MYSQL_HOST');
            $this->db_name = getenv('MYSQL_DB');
            $this->username = getenv('MYSQL_USER');
            $this->password = getenv('MYSQL_PASS');
        }
    }


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