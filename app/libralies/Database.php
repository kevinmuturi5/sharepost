<?php
/*
*PDO Database Class
*connecy to database
*create prepared statements
*bind values
*return rows and results
*/
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;

    private $dbh;
    private $stmt;
    private $error;

    public function __construct(){
        //set dsn
        $dsn = 'mysql:host=' . $this->host . '; dbname=' . $this->dbname;
        $options = array(
           PDO::ATTR_PERSISTENT => true,
           PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );
        //create pdo instance
        try{
            $this->dbh = new PDO($dsn, $this->user,$this->pass,$options);
        }catch(PDOExeption $e){
            $this->error = $e->getmessage();
            echo $this->error;
        } 
    }
    //prepare statement with query
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }
   //bind values
   public function bind ($param, $value,$type=null){
       if(is_null($type)){
           switch(true){
               case is_int($value);
                   $type = PDO::PARAM_INT;
               break;
               case is_bool($value);
                  $type = PDO::PARAM_BOOL;
               break;
               case is_null($value);
                 $type = PDO::PARAM_NULL;
               break;
               default;
                  $type = PDO::PARAM_STR;
                break;

           }
       }
       $this->stmt->bindValue($param,$value,$type);

   }
   //Execut prepared statement
   public function execute(){
       return $this->stmt->execute();
   }
   // get set array as objects of objects
   public function resultSet(){
       $this->execute();
       return $this->stmt->fetchAll(PDO::FETCH_OBJ);
   }
   //get single record as object
   public function single(){
    $this->execute();
    return $this->stmt->fetch(PDO::FETCH_OBJ);
   }
   //get row cont
   public function rowCount(){
       return $this->stmt->rowCount();
   }
}