<?php

require_once(LIB_PATH.DS."config.php");
    
class MySQLDatabase{
    private $connection;
    
    function __construct(){
        $this->open_connection();
    }
    
    public function open_connection(){
        $this->connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
        if(mysqli_connect_errno()){
            die("Database connection failed: " .'('. mysqli_connect_errno(). ')');
        } 
    }
    
    public function close_connection(){
        
        if(isset($this->connection)){
            mysqli_close($this->connection);
            unset($this->connection);
        }
    }
    
    public function query($sql){
        $result = mysqli_query($this->connection, $sql);
        $this->confirm_query($result);
        return $result;
    }
    
    private function confirm_query($result){
        if(!$result){
            die("Database query failed. " . mysqli_error() );
        }
    }
    
    public function escape_value($string){
       $escape_string = mysqli_real_escape_string($this->connection, $string);
        return $escape_string;
    }
    
    //database nuetral function
    
    public function fetch_array($result_set){
        return mysqli_fetch_array($result_set);
    }
    
    public function num_rows($result_set){
        return mysqli_num_rows($result_set);
    }
    
    public function insert_id(){
        return mysqli_insert_id($this->connection);
    }
    
    public function affected_row(){
        return mysqli_affected_rows($this->connection);
    }
    
}

$database = new MySQLDatabase();
$db =& $database;
