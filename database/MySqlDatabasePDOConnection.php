<?php
namespace database;

use environment\Environment;
use PDO;
use PDOException;
use interfaces\DatabaseConnection;

class MySqlDatabasePDOConnection implements DatabaseConnection
{
    
    private $databaseHost;
    private $databasePort;
    private $databaseName;
    private $userName;
    private $password;
    
    public function __construct(){
        $this->databaseHost = Environment::getDBHhostName();
        $this->databaseName = Environment::getDBDatabaseName();
        $this->databasePort = Environment::getDBPport();
        $this->userName = Environment::getDBUserName();
        $this->password = Environment::getDBPassword();
    }
    
    public function getConnection(){
        try{
            $conn = new PDO( "mysql:host=$this->databaseHost;port=$this->databasePort" , $this->userName, $this->password );
            $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            if( $this->databaseExist( $conn ) ){
                $conn->exec('use ' . $this->escapeString( $this->databaseName ));
                return $conn;
            }else{
                throw new DatabaseNotExistException("The database does not exist.");
            }            
        }catch( PDOException $e ){
            throw new PDOException("Database connection error.");
        }
    }
    
    private function databaseExist( PDO $conn ):bool{
        $stmt = $conn->prepare('select count( schema_name ) as schema_count from information_schema.schemata where schema_name = ?');
        if( $stmt->execute( array( $this->databaseName ) ) ){
            return intval( ($stmt->fetch( PDO::FETCH_ASSOC ))['schema_count'] ) != 0;
        }
        return false;
    }
    
    private function escapeString( string $str ){
        return htmlspecialchars( stripslashes( trim($str) ) );
    }    
    
}