
<?php

    class Database {

        private $tdb = 'pgsql';  // if you use mysql replace by mysql
        private $host = 'localhost'; 
        private $user = 'postgres';  
        private $pass = 'E2C979ED9C'; 
        private $dbname = 'facebook'; // name of the database
        private $port = '5432'; 


        //Will be the PDO object
        private $dbh;
        private $stmt;
        private $error;

        public function __construct(){
            //Set DSN
            $this->$dsn = $this->tdb.':host='.$this->host.';port='.$this->port.';dbname='.$this->dbname; 
            $options = array(
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );

            //Create PDO instance
            try{
                $this->dbh = new PDO($this->$dsn, $user, $pass);
            }catch(PDOException $e){
                $this->error = $e->getMessage();
                echo $e;
            }
        }

        //Prepare statement with query
        public function query($sql){
            $this->stmt = $this->dbh->prepare($sql);
        }

        //Bind values, to prepared statement using named parameters
        public function bind($param, $value, $type = null){
            if(is_null($type)){
                switch(true){
                    case is_int($value):
                        $type = PDO::PARAM_INT;
                        break;
                    case is_bool($value):
                        $type = PDO::PARAM_BOOL;
                        break;
                    case is_null($value):
                        $type = PDO::PARAM_NULL;
                        break;
                    default:
                        $type = PDO::PARAM_STR;
                }
            }
            $this->stmt->bindValue($param, $value, $type);
        }

        //Execute the prepared statement
        public function execute(){
            return $this->stmt->execute();
        }

        //Return multiple records
        public function resultSet(){
            $this->execute();
            return $this->stmt->fetchAll(PDO::FETCH_OBJ);
        }

        //Return a single record
        public function single(){
            $this->execute();
            return $this->stmt->fetch(PDO::FETCH_OBJ);
        }

        //Get row count
        public function rowCount(){
            return $this->stmt->rowCount();
        }
    }
 
?>
