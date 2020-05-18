<?php 

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

use controller\classes\Logger;
use database\MySqlDatabasePDOConnection;
use environment\Environment;
use controller\classes\FormValidator;
use database\MeaningMySqlPDO;
use database\WordMySqlPDO;

Environment::initEnvironment();

if( isset( $_GET['word']) ){
    try{
        $word = FormValidator::escapeString( $_GET['word'] );
        if( !FormValidator::wordValid( $word ) ){
           exit; 
        }
        $conn = (new MySqlDatabasePDOConnection())->getConnection();
        $meaningDB = new MeaningMySqlPDO($conn, new WordMySqlPDO($conn));
        $meaningList = $meaningDB->filterMeaningByFields( $word, '', '', 10);
        echo $meaningList->getListInJSON();

    }catch( PDOException $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }catch( Exception $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }catch( Error $e ){
        Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
    }finally{
        $conn = null;
    }
}else{
    $host  = $_SERVER['HTTP_HOST'];
    header("Location: http://$host");
    exit;
}
?>