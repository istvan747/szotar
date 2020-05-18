<?php
session_start();

require_once '..' . DIRECTORY_SEPARATOR . 'autoloader.php';

use modell\TestValidator;
use modell\Test;
use controller\classes\Logger;
use controller\classes\MeaningArrayList;
use database\MySqlDatabasePDOConnection;
use database\TestMySqliPDO;
use environment\Environment;
use controller\classes\Session;

Environment::initEnvironment();

if( isset( $_POST["questions"] ) && isset( $_POST["answers"] ) ){
    $test = new Test();
    $test->setTestQuestionsByJSON( $_POST["questions"] );
    $test->setUserName( Session::getUserNameSession());
    $answers = new MeaningArrayList();
    $answers->setListFromJSON( $_POST["answers"] );
    $testValidator = new TestValidator( $test, $answers );
    if( $testValidator->testValidationSuccess() ){
        try{            
            $conn = (new MySqlDatabasePDOConnection())->getConnection();            
            $testDB = new TestMySqliPDO($conn);
            $testDB->saveTest( $testValidator );            
        }catch( PDOException $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }catch( Exception $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }catch( Error $e ){
            Logger::log( $e->getCode() . ', ' . $e->getMessage() . ', ' . $e->getFile() . ', ' . $e->getLine() . ', ' . $e->getTraceAsString() );
        }finally{
            $conn = null;
        }
    }
    echo $testValidator->getBadAnswers()->getListInJSON();    
}
?>