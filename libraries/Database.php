<?php
/**
 * 
 */

class Database {
	// Database variables from config
	private $host     = DB_HOST;
	private $user     = DB_USER;
	private $pass     = DB_PASS;
	private $dbname   = DB_NAME;

    // Database handler variable
    private $dbh;
    // query statement variable
    private $stmt;
    // Error handler variable
    private $error;

    public function __construct(){
        // Set Database Source Name
        $dsn = 'mysql:host='.$this->host.';dbname='.$this->dbname;
        // Set options
        // Persistent database connections can increase performance by checking to see if there is already an established connection to the database
        // Throw an exception if an error occurs. This then allows you to handle the error gracefully.
        $options = array(
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        );

        // Create PDO instance
        try{
            $this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
        }catch(PDOException $e){
            $this->error = $e->getMessage();
            //echo $this->error;
        }
    }

    //prepare SQL statement
    public function query($sql){
        $this->stmt = $this->dbh->prepare($sql);
    }

    // Bind values, to prepared statement using named parameters
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
        // Run PDO bindValue
        $this->stmt->bindValue($param, $value, $type);
    }

    // Execute the prepared statement
    public function execute(){
        return $this->stmt->execute();
    }

    /**
     * Set returns
     */
    
    // Return multiple records as object array
    public function resultSet(){
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Return a single record
    public function single(){
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_OBJ);
    }

    // Return row count
    public function rowCount(){
        return $this->stmt->rowCount();
    }

    // Return the last inserted Id as a string
	  public function lastInsertId(){
		  return $this->dbh->lastInsertId();
	  }

    /**
     * Set Transactions
     * 
     * allows multiple changes to a database all in one batch to avoid 'interuption' errors
     */

    //begin a transaction
    public function beginTransaction(){
        return $this->dbh->beginTransaction();
    }
    //end a transaction (commit changes)
    public function endTransaction(){
        return $this->dbh->commit();
    }
    //cancel a transaction (roll back changes)
    public function cancelTransaction(){
        return $this->dbh->rollBack();
    }
    //Debug (dump the information that was contained in the Prepared Statement)
    public function debugDumpParams(){
        return $this->stmt->debugDumpParams();
    }

}//end class

// Instantiate database (here or on page e.g.tutorial.php
//$database = new Database(); needs to be global here?