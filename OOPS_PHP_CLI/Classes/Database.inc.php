<?php

/*
/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan

 * Description of Colors Cli
 *
 * @author Administrator
 * 
; Portions Copyrighted 2018 
*/
final class Database{
    
    private $conn;
    private $options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,);
    public  $connection_status = null;//https://www.cloudways.com/blog/introduction-php-data-objects/
    
    public function __construct($host,$user,$pass,$dbname){
    
        
        $dsn="mysql:host=$host";
        
        
        try {
             $this->conn = new PDO($dsn,$user,$pass,$this->options);//echo 'Success';//$this->conn;//return "Connected successfully";
             return $this->conn;//$this->connection_status = 'Success';
        }
        catch (PDOException $e) {
            //echo "Failure on making connection";//"There is some problem in connection: ";
            return $this->connection_status = 'Failure';//$e->getMessage();
        }
        
        /*
        try {
            $this->conn = new PDO($dsn,$user,$pass);            
            //return true; //echo "Connection Success";
            return "Connection Success";
        }catch(PDOException $e) {
            return 'Error';//die("Error!: ". $e->getMessage());
            //die("Error!: ". $e->getMessage());
        }       
        try {
            $this->conn = new PDO($dsn,$user,$pass);            
            return true; //echo "Connection Success";//return "Connection Success";
        }catch(PDOException $e) {
            return 'Error';//die("Error!: ". $e->getMessage());
            //die("Error!: ". $e->getMessage());
        }
        */
        
        
        
    }

    public function CreateDatabase($dbName,$collation="utf8_general_ci"){
        
        $sql=<<<"db"
            CREATE DATABASE IF NOT EXISTS $dbName
            DEFAULT CHARACTER SET utf8
            DEFAULT COLLATE $collation;
db;
         $stmt=$this->conn->prepare($sql);
         $stmt->execute();
        if($stmt->errorCode()=="00000"){
            return true; //echo "Database Create Success";
             
        }
        else{
         return false; //die($stmt->errorInfo()[2]);
        }
    }


    public function SelectDatabase($dbName){
        
        $sql="use $dbName";
        $stmt=$this->conn->prepare($sql);
        $stmt->execute();
        if($stmt->errorCode()!="00000"){
              return false; //die($stmt->errorInfo()[2]);             
        }
              return true; //"Database Selected";
        
    }

    public function CreateTable(string $table, array $fields,$primary_key=""){
        
        $sql = "CREATE TABLE `$table`(";
        foreach ($fields as $definition) {
          $sql.= $definition['column_name'].' '.$definition['column_type'].', ';
        }
        $sql.= 'date_added'." DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,";
        $sql.= "PRIMARY KEY ($primary_key));";
        $stmt=$this->conn->prepare($sql);
             $stmt->execute();
            if($stmt->errorCode()!="00000"){
                 return ($stmt->errorInfo()[2]); //die ($stmt->errorInfo()[2]);
            }
            return "table $table Created ...";
            
    }

    public function Insert($table,array $data){
        
        $sql="INSERT INTO $table ( ";
        foreach($data as $col=>$val){
            $sql.=" $col,";
            }
        $sql= substr($sql,0,-1);
        $sql.=") VALUES ( ";
        foreach($data as $col=>$val){
            $sql.=" :$col,";
        }
        $sql= substr($sql,0,-1);
        $sql.=" )";

            $stmt = $this->conn->prepare($sql);
            foreach($data as $column=>&$value){
                $stmt->bindParam($column, $value);
            }
            $stmt->execute();
        if($stmt->rowCount()>0){
            //echo "Data Insert Success";
            return true;
        }
        else{
            die("Data Insert Fail!");
        }

    }
}
